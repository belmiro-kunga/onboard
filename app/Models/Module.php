<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasActiveStatus;
use App\Models\Traits\Orderable;
use App\Models\Traits\FormattedTimestamps;, Cacheable
use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $category
 * @property int $order_index
 * @property bool $is_active
 * @property int $points_reward
 * @property int $estimated_duration
 * @property string $content_type
 * @property array $content_data
 * @property string $thumbnail
 * @property string $difficulty_level
 * @property array $prerequisites
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Module extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'category',
        'order_index',
        'is_active',
        'points_reward',
        'estimated_duration',
        'content_type',
        'content_data',
        'thumbnail',
        'difficulty_level',
        'prerequisites',
        'requirements',
        'duration_minutes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'points_reward' => 'integer',
        'estimated_duration' => 'integer',
        'content_data' => 'array',
        'prerequisites' => 'array',
        'requirements' => 'array',
        'duration_minutes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento: curso ao qual o módulo pertence
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relacionamento: conteúdos do módulo.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(ModuleContent::class);
    }

    /**
     * Relacionamento: quizzes do módulo.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Relacionamento: progresso dos usuários neste módulo.
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Relacionamento: usuários que completaram o módulo.
     */
    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_progress')
                    ->wherePivot('status', 'completed')
                    ->withPivot(['started_at', 'completed_at', 'time_spent'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento: pré-requisitos do módulo.
     */
    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_prerequisites', 'module_id', 'prerequisite_id');
    }

    /**
     * Relacionamento: módulos que dependem deste.
     */
    public function dependentModules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_prerequisites', 'prerequisite_id', 'module_id');
    }

    /**
     * Métodos de negócio
     */

    /**
     * Verificar se o módulo é acessível por um usuário.
     */
    public function isAccessibleBy(User $user): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Verificar pré-requisitos
        if ($this->prerequisites && count($this->prerequisites) > 0) {
            $completedModuleIds = $user->completedModules()->pluck('modules.id')->toArray();
            
            foreach ($this->prerequisites as $prerequisiteId) {
                if (!in_array($prerequisiteId, $completedModuleIds)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Obter taxa de conclusão do módulo.
     */
    public function getCompletionRate(): float
    {
        $totalUsers = User::where('is_active', true)->count();
        $completedUsers = $this->completedByUsers()->count();
        
        if ($totalUsers === 0) {
            return 0.0;
        }
        
        return round(($completedUsers / $totalUsers) * 100, 2);
    }

    /**
     * Obter tempo médio de conclusão em minutos.
     */
    public function getAverageTime(): int
    {
        $averageTime = $this->userProgress()
                           ->where('status', 'completed')
                           ->whereNotNull('time_spent')
                           ->avg('time_spent');
        
        return (int) round($averageTime ?? 0);
    }

    /**
     * Verificar se o usuário já completou este módulo.
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->userProgress()
                   ->where('user_id', $user->id)
                   ->where('status', 'completed')
                   ->exists();
    }

    /**
     * Obter progresso do usuário neste módulo.
     */
    public function getProgressFor(User $user): ?UserProgress
    {
        return $this->userProgress()
                   ->where('user_id', $user->id)
                   ->first();
    }

    /**
     * Obter porcentagem de conclusão do usuário neste módulo.
     */
    public function getCompletionPercentageFor(User $user): int
    {
        $progress = $this->getProgressFor($user);
        
        if (!$progress) {
            return 0;
        }
        
        return $progress->progress_percentage;
    }

    /**
     * Obter próximo módulo na sequência.
     */
    public function getNextModule(): ?Module
    {
        return static::where('category', $this->category)
                    ->where('order_index', '>', $this->order_index)
                    ->where('is_active', true)
                    ->orderBy('order_index')
                    ->first();
    }

    /**
     * Obter módulo anterior na sequência.
     */
    public function getPreviousModule(): ?Module
    {
        return static::where('category', $this->category)
                    ->where('order_index', '<', $this->order_index)
                    ->where('is_active', true)
                    ->orderBy('order_index', 'desc')
                    ->first();
    }

    /**
     * Scopes
     */

    /**
     * Scope para módulos ativos.
     */
    // Scope Active disponível via trait


    /**
     * Scope para módulos por categoria.
     */
    // Scope ByCategory disponível via trait HasCommonScopes


    /**
     * Scope para módulos ordenados.
     */
    // Scope Ordered disponível via trait


    /**
     * Scope para módulos por dificuldade.
     */
    public function scopeByDifficulty(\Illuminate\Database\Eloquent\Builder $query, string $difficulty): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Accessors
     */

    /**
     * Obter URL da thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/modules/thumbnails/' . $this->thumbnail);
        }
        
        return asset('images/module-default.png');
    }

    /**
     * Obter duração formatada.
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->estimated_duration < 60) {
            return $this->estimated_duration . ' min';
        }
        
        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;
        
        if ($minutes === 0) {
            return $hours . 'h';
        }
        
        return $hours . 'h ' . $minutes . 'min';
    }

    /**
     * Obter nível de dificuldade formatado.
     */
    public function getFormattedDifficultyAttribute(): string
    {
        return match($this->difficulty_level) {
            'beginner' => 'Iniciante',
            'intermediate' => 'Intermediário',
            'advanced' => 'Avançado',
            default => 'Não definido'
        };
    }
}