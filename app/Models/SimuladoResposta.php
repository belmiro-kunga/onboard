<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimuladoResposta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tentativa_id',
        'questao_id',
        'resposta',
        'is_correct',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Relacionamento: tentativa à qual a resposta pertence.
     */
    public function tentativa(): BelongsTo
    {
        return $this->belongsTo(SimuladoTentativa::class, 'tentativa_id');
    }

    /**
     * Relacionamento: questão à qual a resposta pertence.
     */
    public function questao(): BelongsTo
    {
        return $this->belongsTo(SimuladoQuestao::class, 'questao_id');
    }

    /**
     * Verificar se a resposta está correta.
     */
    public function verificarResposta(): bool
    {
        $questao = $this->questao;
        
        if (!$questao) {
            return false;
        }

        return $questao->isCorrect($this->resposta);
    }

    /**
     * Obter o texto da resposta.
     */
    public function getRespostaText(): ?string
    {
        $questao = $this->questao;
        
        if (!$questao) {
            return $this->resposta;
        }

        if ($questao->tipo === 'multipla_escolha') {
            return $questao->getOpcaoText($this->resposta) ?? $this->resposta;
        } elseif ($questao->tipo === 'verdadeiro_falso') {
            return $this->resposta === 'true' ? 'Verdadeiro' : 'Falso';
        }

        return $this->resposta;
    }
}