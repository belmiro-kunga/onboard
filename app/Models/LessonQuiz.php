<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model para quiz/atividade de uma aula
 * 
 * @property int $id
 * @property int $lesson_id
 * @property string $title
 * @property string|null $description
 * @property string $type (quiz|reflection|activity)
 * @property array $questions
 * @property int|null $time_limit_minutes
 * @property int|null $max_attempts
 * @property float|null $passing_score
 * @property bool $is_required
 * @property bool $show_results_immediately
 * @property array|null $settings
 */
class LessonQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'type',
        'questions',
        'time_limit_minutes',
        'max_attempts',
        'passing_score',
        'is_required',
        'show_results_immediately',
        'settings',
    ];

    protected $casts = [
        'questions' => 'array',
        'time_limit_minutes' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'float',
        'is_required' => 'boolean',
        'show_results_immediately' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Relacionamento com aula
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relacionamento com tentativas dos usuários
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(LessonQuizAttempt::class);
    }

    /**
     * Verificar se é quiz
     */
    public function isQuiz(): bool
    {
        return $this->type === 'quiz';
    }

    /**
     * Verificar se é reflexão
     */
    public function isReflection(): bool
    {
        return $this->type === 'reflection';
    }

    /**
     * Verificar se é atividade
     */
    public function isActivity(): bool
    {
        return $this->type === 'activity';
    }

    /**
     * Obter tentativa do usuário
     */
    public function getUserAttempt(User $user): ?LessonQuizAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Obter todas as tentativas do usuário
     */
    public function getUserAttempts(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Verificar se usuário pode fazer nova tentativa
     */
    public function canUserAttempt(User $user): bool
    {
        if (!$this->max_attempts) {
            return true;
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $user->id)
            ->count();

        return $attemptCount < $this->max_attempts;
    }

    /**
     * Verificar se usuário passou no quiz
     */
    public function hasUserPassed(User $user): bool
    {
        if (!$this->passing_score) {
            return true; // Se não tem nota mínima, considera aprovado
        }

        $bestAttempt = $this->attempts()
            ->where('user_id', $user->id)
            ->orderBy('score', 'desc')
            ->first();

        return $bestAttempt && $bestAttempt->score >= $this->passing_score;
    }

    /**
     * Criar nova tentativa para usuário
     */
    public function createAttemptFor(User $user): LessonQuizAttempt
    {
        return $this->attempts()->create([
            'user_id' => $user->id,
            'started_at' => now(),
            'questions_data' => $this->questions,
            'time_limit_minutes' => $this->time_limit_minutes,
        ]);
    }

    /**
     * Obter estatísticas do quiz
     */
    public function getStats(): array
    {
        $totalAttempts = $this->attempts()->count();
        $uniqueUsers = $this->attempts()->distinct('user_id')->count();
        $completedAttempts = $this->attempts()->whereNotNull('completed_at')->count();
        $averageScore = $this->attempts()->whereNotNull('score')->avg('score') ?? 0;
        $passedUsers = $this->passing_score 
            ? $this->attempts()->where('score', '>=', $this->passing_score)->distinct('user_id')->count()
            : $uniqueUsers;

        return [
            'total_attempts' => $totalAttempts,
            'unique_users' => $uniqueUsers,
            'completed_attempts' => $completedAttempts,
            'completion_rate' => $totalAttempts > 0 ? round(($completedAttempts / $totalAttempts) * 100, 1) : 0,
            'average_score' => round($averageScore, 1),
            'pass_rate' => $uniqueUsers > 0 ? round(($passedUsers / $uniqueUsers) * 100, 1) : 0,
        ];
    }

    /**
     * Adicionar pergunta ao quiz
     */
    public function addQuestion(array $questionData): void
    {
        $questions = $this->questions ?? [];
        $questions[] = array_merge($questionData, [
            'id' => uniqid(),
            'created_at' => now()->toISOString(),
        ]);

        $this->update(['questions' => $questions]);
    }

    /**
     * Atualizar pergunta
     */
    public function updateQuestion(string $questionId, array $questionData): void
    {
        $questions = $this->questions ?? [];
        
        foreach ($questions as &$question) {
            if ($question['id'] === $questionId) {
                $question = array_merge($question, $questionData, [
                    'updated_at' => now()->toISOString(),
                ]);
                break;
            }
        }

        $this->update(['questions' => $questions]);
    }

    /**
     * Remover pergunta
     */
    public function removeQuestion(string $questionId): void
    {
        $questions = $this->questions ?? [];
        $questions = array_filter($questions, fn($q) => $q['id'] !== $questionId);

        $this->update(['questions' => array_values($questions)]);
    }

    /**
     * Obter perguntas ordenadas
     */
    public function getOrderedQuestions(): array
    {
        $questions = $this->questions ?? [];
        
        // Ordenar por order_index se existir
        usort($questions, function($a, $b) {
            $orderA = $a['order_index'] ?? 0;
            $orderB = $b['order_index'] ?? 0;
            return $orderA <=> $orderB;
        });

        return $questions;
    }

    /**
     * Obter pergunta por ID
     */
    public function getQuestion(string $questionId): ?array
    {
        $questions = $this->questions ?? [];
        
        foreach ($questions as $question) {
            if ($question['id'] === $questionId) {
                return $question;
            }
        }

        return null;
    }

    /**
     * Calcular pontuação de uma tentativa
     */
    public function calculateScore(array $answers): float
    {
        $questions = $this->getOrderedQuestions();
        $totalQuestions = count($questions);
        
        if ($totalQuestions === 0) {
            return 0;
        }

        $correctAnswers = 0;

        foreach ($questions as $question) {
            $questionId = $question['id'];
            $userAnswer = $answers[$questionId] ?? null;

            if ($this->isAnswerCorrect($question, $userAnswer)) {
                $correctAnswers++;
            }
        }

        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }

    /**
     * Verificar se resposta está correta
     */
    private function isAnswerCorrect(array $question, $userAnswer): bool
    {
        $questionType = $question['type'] ?? 'multiple_choice';

        return match($questionType) {
            'multiple_choice' => $userAnswer === $question['correct_answer'],
            'multiple_select' => $this->compareMultipleSelect($question['correct_answers'] ?? [], $userAnswer ?? []),
            'true_false' => $userAnswer === $question['correct_answer'],
            'text' => $this->compareTextAnswer($question['correct_answer'] ?? '', $userAnswer ?? ''),
            default => false,
        };
    }

    /**
     * Comparar respostas de múltipla seleção
     */
    private function compareMultipleSelect(array $correctAnswers, array $userAnswers): bool
    {
        sort($correctAnswers);
        sort($userAnswers);
        return $correctAnswers === $userAnswers;
    }

    /**
     * Comparar resposta de texto
     */
    private function compareTextAnswer(string $correctAnswer, string $userAnswer): bool
    {
        // Comparação simples - pode ser melhorada com fuzzy matching
        return strtolower(trim($correctAnswer)) === strtolower(trim($userAnswer));
    }

    /**
     * Obter tipo formatado
     */
    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'quiz' => 'Quiz',
            'reflection' => 'Reflexão',
            'activity' => 'Atividade',
            default => 'Avaliação',
        };
    }

    /**
     * Obter ícone do tipo
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'quiz' => 'help-circle',
            'reflection' => 'message-circle',
            'activity' => 'clipboard',
            default => 'file-text',
        };
    }
}