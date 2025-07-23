<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasActiveStatus;
use App\Models\Traits\Orderable;
use App\Models\Traits\FormattedTimestamps;

/**
 * Model para representar uma aula dentro de um módulo
 * 
 * @property int $id
 * @property int $module_id
 * @property string $title
 * @property string|null $description
 * @property string|null $objective
 * @property int $order_index
 * @property bool $is_active
 * @property bool $is_optional
 * @property int|null $duration_minutes
 * @property array|null $metadata
 */
class Lesson extends Model
{
    use HasFactory, SoftDeletes, HasActiveStatus, Orderable, FormattedTimestamps;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'objective',
        'order_index',
        'is_active',
        'is_optional',
        'duration_minutes',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_optional' => 'boolean',
        'duration_minutes' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento com módulo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relacionamento com vídeo da aula
     */
    public function video(): HasOne
    {
        return $this->hasOne(LessonVideo::class);
    }

    /**
     * Relacionamento com materiais complementares
     */
    public function materials(): HasMany
    {
        return $this->hasMany(LessonMaterial::class)->orderBy('order_index');
    }

    /**
     * Relacionamento com quiz da aula
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(LessonQuiz::class);
    }

    /**
     * Relacionamento com progresso dos usuários
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Relacionamento com comentários
     */
    public function comments(): HasMany
    {
        return $this->hasMany(LessonComment::class)->whereNull('parent_id')->orderBy('created_at');
    }

    /**
     * Relacionamento com notas dos usuários
     */
    public function userNotes(): HasMany
    {
        return $this->hasMany(LessonNote::class);
    }

    /**
     * Verificar se a aula foi completada pelo usuário
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * Obter progresso da aula para um usuário
     */
    public function getProgressFor(User $user): ?LessonProgress
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Marcar aula como completada para um usuário
     */
    public function markAsCompletedFor(User $user): LessonProgress
    {
        return $this->userProgress()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'is_completed' => true,
                'completed_at' => now(),
                'progress_percentage' => 100,
            ]
        );
    }

    /**
     * Obter próxima aula no módulo
     */
    public function getNextLesson(): ?self
    {
        return self::where('module_id', $this->module_id)
            ->where('order_index', '>', $this->order_index)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->first();
    }

    /**
     * Obter aula anterior no módulo
     */
    public function getPreviousLesson(): ?self
    {
        return self::where('module_id', $this->module_id)
            ->where('order_index', '<', $this->order_index)
            ->where('is_active', true)
            ->orderBy('order_index', 'desc')
            ->first();
    }

    /**
     * Verificar se a aula está disponível para o usuário
     */
    public function isAvailableFor(User $user): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Se é opcional, sempre disponível
        if ($this->is_optional) {
            return true;
        }

        // Verificar se aulas anteriores foram completadas
        $previousLessons = self::where('module_id', $this->module_id)
            ->where('order_index', '<', $this->order_index)
            ->where('is_active', true)
            ->where('is_optional', false)
            ->get();

        foreach ($previousLessons as $lesson) {
            if (!$lesson->isCompletedBy($user)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obter duração formatada
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) {
            return 'N/A';
        }

        if ($this->duration_minutes < 60) {
            return $this->duration_minutes . ' min';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($minutes === 0) {
            return $hours . 'h';
        }

        return $hours . 'h ' . $minutes . 'min';
    }

    /**
     * Obter estatísticas de engajamento
     */
    public function getEngagementStats(): array
    {
        $totalUsers = $this->module->course->enrolledUsers()->count();
        $completedUsers = $this->userProgress()->where('is_completed', true)->count();
        $averageWatchTime = $this->userProgress()->avg('watch_time_seconds') ?? 0;

        return [
            'total_users' => $totalUsers,
            'completed_users' => $completedUsers,
            'completion_rate' => $totalUsers > 0 ? round(($completedUsers / $totalUsers) * 100, 1) : 0,
            'average_watch_time' => round($averageWatchTime / 60, 1), // em minutos
            'engagement_score' => $this->calculateEngagementScore(),
        ];
    }

    /**
     * Calcular score de engajamento
     */
    private function calculateEngagementScore(): float
    {
        $stats = $this->getEngagementStats();
        $commentsCount = $this->comments()->count();
        $notesCount = $this->userNotes()->count();

        // Fórmula simples de engajamento
        $score = ($stats['completion_rate'] * 0.4) + 
                 (min($commentsCount * 5, 30) * 0.3) + 
                 (min($notesCount * 3, 30) * 0.3);

        return round($score, 1);
    }
}