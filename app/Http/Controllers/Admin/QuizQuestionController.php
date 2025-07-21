<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizQuestionController extends Controller
{
    /**
     * Exibe a lista de questões de um quiz.
     */
    public function index(int $quizId): View
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        return view('admin.quiz-questions.index', compact('quiz'));
    }

    /**
     * Exibe detalhes de uma questão específica.
     */
    public function show(int $quizId, int $questionId): View
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = QuizQuestion::findOrFail($questionId);
        return view('admin.quiz-questions.show', compact('quiz', 'question'));
    }

    /**
     * Exibe o formulário de criação de questão.
     */
    public function create(int $quizId): View
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('admin.quiz-questions.create', compact('quiz'));
    }

    /**
     * Armazena uma nova questão.
     */
    public function store(Request $request, int $quizId): RedirectResponse
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = $quiz->questions()->create($request->validated());
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Questão criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição de questão.
     */
    public function edit(int $quizId, int $questionId): View
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = QuizQuestion::findOrFail($questionId);
        return view('admin.quiz-questions.edit', compact('quiz', 'question'));
    }

    /**
     * Atualiza uma questão existente.
     */
    public function update(Request $request, int $quizId, int $questionId): RedirectResponse
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = QuizQuestion::findOrFail($questionId);
        $question->update($request->validated());
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Questão atualizada com sucesso!');
    }

    /**
     * Remove uma questão.
     */
    public function destroy(int $quizId, int $questionId): RedirectResponse
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = QuizQuestion::findOrFail($questionId);
        $question->delete();
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Questão removida com sucesso!');
    }
}