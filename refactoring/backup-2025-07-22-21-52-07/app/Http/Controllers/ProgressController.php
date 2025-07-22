<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use App\Models\UserProgress;
use App\Models\UserGamification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Exibe o progresso geral do usuário.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $period = $request->input('period', 'month');
        
        // Calcular período baseado na seleção
        $startDate = $this->getStartDate($period);
        
        // Buscar dados reais do usuário
        $generalProgress = $this->getGeneralProgress($user, $startDate);
        $timeline = $this->getTimeline($user, $startDate);
        $quizStats = $this->getQuizStats($user, $startDate);
        $moduleProgress = $this->getModuleProgress($user);
        $insights = $this->getInsights($user);
        
        return view('progress.index', [
            'period' => $period,
            'generalProgress' => $generalProgress,
            'timeline' => $timeline,
            'quizStats' => $quizStats,
            'moduleProgress' => $moduleProgress,
            'insights' => $insights
        ]);
    }
    
    /**
     * Obtém a data de início baseada no período selecionado.
     */
    private function getStartDate(string $period): Carbon
    {
        return match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subYears(10), // Todo o período
        };
    }
    
    /**
     * Obtém o progresso geral do usuário.
     */
    private function getGeneralProgress(User $user, Carbon $startDate): array
    {
        // Módulos
        $totalModules = Module::where('is_active', true)->count();
        $completedModules = UserProgress::where('user_id', $user->id)
            ->where('completed_at', '!=', null)
            ->where('created_at', '>=', $startDate)
            ->count();
        
        // Quizzes
        $totalQuizzes = Quiz::where('is_active', true)->count();
        $userAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();
        $completedQuizzes = $userAttempts->where('completed_at', '!=', null)->count();
        $passedQuizzes = $userAttempts->where('completed_at', '!=', null)
            ->where('score', '>=', 70)
            ->count();
        
        // Certificados
        $certificates = Certificate::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->count();
        
        // Gamificação
        $gamification = UserGamification::where('user_id', $user->id)->first();
        $points = $gamification ? $gamification->total_points : 0;
        $level = $gamification ? $gamification->current_level : 'Rookie';
        $streak = $gamification ? $gamification->streak_days : 0;
        
        return [
            'modules' => [
                'completed' => $completedModules,
                'total' => $totalModules,
                'percentage' => $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0
            ],
            'quizzes' => [
                'completed' => $completedQuizzes,
                'passed' => $passedQuizzes,
                'total' => $totalQuizzes,
                'percentage' => $totalQuizzes > 0 ? round(($passedQuizzes / $totalQuizzes) * 100) : 0,
                'average_score' => $userAttempts->where('completed_at', '!=', null)->avg('score') ?? 0
            ],
            'time_spent' => [
                'hours' => 0, // Implementar cálculo de tempo real
                'minutes' => 0
            ],
            'certificates' => $certificates,
            'points' => $points,
            'level' => $level,
            'streak' => $streak
        ];
    }
    
    /**
     * Obtém a timeline de atividades do usuário.
     */
    private function getTimeline(User $user, Carbon $startDate): array
    {
        $timeline = [];
        
        // Atividades de quiz
        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('completed_at', '!=', null)
            ->where('created_at', '>=', $startDate)
            ->with('quiz')
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();
            
        foreach ($quizAttempts as $attempt) {
            $timeline[] = [
                'title' => 'Quiz concluído: ' . $attempt->quiz->title,
                'description' => 'Você completou o quiz com sucesso!',
                'icon' => 'check-circle',
                'color' => $attempt->score >= 70 ? 'green' : 'red',
                'points' => $attempt->quiz->points_reward,
                'date' => $attempt->completed_at
            ];
        }
        
        // Atividades de módulo
        $moduleProgress = UserProgress::where('user_id', $user->id)
            ->where('completed_at', '!=', null)
            ->where('created_at', '>=', $startDate)
            ->with('module')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($moduleProgress as $progress) {
            $timeline[] = [
                'title' => 'Módulo finalizado: ' . $progress->module->title,
                'description' => 'Você completou o módulo com sucesso',
                'icon' => 'book-open',
                'color' => 'blue',
                'points' => 50,
                'date' => $progress->completed_at
            ];
        }
        
        // Certificados
        $certificates = Certificate::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach ($certificates as $certificate) {
            $timeline[] = [
                'title' => 'Novo certificado disponível',
                'description' => 'Você desbloqueou um novo certificado',
                'icon' => 'academic-cap',
                'color' => 'yellow',
                'points' => 200,
                'date' => $certificate->created_at
            ];
        }
        
        // Ordenar por data
        usort($timeline, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });
        
        return array_slice($timeline, 0, 10);
    }
    
    /**
     * Obtém estatísticas de quizzes do usuário.
     */
    private function getQuizStats(User $user, Carbon $startDate): array
    {
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();
            
        $completedAttempts = $attempts->where('completed_at', '!=', null);
        $passedAttempts = $completedAttempts->where('score', '>=', 70);
        
        // Estatísticas por categoria
        $byCategory = [];
        $categoryAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->where('completed_at', '!=', null)
            ->with('quiz')
            ->get()
            ->groupBy('quiz.category');
            
        foreach ($categoryAttempts as $category => $categoryAttempts) {
            $byCategory[$category] = [
                'avg_score' => $categoryAttempts->avg('score') ?? 0,
                'total_attempts' => $categoryAttempts->count(),
                'passed_attempts' => $categoryAttempts->where('score', '>=', 70)->count()
            ];
        }
        
        return [
            'total_attempts' => $attempts->count(),
            'pass_rate' => $completedAttempts->count() > 0 ? round(($passedAttempts->count() / $completedAttempts->count()) * 100) : 0,
            'average_score' => round($completedAttempts->avg('score') ?? 0, 1),
            'best_score' => $completedAttempts->max('score') ?? 0,
            'formatted_time' => '2h 30m', // Implementar cálculo real
            'by_category' => $byCategory
        ];
    }
    
    /**
     * Obtém o progresso por módulo.
     */
    private function getModuleProgress(User $user): array
    {
        $modules = Module::where('is_active', true)->get();
        $progress = [];
        
        foreach ($modules as $module) {
            $userProgress = UserProgress::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->first();
                
            $status = 'not_started';
            $progressPercentage = 0;
            $completedAt = null;
            $isOverdue = false;
            
            if ($userProgress) {
                if ($userProgress->completed_at) {
                    $status = 'completed';
                    $progressPercentage = 100;
                    $completedAt = $userProgress->completed_at;
                } else {
                    $status = 'in_progress';
                    $progressPercentage = $userProgress->progress_percentage ?? 0;
                }
            }
            
            $progress[] = [
                'id' => $module->id,
                'title' => $module->title,
                'category' => $module->category,
                'status' => $status,
                'progress_percentage' => $progressPercentage,
                'completed_at' => $completedAt,
                'is_overdue' => $isOverdue,
                'formatted_time' => '1h 30m' // Implementar cálculo real
            ];
        }
        
        return $progress;
    }
    
    /**
     * Obtém insights comparativos.
     */
    private function getInsights(User $user): array
    {
        // Dados fictícios para comparação - implementar lógica real posteriormente
        return [
            'comparisons' => [
                'modules_vs_average' => 85,
                'quiz_vs_average' => 92,
                'time_vs_average' => 78
            ]
        ];
    }
}