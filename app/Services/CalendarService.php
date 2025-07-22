<?php

namespace App\Services;


use App\Repositories\ProgressRepository;use App\Models\User;
use App\Models\Module;
use App\Models\CalendarEvent;
use App\Models\UserProgress;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CalendarService
{
    public function __construct(
        private NotificationService $notificationService,
        private ActivityTrackingService $activityTrackingService
    ) {}

    /**
     * Criar eventos automáticos para um usuário
     * 
     * @param User $user Usuário para criar eventos
     * @return array Array de eventos criados (CalendarEvent[])
     */
    public function createAutomaticEvents(User $user): array
    {
        $createdEvents = [];
        
        try {
            // Criar lembretes de módulos
            $moduleEvents = $this->createModuleDeadlines($user);
            $createdEvents = array_merge($createdEvents, $moduleEvents);
            
            // Criar lembretes de quizzes
            $quizEvents = $this->createQuizReminders($user);
            $createdEvents = array_merge($createdEvents, $quizEvents);
            
            // Criar eventos de revisão
            $reviewEvents = $this->createReviewEvents($user);
            $createdEvents = array_merge($createdEvents, $reviewEvents);
            
            // Rastrear atividade
            $this->activityTrackingService->trackActivity($user, 'calendar_events_created', [
                'events_count' => count($createdEvents),
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao criar eventos automáticos para usuário {$user->id}: {$e->getMessage()}");
        }
        
        return $createdEvents;
    }

    /**
     * Criar prazos de módulos
     */
    private function createModuleDeadlines(User $user): array
    {
        $events = [];
        $modules = Module::where('is_active', true)->orderBy('order_index')->get();
        
        $currentDate = now()->addDays(1); // Começar amanhã
        
        foreach ($modules as $index => $module) {
            if ($this->isModuleCompleted($user, $module)) {
                continue; // Pular módulos já completados
            }
            
            $deadline = $this->calculateModuleDeadline($currentDate, $module);
            $event = $this->createModuleDeadlineEvent($user, $module, $deadline);
            
            if ($event) {
                $events[] = $event;
                $currentDate = $deadline->addDays(1); // Próximo módulo no dia seguinte
            }
        }
        
        return $events;
    }

    /**
     * Criar lembretes de quizzes
     */
    private function createQuizReminders(User $user): array
    {
        $events = [];
        $quizzes = Quiz::where('is_active', true)->get();
        
        foreach ($quizzes as $quiz) {
            if ($this->isQuizCompleted($user, $quiz)) {
                continue; // Pular quizzes já aprovados
            }
            
            $reminderDate = now()->addDays(3);
            $event = $this->createQuizReminderEvent($user, $quiz, $reminderDate);
            
            if ($event) {
                $events[] = $event;
            }
        }
        
        return $events;
    }

    /**
     * Criar eventos de revisão
     */
    private function createReviewEvents(User $user): array
    {
        $events = [];
        
        // Criar revisão semanal
        $weeklyReview = $this->createWeeklyReviewEvent($user);
        
        if ($weeklyReview) {
            $events[] = $weeklyReview;
        }
        
        return $events;
    }

    /**
     * Agendar reunião com gestor
     */
    public function scheduleManagerMeeting(User $user, array $data): CalendarEvent
    {
        try {
            $event = CalendarEvent::create([
                'user_id' => $user->id,
                'title' => $data['title'] ?? 'Reunião com Gestor',
                'description' => $data['description'] ?? 'Reunião de acompanhamento do programa de onboarding.',
                'type' => 'meeting',
                'start_time' => Carbon::parse($data['start_time']),
                'end_time' => Carbon::parse($data['end_time']),
                'location' => $data['location'] ?? null,
                'meeting_url' => $data['meeting_url'] ?? null,
                'attendees' => $data['attendees'] ?? [],
                'reminders' => $data['reminders'] ?? [
                    ['minutes' => 15, 'sent' => false], // 15 minutos antes
                    ['minutes' => 1440, 'sent' => false], // 1 dia antes
                ],
                'metadata' => [
                    'meeting_type' => 'manager_followup',
                    'requested_by' => $user->id,
                ]
            ]);
            
            // Enviar notificação
            $this->sendEventNotification($user, $event, 'created');
            
            // Rastrear atividade
            $this->activityTrackingService->trackActivity($user, 'manager_meeting_scheduled', [
                'event_id' => $event->id,
                'start_time' => $event->start_time,
            ]);
            
            return $event;
        } catch (\Exception $e) {
            Log::error("Erro ao agendar reunião com gestor para usuário {$user->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Agendar sessão de treinamento
     */
    public function scheduleTrainingSession(array $data): CalendarEvent
    {
        try {
            $event = CalendarEvent::create([
                'user_id' => $data['user_id'],
                'module_id' => $data['module_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'] ?? 'Sessão de treinamento personalizada.',
                'type' => 'training',
                'start_time' => Carbon::parse($data['start_time']),
                'end_time' => Carbon::parse($data['end_time']),
                'location' => $data['location'] ?? null,
                'meeting_url' => $data['meeting_url'] ?? null,
                'attendees' => $data['attendees'] ?? [],
                'reminders' => $data['reminders'] ?? [
                    ['minutes' => 30, 'sent' => false], // 30 minutos antes
                ],
                'metadata' => [
                    'training_type' => $data['training_type'] ?? 'general',
                    'instructor' => $data['instructor'] ?? null,
                ]
            ]);
            
            $user = User::find($data['user_id']);
            if ($user) {
                $this->sendEventNotification($user, $event, 'created');
                
                $this->activityTrackingService->trackActivity($user, 'training_scheduled', [
                    'event_id' => $event->id,
                    'module_id' => $data['module_id'] ?? null,
                ]);
            }
            
            return $event;
        } catch (\Exception $e) {
            Log::error("Erro ao agendar sessão de treinamento: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Obter eventos do usuário em um período
     */
    public function getUserEvents(User $user, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEvent::where('user_id', $user->id)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Obter eventos de hoje
     */
    public function getTodayEvents(User $user): Collection
    {
        $today = now()->startOfDay();
        $tomorrow = $today->copy()->addDay();
        
        return $this->getUserEvents($user, $today, $tomorrow);
    }

    /**
     * Obter próximos eventos
     */
    public function getUpcomingEvents(User $user, int $limit = 5): Collection
    {
        return CalendarEvent::where('user_id', $user->id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Verificar conflitos de horário
     */
    public function checkConflicts(User $user, Carbon $startTime, Carbon $endTime, ?int $excludeEventId = null): Collection
    {
        $query = CalendarEvent::where('user_id', $user->id)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                         ->where('end_time', '>=', $endTime);
                  });
            });
        
        if ($excludeEventId) {
            $query->where('id', '!=', $excludeEventId);
        }
        
        return $query->get();
    }

    /**
     * Reagendar evento
     */
    public function rescheduleEvent(CalendarEvent $event, Carbon $newStartTime, Carbon $newEndTime): CalendarEvent
    {
        try {
            $oldStartTime = $event->start_time;
            
            $event->update([
                'start_time' => $newStartTime,
                'end_time' => $newEndTime,
            ]);
            
            // Enviar notificação
            $user = $event->user;
            $this->sendEventNotification($user, $event, 'rescheduled');
            
            // Rastrear atividade
            $this->activityTrackingService->trackActivity($user, 'event_rescheduled', [
                'event_id' => $event->id,
                'old_start_time' => $oldStartTime,
                'new_start_time' => $newStartTime,
            ]);
            
            return $event;
        } catch (\Exception $e) {
            Log::error("Erro ao reagendar evento {$event->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Cancelar evento
     */
    public function cancelEvent(CalendarEvent $event, string $reason = null): CalendarEvent
    {
        try {
            $event->update([
                'status' => 'cancelled',
                'metadata' => array_merge($event->metadata ?? [], [
                    'cancellation_reason' => $reason,
                    'cancelled_at' => now(),
                ])
            ]);
            
            // Enviar notificação
            $user = $event->user;
            $this->sendEventNotification($user, $event, 'cancelled');
            
            // Rastrear atividade
            $this->activityTrackingService->trackActivity($user, 'event_cancelled', [
                'event_id' => $event->id,
                'reason' => $reason,
            ]);
            
            return $event;
        } catch (\Exception $e) {
            Log::error("Erro ao cancelar evento {$event->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Completar evento
     */
    public function completeEvent(CalendarEvent $event): CalendarEvent
    {
        try {
            $event->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            // Rastrear atividade
            $user = $event->user;
            $this->activityTrackingService->trackActivity($user, 'event_completed', [
                'event_id' => $event->id,
                'event_type' => $event->type,
            ]);
            
            return $event;
        } catch (\Exception $e) {
            Log::error("Erro ao completar evento {$event->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Processar lembretes
     */
    public function processReminders(): array
    {
        $processed = [];
        
        $eventsWithReminders = CalendarEvent::where('status', 'active')
            ->where('start_time', '>', now())
            ->get();
        
        foreach ($eventsWithReminders as $event) {
            $reminders = $event->reminders ?? [];
            $updatedReminders = [];
            
            foreach ($reminders as $reminder) {
                if ($reminder['sent'] ?? false) {
                    $updatedReminders[] = $reminder;
                    continue;
                }
                
                $reminderTime = $event->start_time->subMinutes($reminder['minutes']);
                
                if (now()->gte($reminderTime)) {
                    $this->sendReminderNotification($event->user, $event, $reminder);
                    $reminder['sent'] = true;
                    $processed[] = [
                        'event_id' => $event->id,
                        'reminder_minutes' => $reminder['minutes'],
                    ];
                }
                
                $updatedReminders[] = $reminder;
            }
            
            $event->update(['reminders' => $updatedReminders]);
        }
        
        return $processed;
    }

    /**
     * Gerar relatório de eventos
     * 
     * @param User $user Usuário para gerar relatório
     * @param Carbon $startDate Data de início
     * @param Carbon $endDate Data de fim
     * @return array Relatório de eventos com a seguinte estrutura:
     *               [
     *                 'total_events' => int,
     *                 'completed_events' => int,
     *                 'cancelled_events' => int,
     *                 'upcoming_events' => int,
     *                 'events_by_type' => Collection
     *               ]
     */
    public function generateEventsReport(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $events = $this->getUserEvents($user, $startDate, $endDate);
        
        $stats = [
            'total_events' => $events->count(),
            'completed_events' => $events->where('status', 'completed')->count(),
            'cancelled_events' => $events->where('status', 'cancelled')->count(),
            'upcoming_events' => $events->where('start_time', '>', now())->count(),
            'events_by_type' => $events->groupBy('type')->map->count(),
        ];
        
        return $stats;
    }

    /**
     * Sincronizar com calendário externo
     * 
     * @param User $user Usuário para sincronizar
     * @param string $provider Provedor do calendário (google, outlook, etc.)
     * @return array Array de eventos sincronizados
     */
    public function syncWithExternalCalendar(User $user, string $provider): array
    {
        // Implementação básica - pode ser expandida
        $syncedEvents = [];
        
        // Aqui você implementaria a lógica de sincronização
        // com Google Calendar, Outlook, etc.
        
        $this->activityTrackingService->trackActivity($user, 'calendar_synced', [
            'provider' => $provider,
            'synced_count' => count($syncedEvents),
        ]);
        
        return $syncedEvents;
    }

    /**
     * Obter slots disponíveis
     * 
     * @param User $user Usuário para verificar slots
     * @param Carbon $date Data para verificar
     * @param int $durationMinutes Duração do slot em minutos
     * @return array Array de slots disponíveis com a seguinte estrutura:
     *               [
     *                 [
     *                   'start_time' => Carbon,
     *                   'end_time' => Carbon
     *                 ]
     *               ]
     */
    public function getAvailableSlots(User $user, Carbon $date, int $durationMinutes = 60): array
    {
        $slots = [];
        $startHour = 9; // 9:00 AM
        $endHour = 18;  // 6:00 PM
        
        $currentTime = $date->copy()->setTime($startHour, 0);
        $endTime = $date->copy()->setTime($endHour, 0);
        
        while ($currentTime->lt($endTime)) {
            $slotEnd = $currentTime->copy()->addMinutes($durationMinutes);
            
            if ($slotEnd->lte($endTime)) {
                $conflicts = $this->checkConflicts($user, $currentTime, $slotEnd);
                
                if ($conflicts->isEmpty()) {
                    $slots[] = [
                        'start_time' => $currentTime->copy(),
                        'end_time' => $slotEnd,
                    ];
                }
            }
            
            $currentTime->addMinutes(30); // Intervalos de 30 minutos
        }
        
        return $slots;
    }

    /**
     * Verificar se módulo está completo
     */
    private function isModuleCompleted(User $user, Module $module): bool
    {
        $progress = $this->progressRepository->getUserModuleProgress(user->id, module->id);
        
        return $progress && $progress->status === 'completed';
    }

    /**
     * Verificar se quiz está completo
     */
    private function isQuizCompleted(User $user, Quiz $quiz): bool
    {
        return $user->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->where('passed', true)
            ->exists();
    }

    /**
     * Calcular prazo do módulo
     */
    private function calculateModuleDeadline(Carbon $currentDate, Module $module): Carbon
    {
        $daysToComplete = max(3, ceil($module->estimated_duration / 60)); // Mínimo 3 dias
        return $currentDate->copy()->addDays($daysToComplete);
    }

    /**
     * Criar evento de prazo de módulo
     */
    private function createModuleDeadlineEvent(User $user, Module $module, Carbon $deadline): ?CalendarEvent
    {
        try {
            return CalendarEvent::create([
                'user_id' => $user->id,
                'module_id' => $module->id,
                'title' => "Prazo: {$module->title}",
                'description' => "Prazo para conclusão do módulo: {$module->title}. Duração estimada: {$module->formatted_duration}.",
                'type' => 'module_deadline',
                'start_time' => $deadline->setTime(17, 0), // 17:00
                'end_time' => $deadline->setTime(18, 0), // 18:00
                'reminders' => [
                    ['minutes' => 1440, 'sent' => false], // 1 dia antes
                    ['minutes' => 120, 'sent' => false],  // 2 horas antes
                ],
                'metadata' => [
                    'module_category' => $module->category,
                    'estimated_duration' => $module->estimated_duration,
                    'points_reward' => $module->points_reward,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao criar evento de prazo para módulo {$module->id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Criar evento de lembrete de quiz
     */
    private function createQuizReminderEvent(User $user, Quiz $quiz, Carbon $reminderDate): ?CalendarEvent
    {
        try {
            return CalendarEvent::create([
                'user_id' => $user->id,
                'title' => "Lembrete: Quiz {$quiz->title}",
                'description' => "Lembrete para realizar o quiz: {$quiz->title}. {$quiz->description}",
                'type' => 'quiz_reminder',
                'start_time' => $reminderDate->setTime(10, 0), // 10:00
                'end_time' => $reminderDate->setTime(11, 0), // 11:00
                'reminders' => [
                    ['minutes' => 60, 'sent' => false], // 1 hora antes
                ],
                'metadata' => [
                    'quiz_id' => $quiz->id,
                    'quiz_category' => $quiz->category,
                    'time_limit' => $quiz->time_limit,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao criar evento de lembrete para quiz {$quiz->id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Criar evento de revisão semanal
     */
    private function createWeeklyReviewEvent(User $user): ?CalendarEvent
    {
        try {
            return CalendarEvent::create([
                'user_id' => $user->id,
                'title' => 'Revisão Semanal de Progresso',
                'description' => 'Momento para revisar seu progresso no programa de onboarding e planejar os próximos passos.',
                'type' => 'review',
                'start_time' => now()->next(Carbon::FRIDAY)->setTime(16, 0), // Próxima sexta às 16:00
                'end_time' => now()->next(Carbon::FRIDAY)->setTime(17, 0), // Próxima sexta às 17:00
                'reminders' => [
                    ['minutes' => 30, 'sent' => false], // 30 minutos antes
                ],
                'metadata' => [
                    'review_type' => 'weekly',
                    'auto_generated' => true,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao criar evento de revisão semanal para usuário {$user->id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Enviar notificação de evento
     */
    private function sendEventNotification(User $user, CalendarEvent $event, string $action): void
    {
        $messages = [
            'created' => "Novo evento agendado: {$event->title}",
            'rescheduled' => "Evento reagendado: {$event->title}",
            'cancelled' => "Evento cancelado: {$event->title}",
        ];
        
        $message = $messages[$action] ?? "Evento atualizado: {$event->title}";
        
        $this->notificationService->sendToUser(
            $user,
            'Calendário Atualizado 📅',
            $message,
            'info',
            'calendar',
            'blue',
            route('calendar.show', $event),
            ['event_id' => $event->id, 'action' => $action]
        );
    }

    /**
     * Enviar notificação de lembrete
     */
    private function sendReminderNotification(User $user, CalendarEvent $event, array $reminder): void
    {
        $minutes = $reminder['minutes'];
        $timeText = $minutes >= 1440 ? floor($minutes / 1440) . ' dia(s)' : floor($minutes / 60) . ' hora(s)';
        
        $this->notificationService->sendToUser(
            $user,
            'Lembrete de Evento ⏰',
            "Em {$timeText}: {$event->title}",
            'warning',
            'clock',
            'yellow',
            route('calendar.show', $event),
            ['event_id' => $event->id, 'reminder_minutes' => $minutes],
            true // Enviar e-mail
        );
    }
}