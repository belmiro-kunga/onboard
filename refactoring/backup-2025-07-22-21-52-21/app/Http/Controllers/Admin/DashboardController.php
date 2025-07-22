<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard administrativo.
     */
    public function index(): View
    {
        // Estatísticas gerais
        $stats = $this->getGeneralStats();
        
        // Estatísticas de quizzes
        $quizStats = $this->getQuizStats();
        
        // Atividade recente
        $recentActivity = $this->getRecentActivity();
        
        // Usuários ativos
        $activeUsers = $this->getActiveUsers();
        
        // Métricas de performance
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // Alertas do sistema
        $alerts = $this->getSystemAlerts();
        
        return view('admin.dashboard', compact(
            'stats',
            'quizStats', 
            'recentActivity',
            'activeUsers',
            'performanceMetrics',
            'alerts'
        ));
    }
    
    /**
     * Obter estatísticas gerais do sistema
     */
    private function getGeneralStats(): array
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();
        
        $totalQuizzes = Quiz::count();
        $totalQuizAttempts = QuizAttempt::count();
        $totalCertificates = Certificate::count();
        
        // Calcular taxa média de conclusão
        $totalModules = Module::where('is_active', true)->count();
        $completedProgress = UserProgress::where('status', 'completed')->count();
        $averageCompletionRate = $totalUsers > 0 && $totalModules > 0 
            ? round(($completedProgress / ($totalUsers * $totalModules)) * 100, 1)
            : 0;
        
        return [
            'total_users' => $totalUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'total_quizzes' => $totalQuizzes,
            'total_quiz_attempts' => $totalQuizAttempts,
            'total_certificates' => $totalCertificates,
            'average_completion_rate' => $averageCompletionRate,
        ];
    }
    
    /**
     * Obter estatísticas de quizzes
     */
    private function getQuizStats(): array
    {
        $totalAttempts = QuizAttempt::count();
        $passedAttempts = QuizAttempt::where('passed', true)->count();
        $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0;
        
        $averageScore = QuizAttempt::avg('score') ?? 0;
        $averageScore = round($averageScore, 1);
        
        // Performance por categoria (simulada)
        $byCategory = [
            'onboarding' => ['avg_score' => 85.2, 'attempts' => 45],
            'compliance' => ['avg_score' => 78.9, 'attempts' => 32],
            'technical' => ['avg_score' => 72.1, 'attempts' => 28],
        ];
        
        // Quizzes mais difíceis
        $difficultQuizzes = [];
        try {
            $difficultQuizzes = DB::table('quizzes')
                ->select('quizzes.id', 'quizzes.title', DB::raw('AVG(IFNULL(quiz_attempts.score, 0)) as avg_score'))
                ->leftJoin('quiz_attempts', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
                ->groupBy('quizzes.id', 'quizzes.title')
                ->havingRaw('AVG(IFNULL(quiz_attempts.score, 0)) < 70')
                ->orderByRaw('avg_score ASC')
                ->limit(5)
                ->get()
                ->map(function ($quiz) {
                    return [
                        'title' => $quiz->title,
                        'avg_score' => round($quiz->avg_score, 1),
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar quizzes difíceis: ' . $e->getMessage());
        }
        
        return [
            'pass_rate' => $passRate,
            'average_score' => $averageScore,
            'by_category' => $byCategory,
            'difficult_quizzes' => $difficultQuizzes,
        ];
    }
    
    /**
     * Obter atividade recente
     */
    private function getRecentActivity(): array
    {
        $activities = [];
        
        // Novos usuários
        $newUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($newUsers as $user) {
            $activities[] = [
                'title' => 'Novo usuário registrado',
                'description' => $user->name . ' se juntou ao sistema',
                'date' => $user->created_at,
                'color' => 'blue',
                'icon' => 'user-plus',
            ];
        }
        
        // Certificados recentes
        $recentCertificates = Certificate::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($recentCertificates as $certificate) {
            $activities[] = [
                'title' => 'Certificado emitido',
                'description' => $certificate->user->name . ' obteve certificado',
                'date' => $certificate->created_at,
                'color' => 'green',
                'icon' => 'academic-cap',
            ];
        }
        
        // Ordenar por data
        usort($activities, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });
        
        return array_slice($activities, 0, 10);
    }
    
    /**
     * Obter usuários ativos
     */
    private function getActiveUsers(): array
    {
        return User::with('gamification')
            ->where('is_active', true)
            ->whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'department' => $user->department ?? 'N/A',
                    'last_login' => $user->last_login_at ?? $user->created_at,
                    'total_points' => $user->getTotalPoints(),
                    'level' => $user->getCurrentLevel(),
                ];
            })
            ->toArray();
    }
    
    /**
     * Obter métricas de performance
     */
    private function getPerformanceMetrics(): array
    {
        // Taxa de crescimento (simulada)
        $growthRate = 12.5;
        
        // Taxa de engajamento
        $activeUsersLastWeek = User::where('last_login_at', '>=', now()->subWeek())->count();
        $totalUsers = User::count();
        $engagementRate = $totalUsers > 0 ? round(($activeUsersLastWeek / $totalUsers) * 100, 1) : 0;
        
        // Estatísticas diárias dos últimos 7 dias
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $quizAttempts = QuizAttempt::whereDate('created_at', $date)->count();
            
            $dailyStats[] = [
                'date' => $date,
                'formatted_date' => $date->format('d/m'),
                'quiz_attempts' => $quizAttempts,
            ];
        }
        
        return [
            'growth_rate' => $growthRate,
            'engagement_rate' => $engagementRate,
            'daily_stats' => $dailyStats,
        ];
    }
    
    /**
     * Obter alertas do sistema
     */
    private function getSystemAlerts(): array
    {
        $alerts = [];
        
        // Verificar usuários inativos há muito tempo
        $inactiveUsers = User::where('last_login_at', '<', now()->subDays(30))
            ->orWhereNull('last_login_at')
            ->count();
            
        if ($inactiveUsers > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Usuários Inativos',
                'message' => "{$inactiveUsers} usuários não fazem login há mais de 30 dias",
                'action' => route('admin.users.index'),
            ];
        }
        
        // Verificar quizzes com baixa performance - usando subquery para evitar problemas com GROUP BY
        try {
            // Abordagem alternativa para evitar problemas com ONLY_FULL_GROUP_BY
            $lowPerformanceQuizzes = DB::table('quizzes')
                ->select('quizzes.id')
                ->leftJoin('quiz_attempts', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
                ->groupBy('quizzes.id')
                ->havingRaw('AVG(IFNULL(quiz_attempts.score, 0)) < 60')
                ->get()
                ->count();
                
            if ($lowPerformanceQuizzes > 0) {
                $alerts[] = [
                    'type' => 'error',
                    'title' => 'Quizzes com Baixa Performance',
                    'message' => "{$lowPerformanceQuizzes} quizzes têm pontuação média abaixo de 60%",
                    'action' => route('admin.quizzes.index'),
                ];
            }
        } catch (\Exception $e) {
            // Em caso de erro, não exibir este alerta
            \Log::error('Erro ao buscar quizzes com baixa performance: ' . $e->getMessage());
        }
        
        return $alerts;
    }
}