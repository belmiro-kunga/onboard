<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Buscar todos os registros
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Buscar por ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Buscar por ID ou falhar
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Criar novo registro
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Atualizar registro
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Deletar registro
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Buscar com condições
     */
    public function where(string $column, $operator, $value = null): Collection
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        return $this->model->where($column, $operator, $value)->get();
    }

    /**
     * Contar registros
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Contar com condições
     */
    public function countWhere(string $column, $operator, $value = null): int
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        return $this->model->where($column, $operator, $value)->count();
    }

    /**
     * Buscar registros ativos (se o model suportar)
     */
    public function active(): Collection
    {
        if (method_exists($this->model, 'scopeActive')) {
            return $this->model->active()->get();
        }
        
        return $this->model->where('is_active', true)->get();
    }

    /**
     * Contar registros ativos
     */
    public function countActive(): int
    {
        if (method_exists($this->model, 'scopeActive')) {
            return $this->model->active()->count();
        }
        
        return $this->model->where('is_active', true)->count();
    }
}
