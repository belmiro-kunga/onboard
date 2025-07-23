<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para progresso do usuário em uma aula
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property int $progress_percentage
 * @property int|null $watch_time_seconds
 * @property int|null $current_time_seconds
 * @property bool $is_completed
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon|null $last_watched_at
 * @property array|null $metadata
 */
class LessonProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'progress_percentage',
        'watch_time_seconds',
        'current_time_seconds',
        'is_completed',
        'completed_at',
        'last_watched_at',
        'metadata',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
        'watch_time_seconds' => 'integer',
        'current_time_seconds' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'last_watched_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com aula
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Marcar como completada
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);

        // Disparar evento de conclusão
        event(new \App\Events\LessonCompleted($this->user, $this->lesson));
    }

    /**
     * Atualizar progresso
     */
    public function updateProgress(int $percentage, int $watchTime = null, int $currentTime = null): void
    {
        $data = [
            'progress_percentage' => min(100, max(0, $percentage)),
            'last_watched_at' => now(),
        ];

        if ($watchTime !== null) {
            $data['watch_time_seconds'] = $watchTime;
        }

        if ($currentTime !== null) {
            $data['current_time_seconds'] = $currentTime;
        }

        // Auto-completar se chegou a 90% ou mais
        if ($percentage >= 90 && !$this->is_completed) {
            $data['is_completed'] = true;
            $data['completed_at'] = now();
        }

        $this->update($data);

        // Disparar evento se foi completada agora
        if (($data['is_completed'] ?? false) && !$this->wasRecentlyCreated) {
            event(new \App\Events\LessonCompleted($this->user, $this->lesson));
        }
    }

    /**
     * Obter tempo formatado assistido
     */
    public function getFormattedWatchTimeAttribute(): string
    {
        if (!$this->watch_time_seconds) {
            return '0min';
        }

        $minutes = floor($this->watch_time_seconds / 60);
        $seconds = $this->watch_time_seconds % 60;

        if ($minutes === 0) {
            return $seconds . 's';
        }

        if ($seconds === 0) {
            return $minutes . 'min';
        }

        return $minutes . 'min ' . $seconds . 's';
    }

    /**
     * Obter status formatado
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_completed) {
            return 'Concluída';
        }

        if ($this->progress_percentage >= 50) {
            return 'Em andamento';
        }

        if ($this->progress_percentage > 0) {
            return 'Iniciada';
        }

        return 'Não iniciada';
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->is_completed) {
            return 'green';
        }

        if ($this->progress_percentage >= 50) {
            return 'blue';
        }

        if ($this->progress_percentage > 0) {
            return 'yellow';
        }

        return 'gray';
    }

    /**
     * Verificar se foi assistida recentemente
     */
    public function isRecentlyWatched(): bool
    {
        if (!$this->last_watched_at) {
            return false;
        }

        return $this->last_watched_at->isAfter(now()->subHours(24));
    }

    /**
     * Obter pontos de engajamento
     */
    public function getEngagementPoints(): int
    {
        $points = 0;

        // Pontos por progresso
        $points += floor($this->progress_percentage / 10) * 5;

        // Bonus por completar
        if ($this->is_completed) {
            $points += 20;
        }

        // Bonus por assistir recentemente
        if ($this->isRecentlyWatched()) {
            $points += 5;
        }

        return $points;
    }

    /**
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('is_completed', false)
                    ->where('progress_percentage', '>', 0);
    }

    public function scopeNotStarted($query)
    {
        return $query->where('progress_percentage', 0);
    }

    public function scopeRecentlyWatched($query)
    {
        return $query->where('last_watched_at', '>=', now()->subHours(24));
    }
}