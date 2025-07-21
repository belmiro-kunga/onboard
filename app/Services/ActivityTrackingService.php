<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ActivityTrackingService
{
    /**
     * Registrar atividade do usuário.
     */
    public function trackActivity(User $user, string $action, array $data = []): void
    {
        try {
            $activityData = [
                'user_id' => $user->id,
                'action' => $action,
                'data' => $data,
                'timestamp' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];
            
            // Aqui você pode salvar em uma tabela de atividades
            // Por enquanto, vamos usar cache para demonstração
            $this->cacheActivity($user, $activityData);
            
            // Atualizar último login se for ação de login
            if ($action === 'login') {
                $user->update(['last_login_at' => now()]);
            }
            
        } catch (\Exception $e) {
            Log::error("Erro ao rastrear atividade para usuário {$user->id}: {$e->getMessage()}");
        }
    }

    /**
     * Rastrear progresso em módulo.
     */
    public function trackModuleProgress(User $user, int $moduleId, string $status, float $progress = 0.0): void
    {
        try {
            $progressData = UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'module_id' => $moduleId,
                ],
                [
                    'status' => $status,
                    'progress_percentage' => $progress,
                    'last_accessed_at' => now(),
                ]
            );
            
            $this->trackActivity($user, 'module_progress', [
                'module_id' => $moduleId,
                'status' => $status,
                'progress' => $progress,
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao rastrear progresso do módulo para usuário {$user->id}: {$e->getMessage()}");
        }
    }

    /**
     * Rastrear conclusão de quiz.
     */
    public function trackQuizCompletion(User $user, int $quizId, float $score, bool $passed): void
    {
        $this->trackActivity($user, 'quiz_completion', [
            'quiz_id' => $quizId,
            'score' => $score,
            'passed' => $passed,
        ]);
    }

    /**
     * Rastrear conclusão de simulado.
     */
    public function trackSimuladoCompletion(User $user, $simulado, $tentativa): void
    {
        $this->trackActivity($user, 'simulado_completion', [
            'simulado_id' => $simulado->id,
            'simulado_title' => $simulado->titulo,
            'score' => $tentativa->score,
            'passed' => $tentativa->isPassed(),
            'tempo_gasto' => $tentativa->tempo_gasto,
            'categoria' => $simulado->categoria,
            'nivel' => $simulado->nivel,
        ]);
    }

    /**
     * Rastrear acesso a página.
     */
    public function trackPageAccess(User $user, string $page, array $params = []): void
    {
        $this->trackActivity($user, 'page_access', [
            'page' => $page,
            'params' => $params,
        ]);
    }

    /**
     * Obter atividades recentes do usuário.
     * 
     * @param User $user Usuário para obter atividades
     * @param int $limit Limite de atividades
     * @return array Array de atividades com a seguinte estrutura:
     *               [
     *                 [
     *                   'user_id' => int,
     *                   'action' => string,
     *                   'data' => array,
     *                   'timestamp' => Carbon,
     *                   'ip_address' => string,
     *                   'user_agent' => string
     *                 ]
     *               ]
     */
    public function getRecentActivities(User $user, int $limit = 10): array
    {
        $cacheKey = "user_activities_{$user->id}";
        $activities = Cache::get($cacheKey, []);
        
        return array_slice($activities, 0, $limit);
    }

    /**
     * Obter estatísticas de atividade do usuário.
     * 
     * @param User $user Usuário para obter estatísticas
     * @return array Estatísticas de atividade com a seguinte estrutura:
     *               [
     *                 'total_activities' => int,
     *                 'last_activity' => Carbon|null,
     *                 'most_common_action' => string|null,
     *                 'activity_by_day' => array // Atividades agrupadas por dia
     *               ]
     */
    public function getUserActivityStats(User $user): array
    {
        $recentActivities = $this->getRecentActivities($user, 100);
        
        $stats = [
            'total_activities' => count($recentActivities),
            'last_activity' => null,
            'most_common_action' => null,
            'activity_by_day' => [],
        ];
        
        if (!empty($recentActivities)) {
            $stats['last_activity'] = end($recentActivities)['timestamp'] ?? null;
            
            // Contar ações mais comuns
            $actionCounts = [];
            foreach ($recentActivities as $activity) {
                $action = $activity['action'];
                $actionCounts[$action] = ($actionCounts[$action] ?? 0) + 1;
            }
            
            if (count($actionCounts) > 0) {
                arsort($actionCounts);
                $stats['most_common_action'] = array_key_first($actionCounts);
            }
            
            // Atividades por dia
            $activityByDay = [];
            foreach ($recentActivities as $activity) {
                $date = Carbon::parse($activity['timestamp'])->format('Y-m-d');
                $activityByDay[$date] = ($activityByDay[$date] ?? 0) + 1;
            }
            $stats['activity_by_day'] = $activityByDay;
        }
        
        return $stats;
    }

    /**
     * Verificar se usuário está ativo.
     */
    public function isUserActive(User $user, int $daysThreshold = 7): bool
    {
        $lastActivity = $user->last_login_at;
        
        if (!$lastActivity) {
            return false;
        }
        
        // Garantir que last_login_at seja uma instância de Carbon
        if (is_string($lastActivity)) {
            $lastActivity = Carbon::parse($lastActivity);
        }
        
        return $lastActivity->diffInDays(now()) <= $daysThreshold;
    }

    /**
     * Obter usuários inativos.
     */
    public function getInactiveUsers(int $daysThreshold = 30): array
    {
        return User::where('is_active', true)
            ->where(function ($query) use ($daysThreshold) {
                $query->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<=', now()->subDays($daysThreshold));
            })
            ->get()
            ->all();
    }

    /**
     * Cachear atividade do usuário.
     */
    private function cacheActivity(User $user, array $activityData): void
    {
        $cacheKey = "user_activities_{$user->id}";
        $activities = Cache::get($cacheKey, []);
        
        // Adicionar nova atividade
        $activities[] = $activityData;
        
        // Manter apenas as últimas 100 atividades
        if (count($activities) > 100) {
            $activities = array_slice($activities, -100);
        }
        
        Cache::put($cacheKey, $activities, now()->addDays(30));
    }

    /**
     * Limpar atividades antigas.
     */
    public function cleanupOldActivities(int $daysToKeep = 30): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        $cleanedCount = 0;
        
        // Limpar cache de atividades antigas
        $users = User::all();
        
        foreach ($users as $user) {
            $cacheKey = "user_activities_{$user->id}";
            $activities = Cache::get($cacheKey, []);
            
            $filteredActivities = array_filter($activities, function ($activity) use ($cutoffDate) {
                return Carbon::parse($activity['timestamp'])->gte($cutoffDate);
            });
            
            if (count($filteredActivities) !== count($activities)) {
                Cache::put($cacheKey, array_values($filteredActivities), now()->addDays(30));
                $cleanedCount += count($activities) - count($filteredActivities);
            }
        }
        
        return $cleanedCount;
    }
}