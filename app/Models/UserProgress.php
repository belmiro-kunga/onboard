<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\FormattedTimestamps;
use App\Models\Traits\HasCommonScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $module_id
 * ... outros campos relevantes ...
 */
class UserProgress extends Model
{
    use HasFactory, FormattedTimestamps, HasCommonScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'module_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
        'time_spent',
        'last_accessed_at',
        'current_section',
        'notes',
        'bookmarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'progress_percentage' => 'integer',
        'time_spent' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'notes' => 'array',
        'bookmarks' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: usuário deste progresso.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: módulo deste progresso.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Métodos de negócio
     */

    /**
     * Marcar módulo como iniciado
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'last_accessed_at' => now(),
        ]);
    }

    /**
     * Marcar módulo como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => now(),
            'last_accessed_at' => now(),
        ]);
        // Disparar evento de módulo completado
        event(new \App\Events\ModuleCompleted($this->user, $this->module));
    }

    /**
     * Atualizar progresso
     */
    public function updateProgress(int $percentage, ?string $currentSection = null): void
    {
        $data = [
            'progress_percentage' => min(100, max(0, $percentage)),
            'last_accessed_at' => now(),
        ];

        if ($currentSection) {
            $data['current_section'] = $currentSection;
        }

        // Se chegou a 100%, marcar como completado
        if ($percentage >= 100) {
            $data['status'] = 'completed';
            $data['completed_at'] = now();
        } elseif ($this->status === 'not_started') {
            $data['status'] = 'in_progress';
            $data['started_at'] = now();
        }

        $this->update($data);

        // Disparar evento se completou
        if ($percentage >= 100 && $this->status !== 'completed') {
            event(new \App\Events\ModuleCompleted($this->user, $this->module));
        }
    }

    /**
     * Adicionar tempo gasto
     */
    public function addTimeSpent(int $minutes): void
    {
        $this->increment('time_spent', $minutes);
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Adicionar nota
     */
    public function addNote(string $note, ?string $section = null): void
    {
        $notes = $this->notes ?? [];
        $notes[] = [
            'content' => $note,
            'section' => $section,
            'created_at' => now()->toISOString(),
        ];
        
        $this->update(['notes' => $notes]);
    }

    /**
     * Adicionar bookmark
     */
    public function addBookmark(string $section, string $title, ?string $description = null): void
    {
        $bookmarks = $this->bookmarks ?? [];
        $bookmarks[] = [
            'section' => $section,
            'title' => $title,
            'description' => $description,
            'created_at' => now()->toISOString(),
        ];
        
        $this->update(['bookmarks' => $bookmarks]);
    }

    /**
     * Scopes
     */

    /**
     * Scope para progresso concluído.
     */
    // Scope Completed disponível via trait HasCommonScopes


    /**
     * Scope para progresso em andamento.
     */
    // Scope InProgress disponível via trait HasCommonScopes


    /**
     * Scope para progresso não iniciado
     */
    public function scopeNotStarted(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'not_started');
    }

    /**
     * Accessors
     */

    /**
     * Obter status formatado
     */
    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            'not_started' => 'Não iniciado',
            'in_progress' => 'Em progresso',
            'completed' => 'Concluído',
            'paused' => 'Pausado',
            default => 'Desconhecido'
        };
    }

    /**
     * Obter tempo gasto formatado
     */
    public function getFormattedTimeSpentAttribute(): string
    {
        if ($this->time_spent < 60) {
            return $this->time_spent . ' min';
        }
        
        $hours = floor($this->time_spent / 60);
        $minutes = $this->time_spent % 60;
        
        if ($minutes === 0) {
            return $hours . 'h';
        }
        
        return $hours . 'h ' . $minutes . 'min';
    }

    /**
     * Verificar se está atrasado
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'completed' || !$this->started_at) {
            return false;
        }

        // Considerar atrasado se passou mais de 7 dias desde o início
        return $this->started_at->diffInDays(now()) > 7;
    }

    /**
     * Verifica se o progresso está completo.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed' && $this->progress_percentage === 100;
    }
}