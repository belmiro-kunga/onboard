<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Notification());
    }

    /**
     * Buscar notificações do usuário
     */
    public function getByUser(int $userId, int $limit = 5)
    {
        return $this->model->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->limit($limit)
                          ->get();
    }

    /**
     * Buscar notificação específica do usuário
     */
    public function findByUserAndId(int $userId, int $notificationId): ?Notification
    {
        return $this->model->where('user_id', $userId)
                          ->where('id', $notificationId)
                          ->first();
    }

    /**
     * Marcar como lida
     */
    public function markAsRead(int $userId, int $notificationId): bool
    {
        return $this->model->where('user_id', $userId)
                          ->where('id', $notificationId)
                          ->update(['read_at' => now()]);
    }

    /**
     * Contar não lidas do usuário
     */
    public function countUnreadByUser(int $userId): int
    {
        return $this->model->where('user_id', $userId)
                          ->whereNull('read_at')
                          ->count();
    }
}
