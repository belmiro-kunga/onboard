<?php

namespace App\Repositories;

use App\Models\Module;

class ModuleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Module());
    }

    /**
     * Buscar módulos ativos ordenados
     */
    public function getActiveOrdered()
    {
        return $this->model->where('is_active', true)
                          ->orderBy('order_index')
                          ->get();
    }

    /**
     * Buscar módulos por curso
     */
    public function getByCourse(int $courseId)
    {
        return $this->model->where('course_id', $courseId)
                          ->orderBy('order_index')
                          ->get();
    }

    /**
     * Contar módulos ativos
     */
    public function countActive(): int
    {
        return $this->model->where('is_active', true)->count();
    }

    /**
     * Buscar módulos ativos ordenados (método específico para dashboard)
     */
    public function getActiveOrderedForDashboard()
    {
        return $this->model->where('is_active', true)
                          ->orderBy('order_index')
                          ->get();
    }

    /**
     * Contar módulos ativos (método específico)
     */
    public function countActiveModules(): int
    {
        return $this->model->where('is_active', true)->count();
    }
}
