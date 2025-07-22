<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class TestQuizCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:quiz-creation {--quiz-id=} {--type=multiple_choice}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar criação de questões de quiz de forma simples';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎯 Testando Criação de Questões de Quiz');
        $this->newLine();

        // Buscar quiz ou criar um de teste
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
                $this->error("Nenhum quiz encontrado! Crie um quiz primeiro.");
                return 1;
            }
        }

        $this->info("📝 Quiz selecionado: {$quiz->title}");
        $this->newLine();

        // Mostrar questões existentes
        $existingQuestions = $quiz->questions()->count();
        $this->info("📊 Questões existentes: {$existingQuestions}");
        $this->newLine();

        // Criar questão de teste
        $this->createTestQuestion($quiz);

        $this->newLine();
        $this->info('✅ Teste concluído!');
        
        return 0;
    }

    private function createTestQuestion(Quiz $quiz)
    {
        $type = $this->option('type');
        $nextOrder = ($quiz->questions()->max('order_index') ?? 0) + 1;

        $this->info("🔧 Criando questão de teste (tipo: {$type})...");

        switch ($type) {
            case 'multiple_choice':
                $this->createMultipleChoiceQuestion($quiz, $nextOrder);
                break;
            case 'single_choice':
                $this->createSingleChoiceQuestion($quiz, $nextOrder);
                break;
            case 'true_false':
                $this->createTrueFalseQuestion($quiz, $nextOrder);
                break;
            case 'drag_drop':
                $this->createDragDropQuestion($quiz, $nextOrder);
                break;
            default:
                $this->createMultipleChoiceQuestion($quiz, $nextOrder);
        }
    }

    private function createMultipleChoiceQuestion(Quiz $quiz, int $order)
    {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Qual é a capital do Brasil? (Múltipla escolha)',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => 'São Paulo',
                'b' => 'Rio de Janeiro',
                'c' => 'Brasília',
                'd' => 'Salvador'
            ],
            'correct_answer' => ['c'], // Brasília
            'explanation' => 'Brasília é a capital federal do Brasil desde 1960.',
            'points' => 2,
            'order_index' => $order,
            'is_active' => true,
        ]);

        $this->info("✅ Questão de múltipla escolha criada (ID: {$question->id})");
    }

    private function createSingleChoiceQuestion(Quiz $quiz, int $order)
    {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Qual é o maior planeta do sistema solar? (Escolha única)',
            'question_type' => 'single_choice',
            'options' => [
                'a' => 'Terra',
                'b' => 'Marte',
                'c' => 'Júpiter',
                'd' => 'Saturno'
            ],
            'correct_answer' => ['c'], // Júpiter
            'explanation' => 'Júpiter é o maior planeta do sistema solar.',
            'points' => 1,
            'order_index' => $order,
            'is_active' => true,
        ]);

        $this->info("✅ Questão de escolha única criada (ID: {$question->id})");
    }

    private function createTrueFalseQuestion(Quiz $quiz, int $order)
    {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'A Lua é o satélite natural da Terra? (Verdadeiro/Falso)',
            'question_type' => 'true_false',
            'options' => [],
            'correct_answer' => ['true'], // Verdadeiro
            'explanation' => 'Sim, a Lua é o único satélite natural da Terra.',
            'points' => 1,
            'order_index' => $order,
            'is_active' => true,
        ]);

        $this->info("✅ Questão verdadeiro/falso criada (ID: {$question->id})");
    }

    private function createDragDropQuestion(Quiz $quiz, int $order)
    {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Ordene os planetas do mais próximo ao mais distante do Sol: (Arrastar e Soltar)',
            'question_type' => 'drag_drop',
            'options' => [
                'Mercúrio',
                'Vênus',
                'Terra',
                'Marte'
            ],
            'correct_answer' => [0, 1, 2, 3], // Ordem correta
            'explanation' => 'A ordem correta é: Mercúrio, Vênus, Terra, Marte.',
            'points' => 3,
            'order_index' => $order,
            'is_active' => true,
        ]);

        $this->info("✅ Questão de arrastar e soltar criada (ID: {$question->id})");
    }
}
