<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * Buscar usuário por email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Contar admins
     */
    public function countAdmins(): int
    {
        return $this->model->where('role', 'admin')->count();
    }

    /**
     * Buscar usuários por departamento
     */
    public function getByDepartment(string $department)
    {
        return $this->model->where('department', $department)->get();
    }

    /**
     * Buscar usuários ativos por role
     */
    public function getActiveByRole(string $role)
    {
        return $this->model->where('role', $role)
                          ->where('is_active', true)
                          ->get();
    }
}
