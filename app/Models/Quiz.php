<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasActiveStatus;
use App\Models\Traits\FormattedTimestamps;
use App\Models\Traits\HasCommonScopes;
use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User; // Added this import for User model
use App\Models\QuizAttempt; // Added this import for QuizAttempt model

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $category
 * @property string $difficulty
 * @property int $estimated_duration
 * @property int $points_reward
 * @property bool $is_active
 * @property int $max_attempts
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Quiz extends Model
{
    use HasFactory, HasActiveStatus, FormattedTimestamps, HasCommonScopes, Cacheable;

    /**
     * Os atributos que são atribuíveis em massa.
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty_level',
        'time_limit',
        'points_reward',
        'is_active',
        'max_attempts',
        'created_by',
        'module_id',
        'passing_score',
        'instructions',
        'randomize_questions',
        'show_results_immediately',
        'allow_review',
        'generate_certificate',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'points_reward' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'integer',
        'created_by' => 'integer',
        'module_id' => 'integer',
        'randomize_questions' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review' => 'boolean',
        'generate_certificate' => 'boolean',
    ];

    /**
     * Relacionamento: perguntas do quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Relacionamento: tentativas do quiz.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Relacionamento: módulo ao qual o quiz pertence.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relacionamento: usuário que criou o quiz.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope para quizzes ativos.
     */
    // Scope Active disponível via trait


    /**
     * Retorna a melhor tentativa do usuário.
     */
    public function getBestAttemptFor(User $user): ?QuizAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->orderBy('score', 'desc')
            ->first();
    }

    /**
     * Verifica se o usuário pode tentar o quiz novamente.
     */
    public function canUserAttempt(User $user): bool
    {
        $attemptsCount = $this->attempts()->where('user_id', $user->id)->count();
        return $attemptsCount < ($this->max_attempts ?? 3);
    }

    /**
     * Verifica se o usuário passou no quiz.
     */
    public function hasUserPassed(User $user): bool
    {
        $bestAttempt = $this->getBestAttemptFor($user);
        return $bestAttempt && $bestAttempt->score >= 70;
    }

    /**
     * Retorna o número de tentativas do usuário.
     */
    public function getUserAttemptsCount(User $user): int
    {
        return $this->attempts()->where('user_id', $user->id)->count();
    }

    /**
     * Retorna a categoria formatada.
     */
    public function getFormattedCategoryAttribute(): string
    {
        return match($this->category) {
            'hr' => 'Recursos Humanos',
            'it' => 'Tecnologia',
            'security' => 'Segurança',
            'processes' => 'Processos',
            'culture' => 'Cultura',
            'general' => 'Geral',
            default => ucfirst($this->category)
        };
    }
}