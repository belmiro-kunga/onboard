<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $quiz_id
 * @property string $question
 * @property string $question_type
 * @property array $options
 * @property array $correct_answer
 * @property string $explanation
 * @property string $explanation_correct
 * @property array $explanation_incorrect
 * @property string $feedback_type
 * @property int $points
 * @property int $order_index
 * @property bool $is_active
 */
class QuizQuestion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'question',
        'question_type',
        'options',
        'correct_answer',
        'explanation',
        'explanation_correct',
        'explanation_incorrect',
        'feedback_type',
        'points',
        'order_index',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'explanation_incorrect' => 'json',
        'points' => 'integer',
        'order_index' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento: quiz ao qual pertence a questão.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relacionamento: respostas das tentativas para esta questão.
     */
    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    /**
     * Relacionamento com respostas da questão
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Métodos de negócio
     */

    /**
     * Verificar se uma resposta está correta
     */
    public function checkAnswer($userAnswer): bool
    {
        if (is_null($userAnswer)) {
            return false;
        }

        $correctAnswer = $this->correct_answer;

        // Para múltipla escolha simples
        if ($this->question_type === 'multiple_choice') {
            return in_array($userAnswer, $correctAnswer);
        }

        // Para verdadeiro/falso
        if ($this->question_type === 'true_false') {
            return $userAnswer === $correctAnswer[0];
        }

        // Para arrastar e soltar
        if ($this->question_type === 'drag_drop') {
            return $userAnswer === $correctAnswer;
        }

        // Para preencher lacunas
        if ($this->question_type === 'fill_blank') {
            $userAnswer = strtolower(trim($userAnswer));
            foreach ($correctAnswer as $correct) {
                if (strtolower(trim($correct)) === $userAnswer) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

    /**
     * Obter resposta correta formatada
     */
    public function getCorrectAnswer()
    {
        return $this->correct_answer;
    }

    /**
     * Obter opções formatadas
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * Obter estatísticas da questão
     */
    public function getStatistics(): array
    {
        $totalAnswers = $this->attemptAnswers()->count();
        $correctAnswers = $this->attemptAnswers()->where('is_correct', true)->count();
        
        return [
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'accuracy_rate' => $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 2) : 0,
            'difficulty_score' => $totalAnswers > 0 ? round((1 - ($correctAnswers / $totalAnswers)) * 100, 2) : 0,
        ];
    }
    
    /**
     * Obter explicação para resposta correta
     */
    public function getCorrectExplanation(): ?string
    {
        return $this->explanation_correct ?? $this->explanation ?? null;
    }
    
    /**
     * Obter explicação para uma resposta incorreta específica
     */
    public function getIncorrectExplanation(string $answer): ?string
    {
        if (empty($this->explanation_incorrect)) {
            return $this->explanation ?? null;
        }
        
        $explanations = $this->explanation_incorrect;
        
        // Se temos uma explicação específica para esta resposta
        if (is_array($explanations) && isset($explanations[$answer])) {
            return $explanations[$answer];
        }
        
        // Se temos uma explicação genérica para respostas incorretas
        if (is_array($explanations) && isset($explanations['default'])) {
            return $explanations['default'];
        }
        
        return $this->explanation ?? null;
    }
    
    /**
     * Obter explicação baseada na resposta do usuário
     */
    public function getExplanationForAnswer(string $userAnswer): ?string
    {
        if ($this->checkAnswer($userAnswer)) {
            return $this->getCorrectExplanation();
        } else {
            return $this->getIncorrectExplanation($userAnswer);
        }
    }
    
    /**
     * Verificar se o feedback deve ser imediato
     */
    public function hasImmediateFeedback(): bool
    {
        return $this->feedback_type === 'immediate';
    }

    /**
     * Scopes
     */

    /**
     * Scope para questões ativas
     */
    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para questões por tipo
     */
    public function scopeByType(\Illuminate\Database\Eloquent\Builder $query, string $type): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('question_type', $type);
    }

    /**
     * Accessors
     */

    /**
     * Obter tipo de questão formatado
     */
    public function getFormattedTypeAttribute(): string
    {
        return match($this->question_type) {
            'multiple_choice' => 'Múltipla Escolha',
            'true_false' => 'Verdadeiro/Falso',
            'drag_drop' => 'Arrastar e Soltar',
            'fill_blank' => 'Preencher Lacunas',
            default => 'Não definido'
        };
    }
}