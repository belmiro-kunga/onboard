<?php

namespace App\Http\Controllers;


use App\Repositories\QuizRepository;use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends BaseController
{
    /**
     * Exibe a lista de quizzes disponíveis.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Buscar todos os quizzes
        $quizzes = Quiz::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Buscar tentativas do usuário
        $userAttempts = QuizAttempt::where('user_id', $user->id)->get();
        
        // Calcular estatísticas
        $stats = [
            'total_quizzes' => $quizzes->count(),
            'completed_quizzes' => $userAttempts->where('completed_at', '!=', null)->count(),
            'average_score' => $userAttempts->where('completed_at', '!=', null)->avg('score') ?? 0,
            'total_attempts' => $userAttempts->count(),
            'success_rate' => $userAttempts->where('completed_at', '!=', null)->where('score', '>=', 70)->count(),
        ];
        
        // Buscar filtros da requisição
        $category = $request->get('category', 'all');
        $difficulty = $request->get('difficulty', 'all');
        $status = $request->get('status', 'all');
        
        // Filtrar quizzes baseado nos parâmetros
        if ($category !== 'all') {
            $quizzes = $quizzes->where('category', $category);
        }
        
        if ($difficulty !== 'all') {
            $quizzes = $quizzes->where('difficulty_level', $difficulty);
        }
        
        // Adicionar status do usuário para cada quiz
        $quizzesWithStatus = $quizzes->map(function ($quiz) use ($user) {
            $attempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->latest()
                ->first();
                
            $status = 'not_started';
            $score = null;
            $attempts = 0;
            
            if ($attempt) {
                $attempts = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->count();
                    
                if ($attempt->completed_at) {
                    $status = $attempt->score >= 70 ? 'completed' : 'failed';
                    $score = $attempt->score;
                } else {
                    $status = 'in_progress';
                }
            }
            
            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'category' => $quiz->category,
                'difficulty_level' => $quiz->difficulty_level,
                'time_limit' => $quiz->time_limit,
                'points_reward' => $quiz->points_reward,
                'status' => $status,
                'score' => $score,
                'attempts' => $attempts,
                'questions_count' => $quiz->questions()->count(),
            ];
        });
        
        return view('quizzes.index', compact('quizzesWithStatus', 'stats', 'category', 'difficulty', 'status'));
    }

    /**
     * Exibe detalhes de um quiz específico.
     */
    public function show(Quiz $quiz): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Buscar tentativas do usuário para este quiz
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $bestAttempt = $attempts->where('completed_at', '!=', null)->sortByDesc('score')->first();
        
        return view('quizzes.show', compact('quiz', 'attempts', 'bestAttempt'));
    }

    /**
     * Inicia uma tentativa de quiz.
     */
    public function start(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = $request->user();
        if ($user === null) {
            return redirect()->route('login');
        }
        
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
        ]);
        return redirect()->route('quizzes.attempt', [$quiz, $attempt]);
    }

    /**
     * Exibe a tela de tentativa de quiz.
     */
    public function attempt(Quiz $quiz, QuizAttempt $attempt): View
    {
        $user = auth()->user();
        
        if (!$user || $attempt->user_id !== $user->id) {
            return redirect()->route('quizzes.index');
        }
        
        $questions = $quiz->questions()->orderBy('order_index')->get();
        return view('quizzes.attempt', compact('quiz', 'attempt', 'questions'));
    }

    /**
     * Submete as respostas do quiz.
     */
    public function submit(Request $request, Quiz $quiz, QuizAttempt $attempt): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user || $attempt->user_id !== $user->id) {
            return redirect()->route('quizzes.index');
        }
        
        // Calcular pontuação
        $totalQuestions = $quiz->questions()->count();
        $correctAnswers = 0;
        
        foreach ($request->input('answers', []) as $questionId => $answer) {
            $question = QuizQuestion::find($questionId);
            if ($question && $question->correct_answer === $answer) {
                $correctAnswers++;
            }
        }
        
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        
        // Atualizar tentativa
        $attempt->update([
            'score' => $score,
            'completed_at' => now(),
            'answers' => $request->input('answers', []),
        ]);
        
        return redirect()->route('quizzes.results', [$quiz, $attempt]);
    }

    /**
     * Exibe os resultados do quiz.
     */
    public function results(Quiz $quiz, QuizAttempt $attempt): View
    {
        $user = auth()->user();
        
        if (!$user || $attempt->user_id !== $user->id) {
            return redirect()->route('quizzes.index');
        }
        
        return view('quizzes.results', compact('quiz', 'attempt'));
    }
}