<?php

namespace App\Contracts;

use App\Models\User;
use App\Models\Notification;

interface NotificationServiceInterface
{
    /**
     * Enviar notificação para um usuário
     */
    public function sendToUser(
        User $user,
        string $title,
        string $message,
        string $type = 'info',
        string $icon = 'bell',
        string $color = 'blue',
        ?string $actionUrl = null,
        ?array $metadata = null
    ): Notification;

    /**
     * Enviar notificação para múltiplos usuários
     */
    public function sendToMultipleUsers(
        array $userIds,
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $color = null,
        ?string $link = null,
        array $data = [],
        bool $sendEmail = false
    ): array;

    /**
     * Enviar notificação para todos os usuários
     */
    public function sendToAllUsers(
        string $title,
        string $message,
        string $type = 'info',
        array $filters = []
    ): int;

    /**
     * Enviar notificação para usuários com papel específico
     */
    public function sendToRole(
        string $role,
        string $title,
        string $message,
        string $type = 'info',
        string $icon = 'bell',
        string $color = 'blue',
        ?string $actionUrl = null,
        ?array $metadata = null
    );

    /**
     * Enviar notificação para usuários de um departamento específico
     */
    public function sendToDepartment(
        string $department,
        string $title,
        string $message,
        string $type = 'info',
        string $icon = 'bell',
        string $color = 'blue',
        ?string $actionUrl = null,
        ?array $metadata = null
    );
    
    /**
     * Enviar notificação de level up
     */
    public function sendLevelUpNotification(User $user, string $oldLevel, string $newLevel): void;
    
    /**
     * Enviar notificação de pontos
     */
    public function sendPointsNotification(User $user, int $points, string $reason): Notification;
    
    /**
     * Enviar notificação de conquista
     */
    public function sendAchievementNotification(User $user, $achievement): void;
    
    /**
     * Enviar notificação de certificado
     */
    public function sendCertificateNotification(User $user, $certificate): void;
    
    /**
     * Obter estatísticas de notificações do usuário
     */
    public function getNotificationStats(User $user): array;
    
    /**
     * Marca notificação como lida
     */
    public function markAsRead(int $notificationId): bool;
    
    /**
     * Deleta notificação
     */
    public function deleteNotification(int $notificationId): bool;
    
    /**
     * Obtém contagem de não lidas
     */
    public function getUnreadCount(User $user): int;
    
    /**
     * Marcar todas as notificações do usuário como lidas
     */
    public function markAllAsRead(User $user): int;
}