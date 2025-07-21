<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property string $category
 * @property string $type
 * @property array $condition_data
 * @property int $points_reward
 * @property bool $is_active
 * @property string $rarity
 * @property string $unlock_message
 */
class Achievement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'category',
        'type',
        'condition_data',
        'points_reward',
        'is_active',
        'rarity',
        'unlock_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'condition_data' => 'array',
        'points_reward' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento: usuários que conquistaram este achievement.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot(['earned_at', 'points_awarded'])
                    ->withTimestamps();
    }

    /**
     * Métodos de negócio
     */

    /**
     * Verificar se usuário atende à condição da conquista
     */
    public function checkCondition(User $user): bool
    {
        return match($this->type) {
            'modules_completed' => $this->checkModulesCompleted($user),
            'points_earned' => $this->checkPointsEarned($user),
            'quiz_streak' => $this->checkQuizStreak($user),
            'perfect_score' => $this->checkPerfectScore($user),
            'time_spent' => $this->checkTimeSpent($user),
            'login_streak' => $this->checkLoginStreak($user),
            'first_module' => $this->checkFirstModule($user),
            'speed_demon' => $this->checkSpeedDemon($user),
            'knowledge_seeker' => $this->checkKnowledgeSeeker($user),
            'social_butterfly' => $this->checkSocialButterfly($user),
            default => false
        };
    }

    /**
     * Verificar conquista de módulos completados
     */
    private function checkModulesCompleted(User $user): bool
    {
        $required = $this->condition_data['modules_count'] ?? 1;
        $completed = $user->completedModules()->count();
        
        return $completed >= $required;
    }

    /**
     * Verificar conquista de pontos ganhos
     */
    private function checkPointsEarned(User $user): bool
    {
        $required = $this->condition_data['points_required'] ?? 100;
        $earned = $user->getTotalPoints();
        
        return $earned >= $required;
    }

    /**
     * Verificar conquista de sequência de quizzes
     */
    private function checkQuizStreak(User $user): bool
    {
        $required = $this->condition_data['streak_count'] ?? 5;
        
        // Verificar últimas tentativas de quiz consecutivas aprovadas
        $recentAttempts = $user->quizAttempts()
                              ->orderBy('completed_at', 'desc')
                              ->limit($required)
                              ->get();
        
        if ($recentAttempts->count() < $required) {
            return false;
        }
        
        return $recentAttempts->every(fn($attempt) => $attempt->passed);
    }

    /**
     * Verificar conquista de pontuação perfeita
     */
    private function checkPerfectScore(User $user): bool
    {
        $required = $this->condition_data['perfect_count'] ?? 1;
        
        $perfectScores = $user->quizAttempts()
                             ->where('score', 100)
                             ->where('passed', true)
                             ->count();
        
        return $perfectScores >= $required;
    }

    /**
     * Verificar conquista de tempo gasto
     */
    private function checkTimeSpent(User $user): bool
    {
        $required = $this->condition_data['minutes_required'] ?? 60;
        
        $totalTime = $user->progress()
                         ->whereNotNull('time_spent')
                         ->sum('time_spent');
        
        return $totalTime >= $required;
    }

    /**
     * Verificar conquista de sequência de login
     */
    private function checkLoginStreak(User $user): bool
    {
        $required = $this->condition_data['days_required'] ?? 7;
        $gamification = $user->gamification;
        
        return $gamification && $gamification->streak_days >= $required;
    }

    /**
     * Verificar conquista de primeiro módulo
     */
    private function checkFirstModule(User $user): bool
    {
        return $user->completedModules()->count() >= 1;
    }

    /**
     * Verificar conquista de velocidade
     */
    private function checkSpeedDemon(User $user): bool
    {
        $maxTime = $this->condition_data['max_time_minutes'] ?? 30;
        
        return $user->progress()
                   ->where('status', 'completed')
                   ->where('time_spent', '<=', $maxTime)
                   ->exists();
    }

    /**
     * Verificar conquista de busca por conhecimento
     */
    private function checkKnowledgeSeeker(User $user): bool
    {
        $categories = $this->condition_data['categories'] ?? [];
        
        if (empty($categories)) {
            return false;
        }
        
        $completedCategories = $user->completedModules()
                                  ->distinct('category')
                                  ->pluck('category')
                                  ->toArray();
        
        return count(array_intersect($categories, $completedCategories)) >= count($categories);
    }

    /**
     * Verificar conquista social
     */
    private function checkSocialButterfly(User $user): bool
    {
        // Implementar quando houver funcionalidades sociais
        return false;
    }

    /**
     * Scopes
     */

    /**
     * Scope para achievements ativos
     */
    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope por categoria
     */
    public function scopeByCategory(\Illuminate\Database\Eloquent\Builder $query, string $category): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope por raridade
     */
    public function scopeByRarity(\Illuminate\Database\Eloquent\Builder $query, string $rarity): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('rarity', $rarity);
    }

    /**
     * Verifica se o usuário já conquistou este achievement.
     */
    public function isEarnedBy(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Accessors
     */

    /**
     * Obter URL do ícone
     */
    public function getIconUrlAttribute(): string
    {
        if ($this->icon) {
            return asset('storage/achievements/icons/' . $this->icon);
        }
        
        return asset('images/achievement-default.png');
    }

    /**
     * Obter cor da raridade
     */
    public function getRarityColorAttribute(): string
    {
        return match($this->rarity) {
            'common' => '#6B7280',
            'uncommon' => '#10B981',
            'rare' => '#3B82F6',
            'epic' => '#8B5CF6',
            'legendary' => '#F59E0B',
            default => '#6B7280'
        };
    }

    /**
     * Obter raridade formatada
     */
    public function getFormattedRarityAttribute(): string
    {
        return match($this->rarity) {
            'common' => 'Comum',
            'uncommon' => 'Incomum',
            'rare' => 'Raro',
            'epic' => 'Épico',
            'legendary' => 'Lendário',
            default => 'Comum'
        };
    }

    /**
     * Obter categoria formatada
     */
    public function getFormattedCategoryAttribute(): string
    {
        return match($this->category) {
            'progress' => 'Progresso',
            'performance' => 'Performance',
            'engagement' => 'Engajamento',
            'social' => 'Social',
            'special' => 'Especial',
            default => 'Geral'
        };
    }
}