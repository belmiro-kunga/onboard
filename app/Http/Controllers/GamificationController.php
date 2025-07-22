<?php

namespace App\Http\Controllers;



use App\Repositories\ProgressRepository;use App\Services\AuthService;use App\Models\User;
use App\Models\Achievement;
use App\Models\UserGamification;
use App\Models\PointsHistory;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class GamificationController extends BaseController
{
    /**
     * Exibe o dashboard de gamificação.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return \App\Http\Responses\WebResponse::redirectToLogin();
        }
        
        // Buscar dados de gamificação do usuário
        $gamification = $this->progressRepository->getUserGamification(user->id);
        
        // Se não existe, criar um registro básico
        if (!$gamification) {
            $gamification = UserGamification::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'current_level' => 'Rookie',
                'rank_position' => 0,
                'achievements_count' => 0,
                'streak_days' => 0,
                'longest_streak' => 0,
                'last_activity_date' => now(),
                'level_progress' => 0,
            ]);
        }
        
        // Calcular estatísticas
        $stats = $this->getUserStats($user, $gamification);
        
        // Buscar ranking global
        $globalRanking = $this->getGlobalRanking($user);
        
        // Buscar achievements disponíveis
        $availableAchievements = $this->getAvailableAchievements($user);
        
        return view('gamification.dashboard', compact('stats', 'globalRanking', 'availableAchievements'));
    }

    /**
     * Exibe o ranking de gamificação.
     */
    public function ranking(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return \App\Http\Responses\WebResponse::redirectToLogin();
        }
        
        $ranking = $this->getGlobalRanking($user, 50); // Top 50
        $userRank = $this->getUserRank($user);
        
        return view('gamification.ranking', compact('ranking', 'userRank'));
    }

    /**
     * Exibe as conquistas de gamificação.
     */
    public function achievements(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return \App\Http\Responses\WebResponse::redirectToLogin();
        }
        
        $earnedAchievements = $user->achievements()->withPivot('earned_at')->get();
        $availableAchievements = $this->getAvailableAchievements($user);
        $totalAchievements = Achievement::where('is_active', true)->count();
        
        return view('gamification.achievements', compact('earnedAchievements', 'availableAchievements', 'totalAchievements'));
    }
    
    /**
     * Obtém estatísticas do usuário.
     */
    private function getUserStats(User $user, UserGamification $gamification): array
    {
        // Buscar atividades recentes
        $recentActivities = PointsHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($history) {
                return [
                    'reason' => $history->reason,
                    'points' => $history->points > 0 ? '+' . $history->points : $history->points,
                    'color' => $history->points > 0 ? 'green' : 'red',
                    'date' => $history->created_at->diffForHumans()
                ];
            });
        
        // Buscar conquistas recentes
        $recentAchievements = $user->achievements()
            ->withPivot('earned_at')
            ->orderBy('user_achievements.earned_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($achievement) {
                return [
                    'name' => $achievement->name,
                    'earned_at' => $achievement->pivot->earned_at->diffForHumans(),
                    'rarity_color' => $achievement->rarity_color
                ];
            });
        
        // Calcular próximo nível
        $nextLevelRequirement = $this->calculateNextLevelRequirement($gamification->total_points);
        
        return [
            'total_points' => $gamification->total_points,
            'level_formatted' => $gamification->formatted_level,
            'level_color' => $gamification->level_color,
            'level_progress' => $gamification->level_progress,
            'next_level_requirement' => $nextLevelRequirement,
            'achievements_count' => $gamification->achievements_count,
            'rank_position' => $this->getUserRank($user),
            'recent_activities' => $recentActivities,
            'recent_achievements' => $recentAchievements,
            'streak_days' => $gamification->streak_days,
            'longest_streak' => $gamification->longest_streak
        ];
    }
    
    /**
     * Obtém ranking global.
     */
    private function getGlobalRanking(User $user, int $limit = 10): array
    {
        $ranking = UserGamification::with('user')
            ->orderBy('total_points', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($gamification, $index) {
                return [
                    'position' => $index + 1,
                    'user' => [
                        'id' => $gamification->user->id,
                        'name' => $gamification->user->name,
                        'email' => $gamification->user->email
                    ],
                    'points' => $gamification->total_points,
                    'level_formatted' => $gamification->formatted_level,
                    'achievements_count' => $gamification->achievements_count
                ];
            });
        
        return $ranking->toArray();
    }
    
    /**
     * Obtém posição do usuário no ranking.
     */
    private function getUserRank(User $user): int
    {
        return UserGamification::where('total_points', '>', function ($query) use ($user) {
            $query->select('total_points')
                  ->from('user_gamifications')
                  ->where('user_id', $user->id);
        })->count() + 1;
    }
    
    /**
     * Obtém achievements disponíveis para o usuário.
     */
    private function getAvailableAchievements(User $user): array
    {
        $earnedAchievementIds = $user->achievements()->pluck('achievements.id')->toArray();
        
        return Achievement::where('is_active', true)
            ->whereNotIn('id', $earnedAchievementIds)
            ->get()
            ->map(function ($achievement) use ($user) {
                $progress = $this->calculateAchievementProgress($achievement, $user);
                
                return [
                    'id' => $achievement->id,
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'rarity_formatted' => $achievement->formatted_rarity,
                    'rarity_color' => $achievement->rarity_color,
                    'progress' => $progress
                ];
            })
            ->toArray();
    }
    
    /**
     * Calcula progresso de uma achievement.
     */
    private function calculateAchievementProgress(Achievement $achievement, User $user): array
    {
        $current = 0;
        $required = 1;
        
        switch ($achievement->type) {
            case 'modules_completed':
                $current = $user->completedModules()->count();
                $required = $achievement->condition_data['modules_count'] ?? 1;
                break;
            case 'points_earned':
                $current = $user->gamification->total_points ?? 0;
                $required = $achievement->condition_data['points_required'] ?? 100;
                break;
            case 'quiz_streak':
                $current = $user->gamification->streak_days ?? 0;
                $required = $achievement->condition_data['streak_days'] ?? 7;
                break;
            default:
                $current = 0;
                $required = 1;
        }
        
        $percentage = min(100, ($current / $required) * 100);
        
        return [
            'current' => $current,
            'required' => $required,
            'percentage' => round($percentage, 1)
        ];
    }
    
    /**
     * Calcula pontos necessários para próximo nível.
     */
    private function calculateNextLevelRequirement(int $currentPoints): int
    {
        $levelRequirements = [
            'Rookie' => 100,
            'Beginner' => 500,
            'Explorer' => 1000,
            'Intermediate' => 2500,
            'Advanced' => 5000,
            'Expert' => 10000,
            'Master' => 0
        ];
        
        $currentLevel = $this->getCurrentLevel($currentPoints);
        $nextLevel = $this->getNextLevel($currentLevel);
        
        if (!$nextLevel) {
            return 0; // Já está no nível máximo
        }
        
        return $levelRequirements[$nextLevel] - $currentPoints;
    }
    
    /**
     * Obtém nível atual baseado nos pontos.
     */
    private function getCurrentLevel(int $points): string
    {
        return match(true) {
            $points >= 10000 => 'Master',
            $points >= 5000 => 'Expert',
            $points >= 2500 => 'Advanced',
            $points >= 1000 => 'Intermediate',
            $points >= 500 => 'Explorer',
            $points >= 100 => 'Beginner',
            default => 'Rookie'
        };
    }
    
    /**
     * Obtém próximo nível.
     */
    private function getNextLevel(string $currentLevel): ?string
    {
        $levels = ['Rookie', 'Beginner', 'Explorer', 'Intermediate', 'Advanced', 'Expert', 'Master'];
        $currentIndex = array_search($currentLevel, $levels);

        if ($currentIndex === false || !isset($levels[$currentIndex + 1])) {
            return null;
        }

        return $levels[$currentIndex + 1];
    }
}