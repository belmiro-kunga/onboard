<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait FormattedTimestamps
{
    /**
     * Retorna a data de criação formatada
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '-';
    }

    /**
     * Retorna a data de atualização formatada
     */
    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : '-';
    }

    /**
     * Retorna a data de criação em formato humano
     */
    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '-';
    }

    /**
     * Retorna a data de atualização em formato humano
     */
    public function getUpdatedAtHumanAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->diffForHumans() : '-';
    }

    /**
     * Scope para filtrar por período
     */
    public function scopeCreatedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Scope para filtrar registros recentes
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }
}
