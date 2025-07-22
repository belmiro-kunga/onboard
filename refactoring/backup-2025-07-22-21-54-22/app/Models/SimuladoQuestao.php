<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimuladoQuestao extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'simulado_id',
        'enunciado',
        'tipo',
        'opcoes',
        'resposta_correta',
        'explicacao',
        'pontos',
        'ordem',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opcoes' => 'array',
        'pontos' => 'integer',
        'ordem' => 'integer',
    ];

    /**
     * Relacionamento: simulado ao qual a questão pertence.
     */
    public function simulado(): BelongsTo
    {
        return $this->belongsTo(Simulado::class);
    }

    /**
     * Relacionamento: respostas dos usuários para esta questão.
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(SimuladoResposta::class, 'questao_id');
    }

    /**
     * Verifica se a resposta fornecida está correta.
     */
    public function isCorrect(string $resposta): bool
    {
        if ($this->tipo === 'dissertativa') {
            // Para questões dissertativas, a correção é manual
            return false;
        }

        return $resposta === $this->resposta_correta;
    }

    /**
     * Obter o texto da opção pelo valor.
     */
    public function getOpcaoText(string $valor): ?string
    {
        if ($this->tipo !== 'multipla_escolha' || !is_array($this->opcoes)) {
            return null;
        }

        foreach ($this->opcoes as $opcao) {
            if (isset($opcao['valor']) && $opcao['valor'] === $valor) {
                return $opcao['texto'] ?? $valor;
            }
        }

        return null;
    }

    /**
     * Obter o texto da resposta correta.
     */
    public function getRespostaCorretaText(): ?string
    {
        if ($this->tipo === 'multipla_escolha') {
            return $this->getOpcaoText($this->resposta_correta);
        } elseif ($this->tipo === 'verdadeiro_falso') {
            return $this->resposta_correta === 'true' ? 'Verdadeiro' : 'Falso';
        }

        return $this->resposta_correta;
    }

    /**
     * Obter o tipo de questão formatado.
     */
    public function getTipoFormatado(): string
    {
        return match($this->tipo) {
            'multipla_escolha' => 'Múltipla Escolha',
            'verdadeiro_falso' => 'Verdadeiro/Falso',
            'dissertativa' => 'Dissertativa',
            default => $this->tipo,
        };
    }
}