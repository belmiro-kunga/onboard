<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property bool $is_active
 * @property string|null $department
 * @property string|null $position
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'department',
        'position',
        'phone',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Relacionamento com atribuições recebidas
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(UserAssignment::class);
    }

    /**
     * Relacionamento com atribuições feitas por este usuário
     */
    public function assignmentsMade(): HasMany
    {
        return $this->hasMany(UserAssignment::class, 'assigned_by');
    }

    /**
     * Verificar se o usuário tem um papel específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar se o usuário é gestor
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Verificar se o usuário está ativo
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope para buscar por papel.
     */
    public function scopeByRole(\Illuminate\Database\Eloquent\Builder $query, string $role): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('role', $role);
    }

    /**
     * Scope para usuários ativos.
     */
    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }
}
