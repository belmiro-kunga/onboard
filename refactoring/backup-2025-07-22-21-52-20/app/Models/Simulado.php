<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simulado extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'nivel',
        'duracao',
        'questoes_count',
        'passing_score',
        'pontos_recompensa',
        'status',
        'configuracoes',
        'disponivel_em',
        'expiracao_em',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duracao' => 'integer',
        'questoes_count' => 'integer',
        'passing_score' => 'integer',
        'pontos_recompensa' => 'integer',
        'configuracoes' => 'array',
        'disponivel_em' => 'datetime',
        'expiracao_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: questões do simulado.
     */
    public function questoes(): HasMany
    {
        return $this->hasMany(SimuladoQuestao::class)->orderBy('ordem');
    }

    /**
     * Relacionamento: tentativas do simulado.
     */
    public function tentativas(): HasMany
    {
        return $this->hasMany(SimuladoTentativa::class);
    }

    /**
     * Relacionamento: usuário que criou o simulado.
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento: usuários atribuídos ao simulado.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'simulado_user')
                    ->withTimestamps();
    }

    /**
     * Verifica se o simulado está disponível para um usuário.
     */
    public function isAvailableFor(User $user): bool
    {
        // Verificar se o simulado está ativo
        if ($this->status !== 'active') {
            return false;
        }

        // Verificar se o simulado está dentro do período de disponibilidade
        if ($this->disponivel_em && now()->lt($this->disponivel_em)) {
            return false;
        }

        if ($this->expiracao_em && now()->gt($this->expiracao_em)) {
            return false;
        }

        // Verificar se o usuário está atribuído ao simulado
        if (!$this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Para simulados, não há limite de tentativas por padrão
        // Este método pode ser expandido conforme necessário
        return true;
    }

    /**
     * Scope para filtrar simulados disponíveis (ativos, dentro do período de disponibilidade e não expirados).
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('disponivel_em')->orWhere('disponivel_em', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expiracao_em')->orWhere('expiracao_em', '>=', now());
            });
    }

    /**
     * Obter a pontuação total do simulado.
     */
    public function getTotalPoints(): int
    {
        return $this->questoes()->sum('pontos');
    }

    /**
     * Obter a taxa de aprovação do simulado.
     */
    public function getApprovalRate(): float
    {
        $tentativasConcluidas = $this->tentativas()
            ->where('status', 'completed')
            ->count();

        if ($tentativasConcluidas === 0) {
            return 0;
        }

        $aprovados = $this->tentativas()
            ->where('status', 'completed')
            ->where('passed', true)
            ->count();

        return ($aprovados / $tentativasConcluidas) * 100;
    }

    /**
     * Obter a média de notas do simulado.
     */
    public function getAverageScore(): float
    {
        return $this->tentativas()
            ->where('status', 'completed')
            ->avg('score') ?? 0;
    }

    /**
     * Obter o número de tentativas concluídas do simulado.
     */
    public function getCompletedAttemptsCount(): int
    {
        return $this->tentativas()
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Obter o número de tentativas em andamento do simulado.
     */
    public function getInProgressAttemptsCount(): int
    {
        return $this->tentativas()
            ->where('status', 'in_progress')
            ->count();
    }

    /**
     * Obter o número de usuários que já concluíram o simulado.
     */
    public function getCompletedUsersCount(): int
    {
        return $this->tentativas()
            ->where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Verificar se um usuário já concluiu o simulado.
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->tentativas()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Verificar se um usuário já passou no simulado.
     */
    public function isPassedBy(User $user): bool
    {
        return $this->tentativas()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('passed', true)
            ->exists();
    }

    /**
     * Obter a melhor tentativa de um usuário.
     */
    public function getBestAttemptFor(User $user): ?SimuladoTentativa
    {
        return $this->tentativas()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderByDesc('score')
            ->first();
    }

    /**
     * Obter a última tentativa de um usuário.
     */
    public function getLastAttemptFor(User $user): ?SimuladoTentativa
    {
        return $this->tentativas()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
    }

    /**
     * Obter o número de tentativas realizadas por um usuário.
     */
    public function getAttemptsCountFor(User $user): int
    {
        return $this->tentativas()
            ->where('user_id', $user->id)
            ->count();
    }
}