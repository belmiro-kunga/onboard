<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para tentativas de quiz de aula
 * 
 * @property int $id
 * @property int $lesson_quiz_id
 * @property int $user_id
 * @property array $questions_data
 * @property array|null $answers
 * @property float|null $score
 * @property int|null $time_limit_minutes
 * @property int|null $time_spent_seconds
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon|null $completed_at
 */
class LessonQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_quiz_id',
        'user_id',
        'questions_data',
        'answers',
        'score',
        'time_limit_minutes',
        'time_spent_seconds',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'questions_data' => 'array',
        'answers' => 'array',
        'score' => 'float',
        'time_limit_minutes' => 'integer',
        'time_spent_seconds' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relacionamento com quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(LessonQuiz::class, 'lesson_quiz_id');
    }

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verificar se foi completada
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Verificar se está em andamento
     */
    public function isInProgress(): bool
    {
        return is_null($this->completed_at);
    }

    /**
     * Verificar se expirou (se tem limite de tempo)
     */
    public function isExpired(): bool
    {
        if (!$this->time_limit_minutes || $this->isCompleted()) {
            return false;
        }

        return $this->started_at->addMinutes($this->time_limit_minutes)->isPast();
    }

    /**
     * Obter tempo restante em segundos
     */
    public function getRemainingTimeSeconds(): int
    {
        if (!$this->time_limit_minutes || $this->isCompleted()) {
            return 0;
        }

        $endTime = $this->started_at->addMinutes($this->time_limit_minutes);
        $remaining = $endTime->diffInSeconds(now(), false);

        return max(0, $remaining);
    }

    /**
     * Completar tentativa
     */
    public function complete(array $answers): void
    {
        $score = $this->quiz->calculateScore($answers);
        $timeSpent = now()->diffInSeconds($this->started_at);

        $this->update([
            'answers' => $answers,
            'score' => $score,
            'time_spent_seconds' => $timeSpent,
            'completed_at' => now(),
        ]);

        // Disparar evento
        event(new \App\Events\LessonQuizCompleted($this->user, $this->quiz, $this));
    }

    /**
     * Obter resultado formatado
     */
    public function getResultAttribute(): string
    {
        if (!$this->isCompleted()) {
            return 'Em andamento';
        }

        if (!$this->quiz->passing_score) {
            return 'Concluído';
        }

        return $this->score >= $this->quiz->passing_score ? 'Aprovado' : 'Reprovado';
    }

    /**
     * Obter cor do resultado
     */
    public function getResultColorAttribute(): string
    {
        if (!$this->isCompleted()) {
            return 'blue';
        }

        if (!$this->quiz->passing_score) {
            return 'green';
        }

        return $this->score >= $this->quiz->passing_score ? 'green' : 'red';
    }

    /**
     * Obter tempo gasto formatado
     */
    public function getFormattedTimeSpentAttribute(): string
    {
        if (!$this->time_spent_seconds) {
            return 'N/A';
        }

        $minutes = floor($this->time_spent_seconds / 60);
        $seconds = $this->time_spent_seconds % 60;

        if ($minutes === 0) {
            return $seconds . 's';
        }

        return $minutes . 'min ' . $seconds . 's';
    }

    /**
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeInProgress($query)
    {
        return $query->whereNull('completed_at');
    }

    public function scopePassed($query)
    {
        return $query->whereNotNull('completed_at')
                    ->whereRaw('score >= (SELECT passing_score FROM lesson_quizzes WHERE id = lesson_quiz_attempts.lesson_quiz_id)');
    }

    public function scopeFailed($query)
    {
        return $query->whereNotNull('completed_at')
                    ->whereRaw('score < (SELECT passing_score FROM lesson_quizzes WHERE id = lesson_quiz_attempts.lesson_quiz_id)');
    }
}