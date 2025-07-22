<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    /**
     * Retorna a lista de quizzes em formato JSON.
     */
    public function index(): JsonResponse
    {
        $quizzes = Quiz::with('module')->get();
        return response()->json($quizzes);
    }

    /**
     * Retorna detalhes de um quiz específico.
     */
    public function show(int $quizId): JsonResponse
    {
        $quiz = Quiz::with(['module', 'questions'])->findOrFail($quizId);
        return response()->json($quiz);
    }
}