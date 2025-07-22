<?php

namespace App\Repositories;

use App\Models\Simulado;
use App\Models\SimuladoTentativa;

class SimuladoRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Simulado());
    }

    public function hasAttempts(int $simuladoId): bool
    {
        return SimuladoTentativa::where('simulado_id', $simuladoId)->exists();
    }

    public function getUserAttempt(int $simuladoId, int $userId, int $tentativaId): ?SimuladoTentativa
    {
        return SimuladoTentativa::where('id', $tentativaId)
                               ->where('user_id', $userId)
                               ->where('simulado_id', $simuladoId)
                               ->first();
    }

    public function getUserAttempts(int $simuladoId, int $userId)
    {
        return SimuladoTentativa::where('simulado_id', $simuladoId)
                               ->where('user_id', $userId)
                               ->orderBy('created_at', 'desc')
                               ->get();
    }

    public function countAttempts(int $simuladoId): int
    {
        return SimuladoTentativa::where('simulado_id', $simuladoId)->count();
    }

    /**
     * Verificar se tentativa pertence ao usuário e simulado
     */
    public function validateAttemptAccess(int $tentativaId, int $userId, int $simuladoId): bool
    {
        return SimuladoTentativa::where('id', $tentativaId)
                               ->where('user_id', $userId)
                               ->where('simulado_id', $simuladoId)
                               ->exists();
    }

    /**
     * Buscar tentativa com validação completa
     */
    public function getValidatedAttempt(int $tentativaId, int $userId, int $simuladoId): ?SimuladoTentativa
    {
        return SimuladoTentativa::where('id', $tentativaId)
                               ->where('user_id', $userId)
                               ->where('simulado_id', $simuladoId)
                               ->first();
    }
}
