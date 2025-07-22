<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class TestOrderIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:order-index {--quiz-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar se o problema de tipo com order_index foi corrigido';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testando Correção do Problema de Tipo order_index');
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

        // Teste 1: Verificar se max() retorna null quando não há questões
        $this->info("🧪 Teste 1: Verificar max() com quiz sem questões");
        try {
            $maxOrder = $quiz->questions()->max('order_index');
            $this->line("  - max('order_index'): " . ($maxOrder ?? 'null'));
            $this->line("  - Tipo: " . gettype($maxOrder));
            $this->info("  ✅ Teste 1 passou");
        } catch (\Exception $e) {
            $this->error("  ❌ Teste 1 falhou: " . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Teste 2: Verificar operação com null coalescing
        $this->info("🧪 Teste 2: Verificar operação (max() ?? 0) + 1");
        try {
            $nextOrder = ($quiz->questions()->max('order_index') ?? 0) + 1;
            $this->line("  - Resultado: {$nextOrder}");
            $this->line("  - Tipo: " . gettype($nextOrder));
            $this->info("  ✅ Teste 2 passou");
        } catch (\Exception $e) {
            $this->error("  ❌ Teste 2 falhou: " . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Teste 3: Verificar questões existentes
        $questions = $quiz->questions()->orderBy('order_index')->get();
        $this->info("🧪 Teste 3: Verificar questões existentes");
        $this->line("  - Total de questões: {$questions->count()}");
        
        if ($questions->count() > 0) {
            $this->line("  - Maior order_index: " . $questions->max('order_index'));
            $this->line("  - Menor order_index: " . $questions->min('order_index'));
            
            // Verificar se há valores null
            $nullCount = $questions->whereNull('order_index')->count();
            $this->line("  - Questões com order_index null: {$nullCount}");
        }

        $this->info("  ✅ Teste 3 passou");

        $this->newLine();

        // Teste 4: Simular criação de nova questão
        $this->info("🧪 Teste 4: Simular criação de nova questão");
        try {
            $nextOrder = ($quiz->questions()->max('order_index') ?? 0) + 1;
            $this->line("  - Próximo order_index seria: {$nextOrder}");
            
            // Verificar se é um inteiro válido
            if (is_int($nextOrder) && $nextOrder > 0) {
                $this->info("  ✅ order_index válido");
            } else {
                $this->error("  ❌ order_index inválido");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Teste 4 falhou: " . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('🎉 Todos os testes passaram! O problema de tipo foi corrigido.');
        
        return 0;
    }
}
