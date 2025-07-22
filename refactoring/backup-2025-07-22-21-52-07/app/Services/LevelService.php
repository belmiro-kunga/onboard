<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserGamification;
use App\Contracts\EventDispatcherInterface;
use App\Contracts\NotificationServiceInterface;
use App\Events\UserLevelUp;

class LevelService
{
    private const LEVEL_THRESHOLDS = [
        'Rookie' => 0,
        'Beginner' => 100,
        'Explorer' => 500,
        'Intermediate' => 1000,
        'Advanced' => 2000,
        'Expert' => 5000,
        'Master' => 10000
    ];

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private NotificationServiceInterface $notificationService
    ) {}

    /**
     * Calcula o nível baseado nos pontos
     */
    public function calculateLevel(int $totalPoints): string
    {
        $level = 'Rookie';
        
        foreach (self::LEVEL_THRESHOLDS as $levelName => $threshold) {
            if ($totalPoints >= $threshold) {
                $level = $levelName;
            } else {
                break;
            }
        }
        
        return $level;
    }

    /**
     * Verifica se o usuário subiu de nível
     */
    public function checkLevelUp(User $user, int $newPoints): bool
    {
        $gamification = $user->gamification;
        if (!$gamification) {
            return false;
        }

        $currentLevel = $gamification->current_level;
        $newLevel = $this->calculateLevel($newPoints);
        
        return $this->getLevelValue($newLevel) > $this->getLevelValue($currentLevel);
    }

    /**
     * Atualiza o nível do usuário
     */
    public function updateUserLevel(User $user, int $totalPoints): bool
    {
        try {
            $gamification = $user->gamification;
            if (!$gamification) {
                return false;
            }

            $oldLevel = $gamification->current_level;
            $newLevel = $this->calculateLevel($totalPoints);
            
            if ($this->getLevelValue($newLevel) > $this->getLevelValue($oldLevel)) {
                $gamification->current_level = $newLevel;
                $gamification->save();
                
                // Disparar evento
                $this->eventDispatcher->dispatch(new UserLevelUp($user, $oldLevel, $newLevel));
                
                // Enviar notificação
                $this->notificationService->sendLevelUpNotification($user, $oldLevel, $newLevel);
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error("Erro ao atualizar nível do usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Obtém o nível atual do usuário
     */
    public function getUserLevel(User $user): string
    {
        $gamification = $user->gamification;
        return $gamification ? $gamification->current_level : 'Rookie';
    }

    /**
     * Calcula pontos necessários para o próximo nível
     */
    public function calculatePointsToNextLevel(int $currentPoints): int
    {
        $currentLevel = $this->calculateLevel($currentPoints);
        $nextLevel = $this->getNextLevel($currentLevel);
        
        if ($nextLevel === $currentLevel) {
            return 0; // Já está no nível máximo
        }
        
        $nextLevelThreshold = self::LEVEL_THRESHOLDS[$nextLevel];
        return max(0, $nextLevelThreshold - $currentPoints);
    }

    /**
     * Calcula progresso do nível atual (0-100%)
     */
    public function calculateLevelProgress(int $currentPoints): int
    {
        $currentLevel = $this->calculateLevel($currentPoints);
        $currentThreshold = self::LEVEL_THRESHOLDS[$currentLevel];
        $nextLevel = $this->getNextLevel($currentLevel);
        $nextThreshold = self::LEVEL_THRESHOLDS[$nextLevel];
        
        if ($nextThreshold === $currentThreshold) {
            return 100; // Nível máximo
        }
        
        $pointsInCurrentLevel = $currentPoints - $currentThreshold;
        $pointsNeededForLevel = $nextThreshold - $currentThreshold;
        
        return (int) min(100, max(0, round(($pointsInCurrentLevel / $pointsNeededForLevel) * 100)));
    }

    /**
     * Obtém o próximo nível
     */
    private function getNextLevel(string $currentLevel): string
    {
        $levels = array_keys(self::LEVEL_THRESHOLDS);
        $currentIndex = array_search($currentLevel, $levels);
        
        if ($currentIndex === false || $currentIndex === count($levels) - 1) {
            return $currentLevel; // Já está no nível máximo
        }
        
        return $levels[$currentIndex + 1];
    }

    /**
     * Obtém valor numérico do nível
     */
    private function getLevelValue(string $level): int
    {
        return self::LEVEL_THRESHOLDS[$level] ?? 0;
    }

    /**
     * Obtém todos os níveis disponíveis
     */
    public function getAvailableLevels(): array
    {
        return self::LEVEL_THRESHOLDS;
    }
} 