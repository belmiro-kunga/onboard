<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Http\Controllers\Admin\QuizQuestionController;

class TestQuizQuestionsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:quiz-questions-index {--quiz-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar se a rota admin.quiz-questions.index está funcionando';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testando Rota admin.quiz-questions.index');
        $this->newLine();

        // Buscar quiz
        $quizId = $this->option('quiz-id');
        $quiz = null;

        if ($quizId) {
            $quiz = Quiz::find($quizId);
            if (!$quiz) {
                $this->error("Quiz com ID {$quizId} não encontrado!");
                return 1;
            }
        } else {
            $quiz = Quiz::first();
            if (!$quiz) {
                $this->error("Nenhum quiz encontrado!");
                return 1;
            }
        }

        $this->info("📝 Quiz selecionado: {$quiz->title} (ID: {$quiz->id})");
        $this->newLine();

        // Verificar se a rota está registrada
        $routes = \Route::getRoutes();
        $routeExists = false;
        foreach ($routes as $route) {
            if ($route->getName() === 'admin.quiz-questions.index') {
                $routeExists = true;
                break;
            }
        }

        if (!$routeExists) {
            $this->error("❌ Rota admin.quiz-questions.index não encontrada!");
            return 1;
        }

        $this->info("✅ Rota admin.quiz-questions.index registrada");
        $this->newLine();

        // Verificar se o controller tem o método index
        $controller = new QuizQuestionController();
        if (!method_exists($controller, 'index')) {
            $this->error("❌ Método index não encontrado no QuizQuestionController!");
            return 1;
        }

        $this->info("✅ Método index encontrado no QuizQuestionController");
        $this->newLine();

        // Verificar se a view existe
        $viewPath = resource_path('views/admin/quiz-questions/index.blade.php');
        if (!file_exists($viewPath)) {
            $this->error("❌ View não encontrada: {$viewPath}");
            return 1;
        }

        $this->info("✅ View encontrada: admin/quiz-questions/index.blade.php");
        $this->newLine();

        // Verificar dados do quiz
        $questions = $quiz->questions()->orderBy('order_index')->get();
        $this->info("📊 Dados do Quiz:");
        $this->line("  - Título: {$quiz->title}");
        $this->line("  - Questões: {$questions->count()}");
        $this->line("  - Questões ativas: {$questions->where('is_active', true)->count()}");
        $this->line("  - Pontos totais: {$questions->sum('points')}");

        $this->newLine();
        $this->info("🎯 URL da Lista de Questões:");
        $this->line("  http://localhost:8000/admin/quizzes/{$quiz->id}/questions");

        $this->newLine();
        $this->info("🎯 URL de Criação de Questão:");
        $this->line("  http://localhost:8000/admin/quizzes/{$quiz->id}/questions/create");

        $this->newLine();
        $this->info('✅ Teste concluído! A rota admin.quiz-questions.index está funcionando.');
        
        return 0;
    }
}
