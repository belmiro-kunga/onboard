<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Quiz de RH - Básico
        $hrQuiz = Quiz::create([
            'title' => 'Fundamentos de Recursos Humanos',
            'description' => 'Quiz básico sobre políticas e procedimentos de RH da HCP.',
            'instructions' => 'Leia cada questão com atenção e selecione a melhor resposta. Você tem 3 tentativas para ser aprovado.',
            'passing_score' => 70,
            'max_attempts' => 3,
            'time_limit' => 15,
            'difficulty_level' => 'basic',
            'category' => 'hr',
            'points_reward' => 15,
            'randomize_questions' => true,
            'show_results_immediately' => true,
            'allow_review' => true,
            'generate_certificate' => true,
        ]);

        // Questões do Quiz de RH
        QuizQuestion::create([
            'quiz_id' => $hrQuiz->id,
            'question' => 'Qual é o horário de funcionamento padrão da HCP?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => '8h às 17h',
                'b' => '9h às 18h',
                'c' => '8h30 às 17h30',
                'd' => '7h às 16h'
            ],
            'correct_answer' => ['b'],
            'explanation' => 'O horário padrão da HCP é das 9h às 18h, com flexibilidade para alguns departamentos.',
            'points' => 1,
            'order_index' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $hrQuiz->id,
            'question' => 'Quantos dias de férias anuais um funcionário tem direito?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => '20 dias',
                'b' => '25 dias',
                'c' => '30 dias',
                'd' => '35 dias'
            ],
            'correct_answer' => ['c'],
            'explanation' => 'Todo funcionário tem direito a 30 dias de férias anuais, conforme a legislação trabalhista.',
            'points' => 1,
            'order_index' => 2,
        ]);

        QuizQuestion::create([
            'quiz_id' => $hrQuiz->id,
            'question' => 'É permitido usar redes sociais durante o horário de trabalho?',
            'question_type' => 'true_false',
            'options' => [],
            'correct_answer' => ['false'],
            'explanation' => 'O uso de redes sociais durante o horário de trabalho é desencorajado, exceto para fins profissionais específicos.',
            'points' => 1,
            'order_index' => 3,
        ]);

        // Quiz de TI - Intermediário
        $itQuiz = Quiz::create([
            'title' => 'Segurança da Informação',
            'description' => 'Quiz sobre boas práticas de segurança digital e proteção de dados.',
            'instructions' => 'Este quiz avalia seus conhecimentos sobre segurança da informação. Leia com atenção.',
            'passing_score' => 80,
            'max_attempts' => 2,
            'time_limit' => 20,
            'difficulty_level' => 'intermediate',
            'category' => 'security',
            'points_reward' => 25,
            'randomize_questions' => false,
            'show_results_immediately' => true,
            'allow_review' => true,
            'generate_certificate' => true,
        ]);

        // Questões do Quiz de TI
        QuizQuestion::create([
            'quiz_id' => $itQuiz->id,
            'question' => 'Qual é a principal característica de uma senha segura?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => 'Ter pelo menos 6 caracteres',
                'b' => 'Conter apenas letras',
                'c' => 'Ter pelo menos 8 caracteres com letras, números e símbolos',
                'd' => 'Ser fácil de lembrar'
            ],
            'correct_answer' => ['c'],
            'explanation' => 'Uma senha segura deve ter pelo menos 8 caracteres, combinando letras maiúsculas, minúsculas, números e símbolos.',
            'points' => 2,
            'order_index' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $itQuiz->id,
            'question' => 'O que você deve fazer ao receber um e-mail suspeito?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => 'Abrir todos os anexos para verificar',
                'b' => 'Encaminhar para colegas',
                'c' => 'Deletar imediatamente e reportar ao TI',
                'd' => 'Responder perguntando se é legítimo'
            ],
            'correct_answer' => ['c'],
            'explanation' => 'E-mails suspeitos devem ser deletados imediatamente e reportados ao departamento de TI para análise.',
            'points' => 2,
            'order_index' => 2,
        ]);

        QuizQuestion::create([
            'quiz_id' => $itQuiz->id,
            'question' => 'É seguro usar Wi-Fi público para acessar dados corporativos?',
            'question_type' => 'true_false',
            'options' => [],
            'correct_answer' => ['false'],
            'explanation' => 'Wi-Fi público não é seguro para dados corporativos. Sempre use VPN ou conexão segura.',
            'points' => 2,
            'order_index' => 3,
        ]);

        // Quiz de Processos - Avançado
        $processQuiz = Quiz::create([
            'title' => 'Gestão de Processos Corporativos',
            'description' => 'Quiz avançado sobre metodologias e otimização de processos na HCP.',
            'instructions' => 'Este é um quiz avançado. Demonstre seu conhecimento em gestão de processos.',
            'passing_score' => 85,
            'max_attempts' => 2,
            'time_limit' => 30,
            'difficulty_level' => 'advanced',
            'category' => 'processes',
            'points_reward' => 35,
            'randomize_questions' => true,
            'show_results_immediately' => false,
            'allow_review' => true,
            'generate_certificate' => true,
        ]);

        // Questões do Quiz de Processos
        QuizQuestion::create([
            'quiz_id' => $processQuiz->id,
            'question' => 'Qual metodologia é mais adequada para melhoria contínua de processos?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => 'Waterfall',
                'b' => 'PDCA (Plan-Do-Check-Act)',
                'c' => 'Scrum',
                'd' => 'Kanban'
            ],
            'correct_answer' => ['b'],
            'explanation' => 'O ciclo PDCA é especificamente projetado para melhoria contínua de processos.',
            'points' => 3,
            'order_index' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $processQuiz->id,
            'question' => 'Complete: "Um processo eficiente deve ser _______, mensurável e reproduzível."',
            'question_type' => 'fill_blank',
            'options' => [],
            'correct_answer' => ['documentado', 'padronizado', 'estruturado'],
            'explanation' => 'Processos eficientes devem ser documentados/padronizados para garantir consistência e qualidade.',
            'points' => 3,
            'order_index' => 2,
        ]);

        // Quiz de Cultura Organizacional
        $cultureQuiz = Quiz::create([
            'title' => 'Cultura e Valores HCP',
            'description' => 'Quiz sobre a cultura organizacional, missão, visão e valores da Hemera Capital Partners.',
            'instructions' => 'Teste seus conhecimentos sobre nossa cultura e valores corporativos.',
            'passing_score' => 75,
            'max_attempts' => 3,
            'time_limit' => 10,
            'difficulty_level' => 'basic',
            'category' => 'culture',
            'points_reward' => 20,
            'randomize_questions' => false,
            'show_results_immediately' => true,
            'allow_review' => true,
            'generate_certificate' => false,
        ]);

        // Questões do Quiz de Cultura
        QuizQuestion::create([
            'quiz_id' => $cultureQuiz->id,
            'question' => 'Quais são os valores fundamentais da HCP?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => 'Inovação, Integridade, Excelência',
                'b' => 'Lucro, Crescimento, Expansão',
                'c' => 'Velocidade, Agilidade, Eficiência',
                'd' => 'Tradição, Estabilidade, Conservadorismo'
            ],
            'correct_answer' => ['a'],
            'explanation' => 'Os valores fundamentais da HCP são Inovação, Integridade e Excelência, que guiam todas as nossas decisões e ações.',
            'points' => 2,
            'order_index' => 1,
            'is_active' => true,
        ]);

        QuizQuestion::create([
            'quiz_id' => $cultureQuiz->id,
            'question' => 'Organize os passos do processo de onboarding na ordem correta:',
            'question_type' => 'drag_drop',
            'options' => [
                'a' => 'Boas-vindas e apresentação da empresa',
                'b' => 'Treinamentos específicos do cargo',
                'c' => 'Documentação e compliance',
                'd' => 'Integração com a equipe',
                'e' => 'Avaliação de 90 dias'
            ],
            'correct_answer' => ['a', 'c', 'd', 'b', 'e'],
            'explanation' => 'O processo de onboarding segue uma sequência lógica: boas-vindas, documentação, integração, treinamentos e avaliação.',
            'points' => 3,
            'order_index' => 2,
            'is_active' => true,
        ]);

        QuizQuestion::create([
            'quiz_id' => $cultureQuiz->id,
            'question' => 'Qual é a missão da HCP?',
            'question_type' => 'multiple_choice',
            'options' => [
                'a' => 'Maximizar lucros',
                'b' => 'Ser líder de mercado',
                'c' => 'Criar valor sustentável para investidores e sociedade',
                'd' => 'Expandir globalmente'
            ],
            'correct_answer' => ['c'],
            'explanation' => 'Nossa missão é criar valor sustentável tanto para nossos investidores quanto para a sociedade.',
            'points' => 1,
            'order_index' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $cultureQuiz->id,
            'question' => 'A transparência é um dos valores fundamentais da HCP?',
            'question_type' => 'true_false',
            'options' => [],
            'correct_answer' => ['true'],
            'explanation' => 'Sim, a transparência é um dos nossos valores fundamentais, junto com integridade e excelência.',
            'points' => 1,
            'order_index' => 2,
        ]);

        $this->command->info('Quizzes de demonstração criados com sucesso!');
    }
}