<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserProgress;
use App\Models\Notification;
use App\Models\PointsHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal do usuário.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Garantir que o usuário tenha dados de gamificação
        $this->ensureUserGamification($user);
        
        // Buscar módulos ativos
        $modules = Module::where('is_active', true)
            ->orderBy('order_index')
            ->get();
            
        // Buscar progresso do usuário
        $userProgress = UserProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('module_id');
            
        // Buscar notificações do usuário (últimas 6)
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon ?? 'bell',
                    'color' => $notification->color ?? 'blue',
                    'time' => $notification->created_at->diffForHumans(),
                    'is_read' => $notification->read_at !== null,
                    'created_at' => $notification->created_at,
                ];
            });
            
        // Combinar módulos com progresso do usuário
        $modulesWithProgress = $modules->map(function ($module) use ($userProgress) {
            $progressData = $userProgress->get($module->id);
            $progress = $progressData ? $progressData->progress_percentage : 0;
            $status = $this->determineModuleStatus($progress);
            
            return [
                'id' => $module->id,
                'title' => $module->title,
                'description' => $module->description,
                'estimated_duration' => $module->estimated_duration,
                'points_reward' => $module->points_reward,
                'completion_percentage' => $progress,
                'status' => $status,
                'is_available' => $this->isModuleAvailable($module),
                'category' => $module->category,
                'difficulty_level' => $module->difficulty_level,
                'last_accessed' => $progressData ? $progressData->last_accessed_at?->diffForHumans() : null,
                'time_spent' => $progressData ? $progressData->time_spent : 0,
            ];
        });

        // Obter dados de gamificação do usuário
        $gamification = $user->gamification;
        
        // Calcular estatísticas do usuário
        $stats = [
            'total_points' => $gamification->total_points ?? 0,
            'current_level' => $gamification->formatted_level ?? 'Rookie',
            'level_color' => $gamification->level_color ?? '#6B7280',
            'level_progress' => $gamification->level_progress ?? 0,
            'completed_modules' => $modulesWithProgress->where('status', 'completed')->count(),
            'ranking_position' => $gamification->getCurrentRank(),
            'achievements_count' => $gamification->achievements_count ?? 0,
            'streak_days' => $gamification->streak_days ?? 0,
            'longest_streak' => $gamification->longest_streak ?? 0,
        ];

        // Calcular progresso geral
        $totalModules = $modules->count();
        $completedModules = $modulesWithProgress->where('status', 'completed')->count();
        $inProgressModules = $modulesWithProgress->where('status', 'in_progress')->count();
        $overallPercentage = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

        $progress = [
            'overall_percentage' => $overallPercentage,
            'completed_count' => $completedModules,
            'in_progress_count' => $inProgressModules,
            'total_count' => $totalModules,
            'next_module' => $modulesWithProgress->where('status', 'not_started')->first() ?? 
                           $modulesWithProgress->where('status', 'in_progress')->first(),
        ];

        // Buscar conquistas recentes do usuário
        $recentAchievements = $user->achievements()
            ->orderBy('user_achievements.earned_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($achievement) {
                return [
                    'id' => $achievement->id,
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'rarity' => $achievement->rarity,
                    'rarity_color' => $achievement->rarity_color,
                    'points_reward' => $achievement->points_reward,
                    'earned_at' => $achievement->pivot->earned_at,
                ];
            });

        // Buscar ranking dos top performers
        $topPerformers = \App\Models\UserGamification::with('user')
            ->orderBy('total_points', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($gamification, $index) {
                return [
                    'position' => $index + 1,
                    'user_id' => $gamification->user->id,
                    'name' => $gamification->user->name,
                    'avatar' => $gamification->user->avatar_url,
                    'total_points' => $gamification->total_points,
                    'current_level' => $gamification->formatted_level,
                    'level_color' => $gamification->level_color,
                    'is_current_user' => $gamification->user_id === auth()->id(),
                ];
            });

        // Buscar histórico de pontos recente
        $recentPointsHistory = PointsHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($history) {
                return [
                    'points' => $history->points,
                    'formatted_points' => $history->formatted_points,
                    'reason' => $history->reason,
                    'color' => $history->points_color,
                    'icon' => $history->points_icon,
                    'created_at' => $history->created_at->diffForHumans(),
                ];
            });
        
        return view('dashboard-modern', compact(
            'modulesWithProgress', 
            'notifications', 
            'stats', 
            'progress', 
            'recentAchievements',
            'topPerformers',
            'recentPointsHistory'
        ));
    }

    /**
     * Garantir que o usuário tenha dados de gamificação
     */
    private function ensureUserGamification($user): void
    {
        if (!$user->gamification) {
            \App\Models\UserGamification::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'current_level' => 'Rookie',
                'rank_position' => 999,
                'achievements_count' => 0,
                'streak_days' => 0,
                'longest_streak' => 0,
                'last_activity_date' => now(),
                'level_progress' => 0,
                'badges' => [],
                'statistics' => [
                    'modules_completed' => 0,
                    'quizzes_passed' => 0,
                    'perfect_scores' => 0,
                    'time_spent_minutes' => 0,
                    'average_score' => 0,
                ]
            ]);
        }
    }

    /**
     * Obter posição do usuário no ranking.
     */
    private function getUserRankingPosition($user): int
    {
        // Simular posição no ranking baseado nos pontos
        $totalPoints = $user->getTotalPoints();
        
        // Lógica simples de ranking baseada em pontos
        if ($totalPoints >= 2000) return 1;
        if ($totalPoints >= 1500) return 2;
        if ($totalPoints >= 1000) return 3;
        if ($totalPoints >= 750) return 4;
        if ($totalPoints >= 500) return 5;
        
        return rand(6, 20); // Posição aleatória para demonstração
    }
    
    /**
     * Determina o status do módulo baseado no progresso.
     */
    private function determineModuleStatus(int $progress): string
    {
        if ($progress >= 100) {
            return 'completed';
        } elseif ($progress > 0) {
            return 'in_progress';
        } else {
            return 'not_started';
        }
    }
    
    /**
     * Verifica se o módulo está disponível para o usuário.
     */
    private function isModuleAvailable(Module $module): bool
    {
        // Por enquanto, todos os módulos estão disponíveis
        // Aqui você pode adicionar lógica de pré-requisitos
        return true;
    }
} 