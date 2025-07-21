<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface ActivityTrackingServiceInterface
{
    /**
     * Rastreia atividade do usuário
     */
    public function trackActivity(User $user, string $action, array $data = []): bool;

    /**
     * Obtém estatísticas de atividade do usuário
     */
    public function getUserActivityStats(User $user): array;

    /**
     * Obtém atividades recentes do usuário
     */
    public function getRecentActivities(User $user, int $limit = 10): array;

    /**
     * Limpa atividades antigas
     */
    public function clearOldActivities(int $daysOld = 30): int;

    /**
     * Exporta atividades do usuário
     */
    public function exportUserActivities(User $user): string;
} 