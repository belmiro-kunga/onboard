<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserGamification;
use App\Models\PointsHistory;
use App\Contracts\EventDispatcherInterface;
use App\Events\PointsAwarded;
use App\Events\PointsRemoved;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointsService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * Adiciona pontos ao usuário
     */
    public function addPoints(User $user, int $points, ?string $reason = null): bool
    {
        if ($points <= 0) {
            Log::warning("Tentativa de adicionar pontos negativos ou zero para usuário {$user->id}");
            return false;
        }

        try {
            DB::transaction(function () use ($user, $points, $reason) {
                $gamification = $this->getOrCreateGamification($user);
                $oldPoints = $gamification->total_points;
                
                $gamification->total_points += $points;
                $gamification->save();
                
                // Registrar histórico
                $this->recordPointsHistory($user, $points, $reason, $oldPoints, $gamification->total_points);
                
                // Disparar evento
                $this->eventDispatcher->dispatch(new PointsAwarded($user, $points, $reason));
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Erro ao adicionar pontos para usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Remove pontos do usuário
     */
    public function removePoints(User $user, int $points, ?string $reason = null): bool
    {
        if ($points <= 0) {
            Log::warning("Tentativa de remover pontos negativos ou zero para usuário {$user->id}");
            return false;
        }

        try {
            DB::transaction(function () use ($user, $points, $reason) {
                $gamification = $this->getOrCreateGamification($user);
                $oldPoints = $gamification->total_points;
                
                $gamification->total_points = max(0, $gamification->total_points - $points);
                $gamification->save();
                
                // Registrar histórico
                $this->recordPointsHistory($user, -$points, $reason, $oldPoints, $gamification->total_points);
                
                // Disparar evento
                $this->eventDispatcher->dispatch(new PointsRemoved($user, $points, $reason));
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Erro ao remover pontos para usuário {$user->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Obtém pontos totais do usuário
     */
    public function getTotalPoints(User $user): int
    {
        $gamification = $this->getOrCreateGamification($user);
        return $gamification->total_points;
    }

    /**
     * Obtém histórico de pontos do usuário
     */
    public function getPointsHistory(User $user, int $limit = 10): array
    {
        return PointsHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Obtém ou cria gamification para o usuário
     */
    private function getOrCreateGamification(User $user): UserGamification
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
     * Registra histórico de pontos
     */
    private function recordPointsHistory(
        User $user, 
        int $points, 
        ?string $reason, 
        int $oldTotal, 
        int $newTotal
    ): void {
        PointsHistory::create([
            'user_id' => $user->id,
            'points' => $points,
            'reason' => $reason,
            'old_total' => $oldTotal,
            'new_total' => $newTotal,
        ]);
    }
} 