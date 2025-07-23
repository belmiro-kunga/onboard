<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\FormattedTimestamps;
use App\Models\Traits\HasCommonScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * ... outros campos relevantes ...
 */
class Notification extends Model
{
    use HasFactory, FormattedTimestamps, HasCommonScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'read_at',
        'action_url',
        'action_text',
        'priority',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: usuário dono da notificação.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Métodos de negócio
     */

    /**
     * Marca a notificação como lida.
     */
    public function markAsRead(): void
    {
        $this->read_at = now();
        $this->save();
    }

    /**
     * Marcar como não lida
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Verificar se foi lida
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Verificar se não foi lida
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Verificar se expirou
     */
    public function isExpired(): bool
    {
        // Como não temos expires_at, consideramos que não expira
        return false;
    }

    /**
     * Verificar se é válida (não expirou)
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Scopes
     */

    /**
     * Scope para notificações não lidas.
     */
    public function scopeUnread(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para notificações lidas
     */
    public function scopeRead(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope para notificações válidas (não expiradas)
     */
    // Scope Valid disponível via trait HasCommonScopes


    /**
     * Scope por tipo
     */
    // Scope ByType disponível via trait HasCommonScopes


    /**
     * Scope ordenado por data
     */
    // Scope Ordered disponível via trait


    /**
     * Accessors
     */

    /**
     * Obter tipo formatado
     */
    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'module_completed' => 'Módulo Concluído',
            'quiz_passed' => 'Quiz Aprovado',
            'achievement_earned' => 'Conquista Desbloqueada',
            'level_up' => 'Subiu de Nível',
            'reminder' => 'Lembrete',
            'announcement' => 'Anúncio',
            'system' => 'Sistema',
            default => 'Notificação'
        };
    }

    /**
     * Obter ícone do tipo
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'module_completed' => 'check-circle',
            'quiz_passed' => 'award',
            'achievement_earned' => 'star',
            'level_up' => 'trending-up',
            'reminder' => 'clock',
            'announcement' => 'megaphone',
            'system' => 'settings',
            default => 'bell'
        };
    }

    /**
     * Obter cor do tipo
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'module_completed' => 'green',
            'quiz_passed' => 'blue',
            'achievement_earned' => 'yellow',
            'level_up' => 'purple',
            'reminder' => 'orange',
            'announcement' => 'indigo',
            'system' => 'gray',
            default => 'blue'
        };
    }

    /**
     * Obter tempo relativo
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at?->diffForHumans() ?? 'Agora';
    }
}