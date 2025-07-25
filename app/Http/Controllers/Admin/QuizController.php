<?php

namespace App\Http\Controllers\Admin;


use App\Repositories\QuizRepository;use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends BaseAdminController
{
    /**
     * Exibe a lista de quizzes para administração.
     */
        public function index(Request $request)
    {
        $items = $this->baseIndex(Quiz::class, $request, ['title', 'description']);
        $stats = $this->generateStats(Quiz::class);
        
        return $this->adminView('quizs.index', compact('items', 'stats'));
    }

    /**
     * Exibe detalhes de um quiz específico.
     */
    public function show(int $quizId): View
    {
        $quiz = Quiz::with(['module', 'questions'])->findOrFail($quizId);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Exibe o formulário de criação de quiz.
     */
    public function create(): View
    {
        return view('admin.quizzes.create');
    }

    /**
     * Armazena um novo quiz.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação e criação do quiz
        $quiz = Quiz::create($request->validated());
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de quiz.
     */
    public function edit(int $quizId): View
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Atualiza um quiz existente.
     */
    public function update(Request $request, int $quizId): RedirectResponse
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->update($request->validated());
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz atualizado com sucesso!');
    }

    /**
     * Remove um quiz.
     */
    public function destroy(int $quizId): RedirectResponse
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz removido com sucesso!');
    }
}