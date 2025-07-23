<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignable_type',
        'assignable_id',
        'assigned_by',
        'assigned_at',
        'due_date',
        'status',
        'notes',
        'is_mandatory',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'due_date' => 'datetime',
        'is_mandatory' => 'boolean',
    ];

    /**
     * Relacionamento polimórfico com o item atribuído
     */
    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relacionamento com o usuário que recebeu a atribuição
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o usuário que fez a atribuição
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereIn('status', ['assigned', 'in_progress']);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('assignable_type', $type);
    }

    /**
     * Métodos auxiliares
     */
    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               in_array($this->status, ['assigned', 'in_progress']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'assigned' => 'Atribuído',
            'in_progress' => 'Em Progresso',
            'completed' => 'Concluído',
            'overdue' => 'Atrasado',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'assigned' => 'blue',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'overdue' => 'red',
            default => 'gray'
        };
    }

    public function getTypeNameAttribute(): string
    {
        return match($this->assignable_type) {
            'App\Models\Course' => 'Curso',
            'App\Models\Quiz' => 'Quiz',
            'App\Models\Module' => 'Módulo',
            'App\Models\Simulado' => 'Simulado',
            default => 'Item'
        };
    }
}