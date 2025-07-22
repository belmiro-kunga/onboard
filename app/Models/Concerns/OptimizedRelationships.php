<?php

namespace App\Models\Concerns;

/**
 * Trait com relacionamentos otimizados para evitar N+1 queries
 */
trait OptimizedRelationships
{
    /**
     * Carrega relacionamentos com eager loading otimizado
     */
    public function scopeWithOptimizedRelations($query, array $relations = [])
    {
        if (empty($relations)) {
            // Relacionamentos padrão baseados no modelo
            $relations = $this->getDefaultEagerLoad();
        }

        return $query->with($relations);
    }

    /**
     * Relacionamentos padrão para eager loading
     */
    protected function getDefaultEagerLoad(): array
    {
        return [];
    }

    /**
     * Carrega relacionamentos apenas se necessário
     */
    public function loadRelationIfNeeded(string $relation)
    {
        if (!$this->relationLoaded($relation)) {
            $this->load($relation);
        }
        
        return $this;
    }

    /**
     * Conta relacionamentos sem carregar os dados
     */
    public function scopeWithCounts($query, array $relations = [])
    {
        foreach ($relations as $relation) {
            $query->withCount($relation);
        }
        
        return $query;
    }
}
