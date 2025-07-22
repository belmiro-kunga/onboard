<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property int $attempt_number
 * @property Carbon $started_at
 * @property Carbon $completed_at
 * @property int $score
 * @property bool $passed
 * @property int $time_spent
 * @property array $answers
 * @property array $results
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class QuizAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_number',
        'started_at',
        'completed_at',
        'score',
        'passed',
        'time_spent',
        'answers',
        'results',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attempt_number' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'integer',
        'passed' => 'boolean',
        'time_spent' => 'integer',
        'answers' => 'array',
        'results' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: quiz ao qual pertence a tentativa.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relacionamento: usuário que fez a tentativa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: respostas da tentativa.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    /**
     * Relacionamento com certificado
     */
    public function certificate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Certificate::class, 'quiz_attempt_id');
    }

    /**
     * Métodos de negócio
     */

    /**
     * Iniciar tentativa
     */
    public function start(): void
    {
        $this->update([
            'started_at' => now(),
        ]);
    }

    /**
     * Completar tentativa
     */
    public function complete(array $answers): void
    {
        $startTime = $this->started_at;
        $endTime = now();
        $timeSpent = $startTime ? $startTime->diffInSeconds($endTime) : 0;

        // Calcular pontuação
        $results = $this->quiz->calculateScore($answers);

        $this->update([
            'completed_at' => $endTime,
            'time_spent' => $timeSpent,
            'answers' => $answers,
            'results' => $results['results'],
            'score' => $results['score'],
            'passed' => $results['passed'],
        ]);

        // Salvar respostas detalhadas
        $this->saveDetailedAnswers($answers, $results['results']);

        // Atualizar gamificação se passou
        if ($results['passed']) {
            $this->updateGamification();
        }
    }

    /**
     * Salvar respostas detalhadas
     */
    private function saveDetailedAnswers(array $answers, array $results): void
    {
        foreach ($results as $questionId => $result) {
            QuizAttemptAnswer::create([
                'quiz_attempt_id' => $this->id,
                'quiz_question_id' => $questionId,
                'user_answer' => $answers[$questionId] ?? null,
                'is_correct' => $result['is_correct'],
                'points_earned' => $result['is_correct'] ? 1 : 0,
            ]);
        }
    }

    /**
     * Atualizar gamificação
     */
    private function updateGamification(): void
    {
        $gamificationService = app(\App\Services\GamificationService::class);
        
        // Adicionar pontos
        $gamificationService->addPoints($this->user, $this->quiz->points_reward, 'quiz_completion');
        
        // Verificar conquistas
        $gamificationService->checkAchievements($this->user);
    }

    /**
     * Verificar se a tentativa expirou
     */
    public function isExpired(): bool
    {
        if (!$this->started_at || !$this->quiz->time_limit) {
            return false;
        }

        $timeLimit = $this->quiz->time_limit * 60; // converter para segundos
        return $this->started_at->addSeconds($timeLimit)->isPast();
    }

    /**
     * Obter tempo restante em segundos
     */
    public function getRemainingTime(): int
    {
        if (!$this->started_at || !$this->quiz->time_limit) {
            return 0;
        }

        $timeLimit = $this->quiz->time_limit * 60; // converter para segundos
        $elapsed = $this->started_at->diffInSeconds(now());
        
        return max(0, $timeLimit - $elapsed);
    }

    /**
     * Obter resposta para uma questão específica
     */
    public function getAnswerForQuestion(int $questionId)
    {
        return $this->answers[$questionId] ?? null;
    }

    /**
     * Obter resultado para uma questão específica
     */
    public function getResultForQuestion(int $questionId): ?array
    {
        return $this->results[$questionId] ?? null;
    }

    /**
     * Verifica se a tentativa foi aprovada.
     */
    public function isPassed(): bool
    {
        return $this->passed === true;
    }

    /**
     * Accessors
     */

    /**
     * Obter tempo gasto formatado
     */
    public function getFormattedTimeSpentAttribute(): string
    {
        if (!$this->time_spent) {
            return '0min';
        }

        $minutes = floor($this->time_spent / 60);
        $seconds = $this->time_spent % 60;

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
        if (!$this->started_at) {
            return 'Não iniciado';
        }

        if (!$this->completed_at) {
            return $this->isExpired() ? 'Expirado' : 'Em andamento';
        }

        return $this->passed ? 'Aprovado' : 'Reprovado';
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Aprovado' => 'green',
            'Reprovado' => 'red',
            'Em andamento' => 'blue',
            'Expirado' => 'orange',
            default => 'gray'
        };
    }
}