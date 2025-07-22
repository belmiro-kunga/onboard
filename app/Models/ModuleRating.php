<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\FormattedTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleRating extends Model
{
    use HasFactory, FormattedTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'module_id',
        'rating',
        'comment',
        'feedback_data',
        'is_anonymous',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'feedback_data' => 'array',
        'is_anonymous' => 'boolean',
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
     * Relacionamento com módulo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Scopes
     */

    /**
     * Scope por rating
     */
    public function scopeByRating(\Illuminate\Database\Eloquent\Builder $query, int $rating): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope para avaliações com comentários
     */
    public function scopeWithComments(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNotNull('comment')->where('comment', '!=', '');
    }

    /**
     * Scope para avaliações recentes
     */
    // Scope Recent disponível via trait


    /**
     * Accessors
     */

    /**
     * Obter rating em formato de estrelas
     */
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Obter nome do usuário (considerando anonimato)
     */
    public function getUserNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Usuário Anônimo';
        }
        
        return $this->user->name ?? 'Usuário';
    }

    /**
     * Obter iniciais do usuário (considerando anonimato)
     */
    public function getUserInitialsAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'AN';
        }
        
        return $this->user->initials ?? 'U';
    }

    /**
     * Verificar se é uma avaliação positiva (4-5 estrelas)
     */
    public function getIsPositiveAttribute(): bool
    {
        return $this->rating >= 4;
    }

    /**
     * Verificar se é uma avaliação negativa (1-2 estrelas)
     */
    public function getIsNegativeAttribute(): bool
    {
        return $this->rating <= 2;
    }

    /**
     * Obter cor baseada no rating
     */
    public function getRatingColorAttribute(): string
    {
        return match($this->rating) {
            5 => 'text-green-500',
            4 => 'text-green-400',
            3 => 'text-yellow-500',
            2 => 'text-orange-500',
            1 => 'text-red-500',
            default => 'text-gray-400'
        };
    }

    /**
     * Obter descrição do rating
     */
    public function getRatingDescriptionAttribute(): string
    {
        return match($this->rating) {
            5 => 'Excelente',
            4 => 'Muito Bom',
            3 => 'Bom',
            2 => 'Regular',
            1 => 'Ruim',
            default => 'Não avaliado'
        };
    }
}