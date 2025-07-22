<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizAnswerController extends Controller
{
    /**
     * Salvar resposta individual e retornar feedback
     */
    public function saveAnswer(Request $request, $quizId, $attemptId)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:quiz_questions,id',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $quiz = Quiz::findOrFail($quizId);
        $attempt = QuizAttempt::findOrFail($attemptId);
        $question = QuizQuestion::findOrFail($request->question_id);

        // Verificar se a tentativa pertence ao usuário
        if ($attempt->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado a esta tentativa'
            ], 403);
        }

        // Verificar se a questão pertence ao quiz
        if ($question->quiz_id !== $quiz->id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta questão não pertence ao quiz atual'
            ], 400);
        }

        // Verificar se a tentativa está em andamento
        if ($attempt->completed_at) {
            return response()->json([
                'success' => false,
                'message' => 'Esta tentativa já foi concluída'
            ], 400);
        }

        // Verificar se a resposta já foi dada
        $existingAnswer = QuizAttemptAnswer::where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();

        if ($existingAnswer) {
            return response()->json([
                'success' => false,
                'message' => 'Esta questão já foi respondida'
            ], 400);
        }

        // Verificar se a resposta está correta
        $isCorrect = $question->checkAnswer($request->answer);

        // Salvar a resposta
        $answer = QuizAttemptAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_answer' => $request->answer,
            'is_correct' => $isCorrect,
            'feedback_shown_at' => now(),
        ]);

        // Atualizar o progresso da tentativa
        $totalQuestions = $quiz->questions()->active()->count();
        $answeredQuestions = QuizAttemptAnswer::where('attempt_id', $attempt->id)->count();
        $progress = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;

        // Preparar o feedback
        $feedback = [
            'is_correct' => $isCorrect,
            'selected_answer' => $request->answer,
            'correct_answer' => $question->getCorrectAnswer(),
            'explanation' => $question->getExplanationForAnswer($request->answer),
            'question_id' => $question->id
        ];

        // Preparar informações de progresso
        $progressInfo = [
            'current_question' => $answeredQuestions,
            'total_questions' => $totalQuestions,
            'progress_percentage' => $progress,
            'correct_answers' => QuizAttemptAnswer::where('attempt_id', $attempt->id)
                ->where('is_correct', true)
                ->count()
        ];

        return response()->json([
            'success' => true,
            'feedback' => $feedback,
            'progress' => $progressInfo
        ]);
    }

    /**
     * Obter feedback para uma questão específica
     */
    public function getFeedback($quizId, $attemptId, $questionId)
    {
        $user = Auth::user();
        $quiz = Quiz::findOrFail($quizId);
        $attempt = QuizAttempt::findOrFail($attemptId);
        $question = QuizQuestion::findOrFail($questionId);

        // Verificar se a tentativa pertence ao usuário
        if ($attempt->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado a esta tentativa'
            ], 403);
        }

        // Verificar se a questão pertence ao quiz
        if ($question->quiz_id !== $quiz->id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta questão não pertence ao quiz atual'
            ], 400);
        }

        // Buscar a resposta do usuário
        $answer = QuizAttemptAnswer::where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();

        if (!$answer) {
            return response()->json([
                'success' => false,
                'message' => 'Esta questão ainda não foi respondida'
            ], 404);
        }

        // Marcar que o feedback foi mostrado
        $answer->markFeedbackShown();

        // Preparar o feedback
        $feedback = [
            'is_correct' => $answer->is_correct,
            'selected_answer' => $answer->selected_answer,
            'correct_answer' => $question->getCorrectAnswer(),
            'explanation' => $question->getExplanationForAnswer($answer->selected_answer),
            'question_id' => $question->id
        ];

        return response()->json([
            'success' => true,
            'feedback' => $feedback
        ]);
    }

    /**
     * Avançar para a próxima questão
     */
    public function nextQuestion(Request $request, $quizId, $attemptId)
    {
        $user = Auth::user();
        $quiz = Quiz::findOrFail($quizId);
        $attempt = QuizAttempt::findOrFail($attemptId);

        // Verificar se a tentativa pertence ao usuário
        if ($attempt->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado a esta tentativa'
            ], 403);
        }

        // Verificar se a tentativa está em andamento
        if ($attempt->completed_at) {
            return response()->json([
                'success' => false,
                'message' => 'Esta tentativa já foi concluída'
            ], 400);
        }

        // Obter todas as questões do quiz
        $questions = $quiz->questions()->active()->orderBy('order_index')->get();
        
        // Obter questões já respondidas
        $answeredQuestionIds = QuizAttemptAnswer::where('attempt_id', $attempt->id)
            ->pluck('question_id')
            ->toArray();
        
        // Encontrar a próxima questão não respondida
        $nextQuestion = null;
        foreach ($questions as $question) {
            if (!in_array($question->id, $answeredQuestionIds)) {
                $nextQuestion = $question;
                break;
            }
        }

        // Se todas as questões foram respondidas, finalizar o quiz
        if (!$nextQuestion) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Todas as questões foram respondidas',
                'redirect_url' => route('quizzes.results', [$quiz->id, $attempt->id])
            ]);
        }

        return response()->json([
            'success' => true,
            'completed' => false,
            'next_question' => [
                'id' => $nextQuestion->id,
                'question' => $nextQuestion->question,
                'question_type' => $nextQuestion->question_type,
                'options' => $nextQuestion->getOptions(),
            ]
        ]);
    }
}