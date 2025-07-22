<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserGamification;
use App\Contracts\GamificationServiceInterface;
use App\Contracts\NotificationServiceInterface;
use App\Contracts\EventDispatcherInterface;
use App\Services\PointsService;
use App\Services\LevelService;
use App\Services\StreakService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GamificationService implements GamificationServiceInterface
{
    public function __construct(
        private PointsService $pointsService,
        private LevelService $levelService,
        private StreakService $streakService,
        private NotificationServiceInterface $notificationService,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * Adiciona pontos ao usuário com verificação de level up
     */
    public function addPoints(User $user, int $points, ?string $reason = null): bool
    {
        try {
            DB::transaction(function () use ($user, $points, $reason) {
                // Adicionar pontos
                $success = $this->pointsService->addPoints($user, $points, $reason);
                if (!$success) {
                    throw new \Exception('Falha ao adicionar pontos');
                }
                
                // Atualizar streak
                $this->streakService->updateStreak($user);
                
                // Verificar level up
                $totalPoints = $this->pointsService->getTotalPoints($user);
                $this->levelService->updateUserLevel($user, $totalPoints);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error("Erro no GamificationService::addPoints para usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Remove pontos do usuário
     */
    public function removePoints(User $user, int $points, ?string $reason = null): bool
    {
        try {
            DB::transaction(function () use ($user, $points, $reason) {
                $success = $this->pointsService->removePoints($user, $points, $reason);
                if (!$success) {
                    throw new \Exception('Falha ao remover pontos');
                }
                
                // Atualizar level se necessário
                $totalPoints = $this->pointsService->getTotalPoints($user);
                $this->levelService->updateUserLevel($user, $totalPoints);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error("Erro no GamificationService::removePoints para usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Obtém o nível atual do usuário
     */
    public function getUserLevel(User $user): string
    {
        return $this->levelService->getUserLevel($user);
    }

    /**
     * Calcula o nível baseado nos pontos
     */
    public function calculateLevel(int $totalPoints): string
    {
        return $this->levelService->calculateLevel($totalPoints);
    }

    /**
     * Verifica se o usuário subiu de nível
     */
    public function checkLevelUp(User $user, int $newPoints): bool
    {
        return $this->levelService->checkLevelUp($user, $newPoints);
    }

    /**
     * Atualiza o streak do usuário
     */
    public function updateStreak(User $user): void
    {
        $this->streakService->updateStreak($user);
    }

    /**
     * Obtém estatísticas completas do usuário
     * 
     * @param User $user Usuário para obter estatísticas
     * @return array Estatísticas completas com a seguinte estrutura:
     *               [
     *                 'total_points' => int,
     *                 'current_level' => string,
     *                 'points_to_next_level' => int,
     *                 'level_progress' => int,
     *                 'current_streak' => int,
     *                 'longest_streak' => int,
     *                 'is_active_today' => bool,
     *                 'days_to_next_reward' => int,
     *                 'recent_points_history' => array
     *               ]
     */
    public function getUserStats(User $user): array
    {
        $totalPoints = $this->pointsService->getTotalPoints($user);
        $levelStats = [
            'current_level' => $this->levelService->getUserLevel($user),
            'points_to_next_level' => $this->levelService->calculatePointsToNextLevel($totalPoints),
            'level_progress' => $this->levelService->calculateLevelProgress($totalPoints)
        ];
        
        $streakStats = $this->streakService->getStreakStats($user);
        
        return array_merge([
            'total_points' => $totalPoints,
            'recent_points_history' => $this->pointsService->getPointsHistory($user, 5)
        ], $levelStats, $streakStats);
    }

    /**
     * Obtém ranking global de usuários
     * 
     * @param int $limit Limite de usuários no ranking
     * @return array Array de UserGamification com relacionamento user carregado
     */
    public function getGlobalRanking(int $limit = 10): array
    {
        return UserGamification::with('user:id,name,email')
            ->orderBy('total_points', 'desc')
            ->limit($limit)
            ->get()
            ->all();
    }

    /**
     * Obtém ou cria gamification para o usuário
     */
    public function getOrCreateGamification(User $user): UserGamification
    {
        return $user->gamification ?? UserGamification::create([
            'user_id' => $user->id,
            'total_points' => 0,
            'current_level' => 'Rookie',
            'streak_days' => 0,
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Obtém estatísticas de gamificação do sistema
     * 
     * @return array Estatísticas do sistema com a seguinte estrutura:
     *               [
     *                 'total_users' => int,
     *                 'total_points' => int,
     *                 'average_points' => float,
     *                 'level_distribution' => array // Distribuição por nível
     *               ]
     */
    public function getSystemStats(): array
    {
        $totalUsers = UserGamification::count();
        $totalPoints = UserGamification::sum('total_points');
        $averagePoints = $totalUsers > 0 ? round($totalPoints / $totalUsers, 2) : 0;
        
        $levelDistribution = UserGamification::selectRaw('current_level, COUNT(*) as count')
            ->groupBy('current_level')
            ->pluck('count', 'current_level')
            ->toArray();
        
        return [
            'total_users' => $totalUsers,
            'total_points' => $totalPoints,
            'average_points' => $averagePoints,
            'level_distribution' => $levelDistribution
        ];
    }
}