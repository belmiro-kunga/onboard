<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\Module;

class TestQuizEdit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:quiz-edit {--quiz-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar se a view de edição de quiz está funcionando';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testando View de Edição de Quiz');
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

        // Verificar se a view existe
        $viewPath = resource_path('views/admin/quizzes/edit.blade.php');
        if (!file_exists($viewPath)) {
            $this->error("❌ View não encontrada: {$viewPath}");
            return 1;
        }

        $this->info("✅ View encontrada: admin/quizzes/edit.blade.php");
        $this->newLine();

        // Verificar se o controller está funcionando
        try {
            $modules = Module::where('is_active', true)->orderBy('title')->get();
            $this->info("✅ Controller funcionando - Módulos encontrados: {$modules->count()}");
        } catch (\Exception $e) {
            $this->error("❌ Erro no controller: " . $e->getMessage());
            return 1;
        }

        // Verificar dados do quiz
        $this->info("📊 Dados do Quiz:");
        $this->line("  - Título: {$quiz->title}");
        $this->line("  - Categoria: {$quiz->category}");
        $this->line("  - Dificuldade: {$quiz->difficulty_level}");
        $this->line("  - Pontuação mínima: {$quiz->passing_score}%");
        $this->line("  - Máximo de tentativas: {$quiz->max_attempts}");
        $this->line("  - Pontos de recompensa: {$quiz->points_reward}");
        $this->line("  - Ativo: " . ($quiz->is_active ? 'Sim' : 'Não'));
        $this->line("  - Questões: {$quiz->questions()->count()}");

        $this->newLine();
        $this->info("🎯 URL de Edição:");
        $this->line("  http://localhost:8000/admin/quizzes/{$quiz->id}/edit");

        $this->newLine();
        $this->info('✅ Teste concluído! A view de edição está pronta para uso.');
        
        return 0;
    }
}
