<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserGamification;
use App\Contracts\EventDispatcherInterface;
use App\Events\StreakUpdated;
use Carbon\Carbon;

class StreakService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * Atualiza o streak do usuário
     */
    public function updateStreak(User $user): bool
    {
        try {
            $gamification = $user->gamification;
            if (!$gamification) {
                return false;
            }

            $lastActivity = $gamification->last_activity_at;
            $today = Carbon::today();
            
            // Se já foi atualizado hoje, não faz nada
            if ($lastActivity && $lastActivity->isToday()) {
                return true;
            }
            
            $oldStreak = $gamification->streak_days;
            
            if ($lastActivity && $lastActivity->isYesterday()) {
                // Continua o streak
                $gamification->streak_days++;
            } else {
                // Quebra o streak ou inicia novo
                $gamification->streak_days = 1;
            }
            
            $gamification->last_activity_at = $today;
            $gamification->save();
            
            // Disparar evento se streak mudou
            if ($oldStreak !== $gamification->streak_days) {
                $this->eventDispatcher->dispatch(new StreakUpdated($user, $oldStreak, $gamification->streak_days));
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Erro ao atualizar streak do usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Obtém o streak atual do usuário
     */
    public function getCurrentStreak(User $user): int
    {
        $gamification = $user->gamification;
        return $gamification ? $gamification->streak_days : 0;
    }

    /**
     * Obtém o maior streak do usuário
     */
    public function getLongestStreak(User $user): int
    {
        $gamification = $user->gamification;
        return $gamification ? $gamification->longest_streak ?? 0 : 0;
    }

    /**
     * Verifica se o streak está ativo hoje
     */
    public function isStreakActiveToday(User $user): bool
    {
        $gamification = $user->gamification;
        if (!$gamification || !$gamification->last_activity_at) {
            return false;
        }
        
        return $gamification->last_activity_at->isToday();
    }

    /**
     * Calcula recompensa de streak
     */
    public function calculateStreakReward(int $streakDays): int
    {
        // Recompensa baseada no streak
        if ($streakDays >= 7) {
            return 50; // Recompensa semanal
        } elseif ($streakDays >= 30) {
            return 200; // Recompensa mensal
        } elseif ($streakDays >= 100) {
            return 500; // Recompensa centenária
        }
        
        return 0;
    }

    /**
     * Obtém estatísticas de streak
     */
    public function getStreakStats(User $user): array
    {
        $gamification = $user->gamification;
        if (!$gamification) {
            return [
                'current_streak' => 0,
                'longest_streak' => 0,
                'is_active_today' => false,
                'days_to_next_reward' => 0
            ];
        }
        
        $currentStreak = $gamification->streak_days;
        $longestStreak = $gamification->longest_streak ?? 0;
        $isActiveToday = $this->isStreakActiveToday($user);
        
        // Calcular dias para próxima recompensa
        $daysToNextReward = 0;
        if ($currentStreak < 7) {
            $daysToNextReward = 7 - $currentStreak;
        } elseif ($currentStreak < 30) {
            $daysToNextReward = 30 - $currentStreak;
        } elseif ($currentStreak < 100) {
            $daysToNextReward = 100 - $currentStreak;
        }
        
        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
            'is_active_today' => $isActiveToday,
            'days_to_next_reward' => $daysToNextReward
        ];
    }

    /**
     * Reseta o streak do usuário
     */
    public function resetStreak(User $user): bool
    {
        try {
            $gamification = $user->gamification;
            if (!$gamification) {
                return false;
            }
            
            $oldStreak = $gamification->streak_days;
            $gamification->streak_days = 0;
            $gamification->save();
            
            // Disparar evento
            $this->eventDispatcher->dispatch(new StreakUpdated($user, $oldStreak, 0));
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Erro ao resetar streak do usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }
} 