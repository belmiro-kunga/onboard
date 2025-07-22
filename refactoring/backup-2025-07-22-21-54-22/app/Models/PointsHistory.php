<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'points',
        'reason',
        'reference_type',
        'reference_id',
        'multiplier',
        'bonus_points',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'points' => 'integer',
        'multiplier' => 'float',
        'bonus_points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento polimórfico com referência
     */
    public function reference(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Métodos de negócio
     */

    /**
     * Obter total de pontos (incluindo bônus)
     */
    public function getTotalPoints(): int
    {
        return $this->points + ($this->bonus_points ?? 0);
    }

    /**
     * Verificar se é ganho de pontos
     */
    public function isGain(): bool
    {
        return $this->points > 0;
    }

    /**
     * Verificar se é perda de pontos
     */
    public function isLoss(): bool
    {
        return $this->points < 0;
    }

    /**
     * Scopes
     */

    /**
     * Scope para ganhos de pontos
     */
    public function scopeGains(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('points', '>', 0);
    }

    /**
     * Scope para perdas de pontos
     */
    public function scopeLosses(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('points', '<', 0);
    }

    /**
     * Scope por período
     */
    public function scopeInPeriod(\Illuminate\Database\Eloquent\Builder $query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Accessors
     */

    /**
     * Obter pontos formatados
     */
    public function getFormattedPointsAttribute(): string
    {
        $points = $this->getTotalPoints();
        
        if ($points > 0) {
            return '+' . $points;
        }
        
        return (string) $points;
    }

    /**
     * Obter cor dos pontos
     */
    public function getPointsColorAttribute(): string
    {
        return $this->isGain() ? 'green' : 'red';
    }

    /**
     * Obter ícone dos pontos
     */
    public function getPointsIconAttribute(): string
    {
        return $this->isGain() ? 'trending-up' : 'trending-down';
    }
}