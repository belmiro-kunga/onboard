<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\Module;
use App\Models\UserProgress;
use App\Events\NotificationReceived;
use App\Mail\NotificationEmail;
use App\Mail\ModuleReminderEmail;
use App\Mail\AchievementEmail;
use App\Contracts\NotificationServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Carbon\Carbon;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private Dispatcher $eventDispatcher,
        private Mailer $mailer
    ) {}

    /**
     * Envia uma notificação para um usuário.
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
    ): Notification {
        $metadata = $metadata ?? [];
        
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'action_url' => $actionUrl,
            'metadata' => $metadata,
            'read_at' => null,
        ]);

        // Enviar email se solicitado
        if (isset($metadata['sendEmail']) && $metadata['sendEmail']) {
            $this->sendEmailNotification($user, $notification);
        }

        // Disparar evento
        event(new NotificationReceived($user, $notification));

        return $notification;
    }

    /**
     * Enviar uma notificação para múltiplos usuários.
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
    ): array {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                
                if ($user) {
                    $notifications[] = $this->sendToUser(
                        $user,
                        $title,
                        $message,
                        $type,
                        $icon,
                        $color,
                        $link,
                        $data,
                        $sendEmail
                    );
                }
            } catch (\Exception $e) {
                Log::error("Erro ao enviar notificação para o usuário ID {$userId}: {$e->getMessage()}");
            }
        }
        
        return $notifications;
    }

    /**
     * Enviar uma notificação para todos os usuários.
     */
    public function sendToAllUsers(
        string $title,
        string $message,
        string $type = 'info',
        array $filters = []
    ): int {
        $count = 0;
        $icon = $this->getDefaultIcon($type);
        $color = $this->getDefaultColor($type);

        // Buscar usuários em lotes para evitar problemas de memória
        User::chunk(100, function ($users) use ($title, $message, $type, $icon, $color, &$count, $filters) {
            foreach ($users as $user) {
                // Aplicar filtros se fornecidos
                if (!empty($filters)) {
                    if (isset($filters['department']) && $user->department !== $filters['department']) {
                        continue;
                    }
                    if (isset($filters['role']) && $user->role !== $filters['role']) {
                        continue;
                    }
                    if (isset($filters['active']) && $user->is_active !== $filters['active']) {
                        continue;
                    }
                }

                try {
                    $this->sendToUser(
                        $user,
                        $title,
                        $message,
                        $type,
                        $icon,
                        $color,
                        null,
                        [],
                        false
                    );
                    $count++;
                } catch (\Exception $e) {
                    Log::error("Erro ao enviar notificação para usuário {$user->id}: {$e->getMessage()}");
                }
            }
        });

        return $count;
    }

    /**
     * Enviar lembretes automáticos para módulos pendentes.
     */
    public function sendModuleReminders(): int
    {
        $count = 0;
        
        // Buscar usuários com módulos pendentes há mais de 3 dias
        $usersWithPendingModules = $this->getUsersWithPendingModules();

        foreach ($usersWithPendingModules as $user) {
            try {
                $pendingModules = $user->progress->pluck('module');
                
                if ($pendingModules->isNotEmpty()) {
                    $this->sendModuleReminder($user, $pendingModules);
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error("Erro ao enviar lembrete para o usuário ID {$user->id}: {$e->getMessage()}");
            }
        }
        
        return $count;
    }

    /**
     * Enviar lembrete específico para módulos pendentes.
     */
    public function sendModuleReminder(User $user, $modules): void
    {
        $moduleNames = $modules->pluck('title')->implode(', ');
        
        // Notificação in-app
        $this->sendToUser(
            $user,
            'Módulos Pendentes 📚',
            "Você tem módulos pendentes há alguns dias: {$moduleNames}. Continue sua jornada de aprendizado!",
            'warning',
            'clock',
            'yellow',
            route('modules.index'),
            ['modules' => $modules->pluck('id')->toArray()],
            true // Enviar e-mail
        );
    }

    /**
     * Enviar notificação de conquista.
     */
    public function sendAchievementNotification(User $user, $achievement): void
    {
        $this->sendToUser(
            $user,
            'Nova Conquista Desbloqueada! 🏆',
            "Parabéns! Você conquistou: {$achievement->name}",
            'achievement',
            'badge-check',
            'purple',
            route('gamification.achievements'),
            ['achievement_id' => $achievement->id],
            true // Enviar e-mail
        );
    }

    /**
     * Enviar notificação de level up.
     */
    public function sendLevelUpNotification(User $user, string $oldLevel, string $newLevel): void
    {
        $this->sendToUser(
            $user,
            'Parabéns! Você subiu de nível! 🚀',
            "Você alcançou o nível {$newLevel}! Continue assim!",
            'level_up',
            'arrow-up',
            'blue',
            route('gamification.dashboard'),
            ['old_level' => $oldLevel, 'new_level' => $newLevel],
            true // Enviar e-mail
        );
    }

    /**
     * Envia notificação de pontos
     */
    public function sendPointsNotification(User $user, int $points, string $reason): Notification
    {
        return $this->sendToUser(
            $user,
            "Pontos Ganhos!",
            "Você ganhou {$points} pontos por: {$reason}",
            'success',
            'star',
            'green',
            route('gamification.dashboard'),
            ['points' => $points, 'reason' => $reason],
            false
        );
    }

    /**
     * Envia notificação de certificado
     */
    public function sendCertificateNotification(User $user, $certificate): void
    {
        $this->sendToUser(
            $user,
            "Novo Certificado!",
            "Parabéns! Você recebeu um novo certificado: {$certificate->title}",
            'success',
            'award',
            'green',
            route('certificates.show', $certificate),
            ['certificate_id' => $certificate->id],
            true
        );
    }

    /**
     * Enviar mensagem do gerente.
     */
    public function sendManagerMessage(User $user, string $title, string $message, ?string $link = null): void
    {
        $this->sendToUser(
            $user,
            $title,
            $message,
            'manager',
            'user-tie',
            'indigo',
            $link,
            ['from_manager' => true],
            true // Enviar e-mail
        );
    }
    
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
    ) {
        $count = 0;
        $icon = $icon ?: $this->getDefaultIcon($type);
        $color = $color ?: $this->getDefaultColor($type);
        $metadata = $metadata ?: [];
        
        // Buscar usuários com o papel especificado em lotes
        User::where('role', $role)->chunk(100, function ($users) use ($title, $message, $type, $icon, $color, $actionUrl, $metadata, &$count) {
            foreach ($users as $user) {
                try {
                    $this->sendToUser(
                        $user,
                        $title,
                        $message,
                        $type,
                        $icon,
                        $color,
                        $actionUrl,
                        $metadata,
                        false
                    );
                    $count++;
                } catch (\Exception $e) {
                    Log::error("Erro ao enviar notificação para usuário {$user->id}: {$e->getMessage()}");
                }
            }
        });
        
        return $count;
    }
    
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
    ) {
        $count = 0;
        $icon = $icon ?: $this->getDefaultIcon($type);
        $color = $color ?: $this->getDefaultColor($type);
        $metadata = $metadata ?: [];
        
        // Buscar usuários do departamento especificado em lotes
        User::where('department', $department)->chunk(100, function ($users) use ($title, $message, $type, $icon, $color, $actionUrl, $metadata, &$count) {
            foreach ($users as $user) {
                try {
                    $this->sendToUser(
                        $user,
                        $title,
                        $message,
                        $type,
                        $icon,
                        $color,
                        $actionUrl,
                        $metadata,
                        false
                    );
                    $count++;
                } catch (\Exception $e) {
                    Log::error("Erro ao enviar notificação para usuário {$user->id}: {$e->getMessage()}");
                }
            }
        });
        
        return $count;
    }

    /**
     * Obter estatísticas de notificações do usuário.
     * 
     * @param User $user Usuário para obter estatísticas
     * @return array Estatísticas de notificações com a seguinte estrutura:
     *               [
     *                 'total' => int, // Total de notificações
     *                 'unread' => int, // Notificações não lidas
     *                 'by_type' => array, // Notificações agrupadas por tipo
     *                 'recent' => Collection // Notificações recentes
     *               ]
     */
    public function getNotificationStats(User $user): array
    {
        $totalNotifications = Notification::where('user_id', $user->id)->count();
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('read_at', null)
            ->count();
        
        $notificationsByType = Notification::where('user_id', $user->id)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
        
        $recentNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return [
            'total' => $totalNotifications,
            'unread' => $unreadNotifications,
            'by_type' => $notificationsByType,
            'recent' => $recentNotifications,
        ];
    }

    /**
     * Marca notificação como lida
     */
    public function markAsRead(int $notificationId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            if ($notification) {
                $notification->update(['read_at' => now()]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Erro ao marcar notificação {$notificationId} como lida: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Deleta notificação
     */
    public function deleteNotification(int $notificationId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            if ($notification) {
                $notification->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Erro ao deletar notificação {$notificationId}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Obtém contagem de não lidas
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Marcar todas as notificações do usuário como lidas.
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('read_at', null)
            ->update(['read_at' => now()]);
    }

    /**
     * Criar notificação no banco de dados.
     */
    private function createNotification(
        User $user,
        string $title,
        string $message,
        string $type,
        string $icon,
        string $color,
        ?string $link,
        array $data
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Enviar notificação por e-mail.
     */
    protected function sendEmailNotification(User $user, Notification $notification): void
    {
        try {
            $this->mailer->to($user->email)
                ->send(new NotificationEmail($user, $notification));
        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail de notificação para {$user->email}: {$e->getMessage()}");
        }
    }

    /**
     * Enviar e-mail de lembrete de módulo.
     */
    protected function sendModuleReminderEmail(User $user, \Illuminate\Database\Eloquent\Collection $modules): void
    {
        try {
            $this->mailer->to($user->email)
                ->send(new ModuleReminderEmail($user, $modules));
        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail de lembrete para {$user->email}: {$e->getMessage()}");
        }
    }

    /**
     * Enviar e-mail de conquista.
     */
    protected function sendAchievementEmail(User $user, \App\Models\Achievement $achievement): void
    {
        try {
            $this->mailer->to($user->email)
                ->send(new AchievementEmail($user, $achievement));
        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail de conquista para {$user->email}: {$e->getMessage()}");
        }
    }

    /**
     * Obter usuários com módulos pendentes.
     */
    private function getUsersWithPendingModules(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('is_active', true)
            ->whereHas('progress', function ($query) {
                $query->where('status', 'in_progress')
                      ->where('updated_at', '<=', Carbon::now()->subDays(3));
            })
            ->with(['progress' => function ($query) {
                $query->where('status', 'in_progress')
                      ->where('updated_at', '<=', Carbon::now()->subDays(3))
                      ->with('module');
            }])
            ->get();
    }

    /**
     * Obter ícone padrão baseado no tipo.
     */
    protected function getDefaultIcon(string $type): string
    {
        return match ($type) {
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'information-circle',
            'achievement' => 'badge-check',
            'level_up' => 'arrow-up',
            'points' => 'star',
            'manager' => 'user-tie',
            default => 'bell',
        };
    }

    /**
     * Obter cor padrão baseada no tipo.
     */
    protected function getDefaultColor(string $type): string
    {
        return match ($type) {
            'success' => 'green',
            'error' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
            'achievement' => 'purple',
            'level_up' => 'blue',
            'points' => 'green',
            'manager' => 'indigo',
            default => 'gray',
        };
    }
}