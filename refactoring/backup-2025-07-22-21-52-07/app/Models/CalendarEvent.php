<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'module_id',
        'title',
        'description',
        'type',
        'start_time',
        'end_time',
        'all_day',
        'location',
        'meeting_url',
        'attendees',
        'reminders',
        'status',
        'external_event_id',
        'calendar_provider',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
        'attendees' => 'array',
        'reminders' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com módulo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Scopes
     */

    /**
     * Scope para eventos futuros
     */
    public function scopeUpcoming(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('start_time', '>', now());
    }

    /**
     * Scope para eventos passados
     */
    public function scopePast(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('end_time', '<', now());
    }

    /**
     * Scope para eventos de hoje
     */
    public function scopeToday(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereDate('start_time', today());
    }

    /**
     * Scope para eventos desta semana
     */
    public function scopeThisWeek(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereBetween('start_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope por tipo
     */
    public function scopeByType(\Illuminate\Database\Eloquent\Builder $query, string $type): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope por status
     */
    public function scopeByStatus(\Illuminate\Database\Eloquent\Builder $query, string $status): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope entre datas
     */
    public function scopeBetweenDates(\Illuminate\Database\Eloquent\Builder $query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_time', [$startDate, $endDate])
              ->orWhereBetween('end_time', [$startDate, $endDate])
              ->orWhere(function($subQ) use ($startDate, $endDate) {
                  $subQ->where('start_time', '<=', $startDate)
                       ->where('end_time', '>=', $endDate);
              });
        });
    }

    /**
     * Accessors
     */

    /**
     * Obter duração do evento em minutos
     */
    public function getDurationAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Verificar se o evento é hoje
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->start_time->isToday();
    }

    /**
     * Verificar se o evento está acontecendo agora
     */
    public function getIsNowAttribute(): bool
    {
        $now = now();
        return $now->between($this->start_time, $this->end_time);
    }

    /**
     * Verificar se o evento já passou
     */
    public function getIsPastAttribute(): bool
    {
        return $this->end_time->isPast();
    }

    /**
     * Obter status formatado
     */
    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'Agendado',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
            'rescheduled' => 'Reagendado',
            default => 'Desconhecido'
        };
    }

    /**
     * Obter tipo formatado
     */
    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'module_deadline' => 'Prazo de Módulo',
            'quiz_reminder' => 'Lembrete de Quiz',
            'meeting' => 'Reunião',
            'training' => 'Treinamento',
            'review' => 'Revisão',
            'custom' => 'Personalizado',
            default => 'Evento'
        };
    }

    /**
     * Obter cor do evento baseada no tipo
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'module_deadline' => '#ef4444', // red
            'quiz_reminder' => '#f59e0b', // amber
            'meeting' => '#3b82f6', // blue
            'training' => '#10b981', // emerald
            'review' => '#8b5cf6', // violet
            'custom' => '#6b7280', // gray
            default => '#6b7280'
        };
    }

    /**
     * Obter ícone do evento baseado no tipo
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'module_deadline' => 'clock',
            'quiz_reminder' => 'help-circle',
            'meeting' => 'users',
            'training' => 'book-open',
            'review' => 'eye',
            'custom' => 'calendar',
            default => 'calendar'
        };
    }

    /**
     * Obter data formatada para exibição
     */
    public function getFormattedDateAttribute(): string
    {
        if ($this->all_day) {
            return $this->start_time->format('d/m/Y');
        }
        
        if ($this->start_time->isSameDay($this->end_time)) {
            return $this->start_time->format('d/m/Y H:i') . ' - ' . $this->end_time->format('H:i');
        }
        
        return $this->start_time->format('d/m/Y H:i') . ' - ' . $this->end_time->format('d/m/Y H:i');
    }

    /**
     * Obter tempo restante até o evento
     */
    public function getTimeUntilAttribute(): string
    {
        if ($this->is_past) {
            return 'Evento passou';
        }
        
        if ($this->is_now) {
            return 'Acontecendo agora';
        }
        
        return $this->start_time->diffForHumans();
    }

    /**
     * Verificar se precisa de lembrete
     */
    public function needsReminder(): bool
    {
        if (!$this->reminders || $this->is_past) {
            return false;
        }
        
        foreach ($this->reminders as $reminder) {
            $reminderTime = $this->start_time->subMinutes($reminder['minutes']);
            if (now()->gte($reminderTime) && !($reminder['sent'] ?? false)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Marcar lembrete como enviado
     */
    public function markReminderSent(int $reminderIndex): void
    {
        $reminders = $this->reminders;
        if (isset($reminders[$reminderIndex])) {
            $reminders[$reminderIndex]['sent'] = true;
            $this->update(['reminders' => $reminders]);
        }
    }

    /**
     * Verificar conflito com outro evento
     */
    public function hasConflictWith(CalendarEvent $otherEvent): bool
    {
        return $this->start_time->lt($otherEvent->end_time) && 
               $this->end_time->gt($otherEvent->start_time);
    }

    /**
     * Gerar link do calendário (Google Calendar)
     */
    public function getGoogleCalendarLinkAttribute(): string
    {
        $startTime = $this->start_time->utc()->format('Ymd\THis\Z');
        $endTime = $this->end_time->utc()->format('Ymd\THis\Z');
        
        $params = [
            'action' => 'TEMPLATE',
            'text' => $this->title,
            'dates' => $startTime . '/' . $endTime,
            'details' => $this->description,
            'location' => $this->location,
        ];
        
        return 'https://calendar.google.com/calendar/render?' . http_build_query($params);
    }

    /**
     * Gerar link do Outlook
     */
    public function getOutlookLinkAttribute(): string
    {
        $startTime = $this->start_time->utc()->format('Y-m-d\TH:i:s\Z');
        $endTime = $this->end_time->utc()->format('Y-m-d\TH:i:s\Z');
        
        $params = [
            'subject' => $this->title,
            'startdt' => $startTime,
            'enddt' => $endTime,
            'body' => $this->description,
            'location' => $this->location,
        ];
        
        return 'https://outlook.live.com/calendar/0/deeplink/compose?' . http_build_query($params);
    }

    /**
     * Converter para formato de calendário (FullCalendar)
     */
    public function toCalendarEvent(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start_time->toISOString(),
            'end' => $this->end_time->toISOString(),
            'allDay' => $this->all_day,
            'color' => $this->color,
            'description' => $this->description,
            'location' => $this->location,
            'url' => $this->meeting_url,
            'extendedProps' => [
                'type' => $this->type,
                'status' => $this->status,
                'module_id' => $this->module_id,
                'attendees' => $this->attendees,
                'reminders' => $this->reminders,
            ]
        ];
    }
}