<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $total_points
 * @property string $current_level
 * @property int $rank_position
 * @property int $achievements_count
 * @property int $streak_days
 * @property int $longest_streak
 * @property string $last_activity_date
 * @property int $level_progress
 * @property array $badges
 * @property array $statistics
 */
class UserGamification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_points',
        'current_level',
        'rank_position',
        'achievements_count',
        'streak_days',
        'longest_streak',
        'last_activity_date',
        'level_progress',
        'badges',
        'statistics',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_points' => 'integer',
        'rank_position' => 'integer',
        'achievements_count' => 'integer',
        'streak_days' => 'integer',
        'longest_streak' => 'integer',
        'level_progress' => 'integer',
        'last_activity_date' => 'date',
        'badges' => 'array',
        'statistics' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: usuário deste gamification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com conquistas através do usuário
     */
    public function achievements(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->user->achievements();
    }

    /**
     * Métodos de negócio
     */

    /**
     * Adicionar pontos ao usuário
     */
    public function addPoints(int $points, string $reason): void
    {
        $this->increment('total_points', $points);
        $this->updateLastActivity();
        $this->checkLevelUp();
        
        // Registrar histórico de pontos
        $this->recordPointsHistory($points, $reason);
        
        // Verificar novas conquistas
        $this->checkForNewAchievements();
    }

    /**
     * Verificar se deve subir de nível
     */
    public function checkLevelUp(): void
    {
        $newLevel = $this->calculateLevel($this->total_points);
        
        if ($newLevel !== $this->current_level) {
            $oldLevel = $this->current_level;
            $this->update([
                'current_level' => $newLevel,
                'level_progress' => $this->calculateLevelProgress($this->total_points)
            ]);
            
            // Disparar evento de subida de nível
            event(new \App\Events\UserLevelUp($this->user, $oldLevel, $newLevel));
        }
    }

    /**
     * Calcular nível baseado nos pontos
     */
    private function calculateLevel(int $points): string
    {
        return match(true) {
            $points >= 10000 => 'Master',
            $points >= 5000 => 'Expert',
            $points >= 2500 => 'Advanced',
            $points >= 1000 => 'Intermediate',
            $points >= 500 => 'Explorer',
            $points >= 100 => 'Beginner',
            default => 'Rookie'
        };
    }

    /**
     * Calcular progresso do nível atual (0-100)
     */
    private function calculateLevelProgress(int $points): int
    {
        $levelRequirements = [
            'Rookie' => 0,
            'Beginner' => 100,
            'Explorer' => 500,
            'Intermediate' => 1000,
            'Advanced' => 2500,
            'Expert' => 5000,
            'Master' => 10000
        ];
        
        $currentLevel = $this->calculateLevel($points);
        $currentRequirement = $levelRequirements[$currentLevel];
        $nextRequirement = $levelRequirements[$this->getNextLevel()] ?? $currentRequirement;
        
        if ($nextRequirement === $currentRequirement) {
            return 100; // Nível máximo
        }
        
        $levelRange = $nextRequirement - $currentRequirement;
        $userProgress = $points - $currentRequirement;
        
        return min(100, max(0, (int) round(($userProgress / $levelRange) * 100)));
    }

    /**
     * Atualizar streak do usuário
     */
    public function updateStreak(): void
    {
        $lastActivity = $this->last_activity_date;
        $today = now()->startOfDay();
        
        if (!$lastActivity) {
            // Primeira atividade
            $this->update([
                'streak_days' => 1,
                'last_activity_date' => $today
            ]);
            return;
        }
        
        $lastActivityDay = $lastActivity->startOfDay();
        $daysDiff = $lastActivityDay->diffInDays($today);
        
        if ($daysDiff === 0) {
            // Mesmo dia, não fazer nada
            return;
        } elseif ($daysDiff === 1) {
            // Dia consecutivo
            $this->increment('streak_days');
        } else {
            // Streak quebrada
            $this->update(['streak_days' => 1]);
        }
        
        $this->updateLastActivity();
    }

    /**
     * Atualizar última atividade
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity_date' => now()]);
    }

    /**
     * Verificar novas conquistas
     */
    public function checkForNewAchievements(): array
    {
        $newAchievements = [];
        $availableAchievements = Achievement::where('is_active', true)->get();
        $earnedAchievementIds = $this->user->achievements()->pluck('achievements.id')->toArray();
        
        foreach ($availableAchievements as $achievement) {
            if (!in_array($achievement->id, $earnedAchievementIds)) {
                if ($achievement->checkCondition($this->user)) {
                    $this->earnAchievement($achievement);
                    $newAchievements[] = $achievement;
                }
            }
        }
        
        return $newAchievements;
    }

    /**
     * Conquistar uma achievement
     */
    public function earnAchievement(Achievement $achievement): void
    {
        // Usar o relacionamento do usuário diretamente
        $this->user->achievements()->attach($achievement->id, [
            'earned_at' => now(),
            'points_awarded' => $achievement->points_reward
        ]);
        
        $this->increment('achievements_count');
        $this->addPoints($achievement->points_reward, "Conquista: {$achievement->name}");
        
        // Disparar evento
        event(new \App\Events\AchievementEarned($this->user, $achievement));
    }

    /**
     * Registrar histórico de pontos
     */
    private function recordPointsHistory(int $points, string $reason): void
    {
        PointsHistory::create([
            'user_id' => $this->user_id,
            'points' => $points,
            'reason' => $reason,
            'created_at' => now()
        ]);
    }

    /**
     * Obter ranking atual do usuário
     */
    public function getCurrentRank(): int
    {
        return static::where('total_points', '>', $this->total_points)->count() + 1;
    }

    /**
     * Obter pontos necessários para próximo nível
     */
    public function getNextLevelRequirement(): int
    {
        $levelRequirements = [
            'Rookie' => 100,
            'Beginner' => 500,
            'Explorer' => 1000,
            'Intermediate' => 2500,
            'Advanced' => 5000,
            'Expert' => 10000,
            'Master' => 0 // Nível máximo
        ];
        
        $nextLevel = $this->getNextLevel();
        
        if (!$nextLevel) {
            return 0; // Já está no nível máximo
        }
        
        return $levelRequirements[$nextLevel] - $this->total_points;
    }

    /**
     * Obter próximo nível
     */
    private function getNextLevel(): ?string
    {
        $levels = ['Rookie', 'Beginner', 'Explorer', 'Intermediate', 'Advanced', 'Expert', 'Master'];
        $currentIndex = array_search($this->current_level, $levels);

        if ($currentIndex === false || !isset($levels[$currentIndex + 1])) {
            return null;
        }

        return $levels[$currentIndex + 1];
    }

    /**
     * Accessors
     */

    /**
     * Obter nível formatado
     */
    public function getFormattedLevelAttribute(): string
    {
        return match($this->current_level) {
            'Rookie' => 'Novato',
            'Beginner' => 'Iniciante',
            'Explorer' => 'Explorador',
            'Intermediate' => 'Intermediário',
            'Advanced' => 'Avançado',
            'Expert' => 'Especialista',
            'Master' => 'Mestre',
            default => 'Não definido'
        };
    }

    /**
     * Obter cor do nível
     */
    public function getLevelColorAttribute(): string
    {
        return match($this->current_level) {
            'Rookie' => '#6B7280',
            'Beginner' => '#10B981',
            'Explorer' => '#3B82F6',
            'Intermediate' => '#8B5CF6',
            'Advanced' => '#F59E0B',
            'Expert' => '#EF4444',
            'Master' => '#F59E0B',
            default => '#6B7280'
        };
    }

    /**
     * Scope para gamification com streak ativa.
     */
    public function scopeWithActiveStreak(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('streak_days', '>', 0);
    }

    /**
     * Verifica se o usuário está em streak.
     */
    public function hasStreak(): bool
    {
        return $this->streak_days > 0;
    }
}