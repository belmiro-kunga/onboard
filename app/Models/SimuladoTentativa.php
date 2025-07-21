<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SimuladoTentativa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'simulado_id',
        'user_id',
        'status',
        'score',
        'passed',
        'started_at',
        'completed_at',
        'time_spent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'float',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent' => 'integer',
    ];

    /**
     * Relacionamento: simulado ao qual a tentativa pertence.
     */
    public function simulado(): BelongsTo
    {
        return $this->belongsTo(Simulado::class);
    }

    /**
     * Relacionamento: usuário que realizou a tentativa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: respostas da tentativa.
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(SimuladoResposta::class, 'tentativa_id');
    }

    /**
     * Calcular a pontuação da tentativa.
     */
    public function calcularPontuacao(): float
    {
        if ($this->status !== 'completed') {
            return 0;
        }

        $totalPontos = $this->simulado->getTotalPoints();
        
        if ($totalPontos === 0) {
            return 0;
        }

        $pontosObtidos = $this->respostas()
            ->where('is_correct', true)
            ->join('simulado_questoes', 'simulado_respostas.questao_id', '=', 'simulado_questoes.id')
            ->sum('simulado_questoes.pontos');

        return ($pontosObtidos / $totalPontos) * 100;
    }

    /**
     * Verificar se a tentativa foi aprovada.
     */
    public function verificarAprovacao(): bool
    {
        if ($this->status !== 'completed') {
            return false;
        }

        return $this->score >= $this->simulado->passing_score;
    }

    /**
     * Finalizar a tentativa.
     */
    public function finalizar(): void
    {
        if ($this->status === 'completed') {
            return;
        }

        $this->status = 'completed';
        $this->completed_at = now();
        $this->time_spent = $this->started_at->diffInMinutes($this->completed_at);
        $this->score = $this->calcularPontuacao();
        $this->passed = $this->verificarAprovacao();
        $this->save();
    }

    /**
     * Obter o tempo restante em segundos.
     */
    public function getTempoRestante(): int
    {
        if ($this->status === 'completed') {
            return 0;
        }

        $tempoLimite = $this->simulado->time_limit * 60; // em segundos
        $tempoDecorrido = now()->diffInSeconds($this->started_at);

        return max(0, $tempoLimite - $tempoDecorrido);
    }

    /**
     * Verificar se o tempo expirou.
     */
    public function isTempoExpirado(): bool
    {
        return $this->getTempoRestante() <= 0;
    }

    /**
     * Obter o número de questões respondidas.
     */
    public function getQuestoesRespondidasCount(): int
    {
        return $this->respostas()->count();
    }

    /**
     * Obter o número total de questões.
     */
    public function getTotalQuestoesCount(): int
    {
        return $this->simulado->questoes()->count();
    }

    /**
     * Obter o progresso da tentativa em porcentagem.
     */
    public function getProgresso(): float
    {
        $total = $this->getTotalQuestoesCount();
        
        if ($total === 0) {
            return 0;
        }

        $respondidas = $this->getQuestoesRespondidasCount();

        return ($respondidas / $total) * 100;
    }
}