<?php

namespace App\Models\Traits;

trait HasActiveStatus
{
    /**
     * Scope para filtrar apenas registros ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para filtrar apenas registros inativos
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Verifica se o registro está ativo
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Verifica se o registro está inativo
     */
    public function isInactive(): bool
    {
        return $this->is_active === false;
    }

    /**
     * Ativa o registro
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Desativa o registro
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Alterna o status ativo/inativo
     */
    public function toggleActive(): bool
    {
        return $this->update(['is_active' => !$this->is_active]);
    }
}
