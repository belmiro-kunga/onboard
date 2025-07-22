<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;

class QuizRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Quiz());
    }

    public function getActive()
    {
        return $this->model->where('is_active', true)->orderBy('title')->get();
    }

    public function getUserAttempts(int $userId, string $startDate = null)
    {
        $query = QuizAttempt::where('user_id', $userId);
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        return $query->get();
    }

    public function getWithQuestions(int $quizId)
    {
        return $this->model->with('questions')->find($quizId);
    }

    public function countAttempts(int $quizId): int
    {
        return QuizAttempt::where('quiz_id', $quizId)->count();
    }

    /**
     * Buscar resposta de tentativa específica
     */
    public function getAttemptAnswer(int $attemptId, int $questionId): ?QuizAttemptAnswer
    {
        return QuizAttemptAnswer::where('attempt_id', $attemptId)
                                ->where('question_id', $questionId)
                                ->first();
    }

    /**
     * Verificar se tentativa já foi concluída
     */
    public function isAttemptCompleted(int $attemptId): bool
    {
        return QuizAttempt::where('id', $attemptId)
                         ->where('status', 'completed')
                         ->exists();
    }

    /**
     * Verificar acesso à tentativa
     */
    public function canAccessAttempt(int $attemptId, int $userId): bool
    {
        return QuizAttempt::where('id', $attemptId)
                         ->where('user_id', $userId)
                         ->exists();
    }
}
