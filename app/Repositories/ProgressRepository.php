<?php

namespace App\Repositories;

use App\Models\UserProgress;
use App\Models\UserGamification;

class ProgressRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new UserProgress());
    }

    public function getUserModuleProgress(int $userId, int $moduleId): ?UserProgress
    {
        return $this->model->where('user_id', $userId)
                          ->where('module_id', $moduleId)
                          ->first();
    }

    public function getUserGamification(int $userId): ?UserGamification
    {
        return UserGamification::where('user_id', $userId)->first();
    }

    public function getUserProgress(int $userId)
    {
        return $this->model->where('user_id', $userId)
                          ->with(['module', 'course'])
                          ->orderBy('updated_at', 'desc')
                          ->get();
    }

    public function countCompletedModules(int $userId): int
    {
        return $this->model->where('user_id', $userId)
                          ->where('status', 'completed')
                          ->count();
    }
}
