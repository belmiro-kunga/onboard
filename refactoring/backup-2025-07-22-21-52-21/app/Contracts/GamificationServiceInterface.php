<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;
use App\Models\UserGamification;

interface GamificationServiceInterface
{
    /**
     * Adiciona pontos ao usuário
     */
    public function addPoints(User $user, int $points, ?string $reason = null): bool;

    /**
     * Remove pontos do usuário
     */
    public function removePoints(User $user, int $points, ?string $reason = null): bool;

    /**
     * Obtém o nível atual do usuário
     */
    public function getUserLevel(User $user): string;

    /**
     * Calcula o nível baseado nos pontos
     */
    public function calculateLevel(int $totalPoints): string;

    /**
     * Verifica se o usuário subiu de nível
     */
    public function checkLevelUp(User $user, int $newPoints): bool;

    /**
     * Atualiza o streak do usuário
     */
    public function updateStreak(User $user): void;

    /**
     * Obtém estatísticas do usuário
     */
    public function getUserStats(User $user): array;

    /**
     * Obtém ranking global
     */
    public function getGlobalRanking(int $limit = 10): array;

    /**
     * Obtém ou cria gamification para o usuário
     */
    public function getOrCreateGamification(User $user): UserGamification;
} 