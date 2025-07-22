<?php

namespace App\Http\Controllers;

use App\Events\OccupationalQualifierCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OccupationalQualifierController extends BaseController
{
    /**
     * Exibir página do qualificador ocupacional
     */
    public function index()
    {
        $user = Auth::user();
        
        // Dados de exemplo para qualificadores ocupacionais
        $qualifiers = $this->getExampleQualifiers();
        
        return view('occupational-qualifier.index', compact('qualifiers'));
    }

    /**
     * Exibir qualificador específico
     */
    public function show($id)
    {
        $qualifiers = $this->getExampleQualifiers();
        $qualifier = collect($qualifiers)->firstWhere('id', $id);
        
        if (!$qualifier) {
            abort(404);
        }
        
        return view('occupational-qualifier.show', compact('qualifier'));
    }

    /**
     * Processar resultado do qualificador
     */
    public function submitResult(Request $request, $id)
    {
        $request->validate([
            'answers' => 'required|array',
            'time_spent' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $qualifiers = $this->getExampleQualifiers();
        $qualifier = collect($qualifiers)->firstWhere('id', $id);
        
        if (!$qualifier) {
            abort(404);
        }

        try {
            // Calcular pontuação (simulada)
            $score = $this->calculateScore($request->answers, $qualifier);
            
            // Disparar evento de qualificador completado
            event(new OccupationalQualifierCompleted(
                $user,
                $qualifier['id'],
                $qualifier['title'],
                $score,
                $qualifier['category'],
                $qualifier['level']
            ));

            return response()->json([
                'success' => true,
                'score' => $score,
                'passed' => $score >= $qualifier['passing_score'],
                'message' => $score >= $qualifier['passing_score'] 
                    ? 'Parabéns! Você foi aprovado no qualificador!'
                    : 'Continue estudando para melhorar seu desempenho.',
                'redirect' => route('occupational-qualifier.result', ['id' => $id, 'score' => $score])
            ]);

        } catch (\Exception $e) {
            Log::error('Error submitting qualifier result', [
                'user_id' => $user->id,
                'qualifier_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar resultado do qualificador'
            ], 500);
        }
    }

    /**
     * Exibir resultado do qualificador
     */
    public function showResult(Request $request, $id)
    {
        $qualifiers = $this->getExampleQualifiers();
        $qualifier = collect($qualifiers)->firstWhere('id', $id);
        $score = $request->get('score', 0);
        
        if (!$qualifier) {
            abort(404);
        }
        
        return view('occupational-qualifier.result', compact('qualifier', 'score'));
    }

    /**
     * Obter qualificadores de exemplo
     */
    private function getExampleQualifiers()
    {
        return [
            [
                'id' => 1,
                'title' => 'Qualificador de Segurança no Trabalho',
                'description' => 'Avaliação dos conhecimentos sobre normas de segurança, prevenção de acidentes e uso de EPIs.',
                'category' => 'security',
                'level' => 'intermediate',
                'duration' => 45, // minutos
                'questions_count' => 20,
                'passing_score' => 80,
                'points_reward' => 300,
                'status' => 'available',
                'questions' => [
                    [
                        'id' => 1,
                        'question' => 'Qual é o principal objetivo do uso de EPIs?',
                        'options' => [
                            'A) Melhorar a aparência do trabalhador',
                            'B) Proteger a integridade física do trabalhador',
                            'C) Facilitar o trabalho',
                            'D) Reduzir custos da empresa'
                        ],
                        'correct_answer' => 'B'
                    ],
                    [
                        'id' => 2,
                        'question' => 'Em caso de incêndio, qual deve ser a primeira ação?',
                        'options' => [
                            'A) Tentar apagar o fogo',
                            'B) Chamar os bombeiros',
                            'C) Evacuar o local',
                            'D) Pegar os pertences pessoais'
                        ],
                        'correct_answer' => 'C'
                    ]
                ]
            ],
            [
                'id' => 2,
                'title' => 'Qualificador de Compliance e Ética',
                'description' => 'Avaliação sobre políticas de compliance, código de ética e prevenção à corrupção.',
                'category' => 'compliance',
                'level' => 'advanced',
                'duration' => 60,
                'questions_count' => 25,
                'passing_score' => 85,
                'points_reward' => 400,
                'status' => 'available',
                'questions' => [
                    [
                        'id' => 1,
                        'question' => 'O que caracteriza um conflito de interesses?',
                        'options' => [
                            'A) Discordar do chefe',
                            'B) Situação onde interesses pessoais podem influenciar decisões profissionais',
                            'C) Trabalhar em equipe',
                            'D) Ter opiniões diferentes'
                        ],
                        'correct_answer' => 'B'
                    ]
                ]
            ],
            [
                'id' => 3,
                'title' => 'Qualificador Técnico - Desenvolvimento',
                'description' => 'Avaliação técnica para desenvolvedores: lógica de programação, boas práticas e frameworks.',
                'category' => 'technical',
                'level' => 'advanced',
                'duration' => 90,
                'questions_count' => 30,
                'passing_score' => 75,
                'points_reward' => 500,
                'status' => 'available',
                'questions' => [
                    [
                        'id' => 1,
                        'question' => 'Qual é o princípio SOLID que se refere à responsabilidade única?',
                        'options' => [
                            'A) Single Responsibility Principle',
                            'B) Open/Closed Principle',
                            'C) Liskov Substitution Principle',
                            'D) Interface Segregation Principle'
                        ],
                        'correct_answer' => 'A'
                    ]
                ]
            ],
            [
                'id' => 4,
                'title' => 'Qualificador de Atendimento ao Cliente',
                'description' => 'Avaliação sobre técnicas de atendimento, comunicação eficaz e resolução de conflitos.',
                'category' => 'customer_service',
                'level' => 'basic',
                'duration' => 30,
                'questions_count' => 15,
                'passing_score' => 70,
                'points_reward' => 200,
                'status' => 'completed',
                'score' => 88,
                'questions' => []
            ]
        ];
    }

    /**
     * Calcular pontuação (simulada)
     */
    private function calculateScore($answers, $qualifier)
    {
        // Simulação de cálculo de pontuação
        $totalQuestions = count($qualifier['questions']);
        if ($totalQuestions === 0) {
            return rand(70, 95); // Score aleatório para demonstração
        }
        
        $correctAnswers = 0;
        foreach ($answers as $questionId => $answer) {
            $question = collect($qualifier['questions'])->firstWhere('id', $questionId);
            if ($question && $question['correct_answer'] === $answer) {
                $correctAnswers++;
            }
        }
        
        return round(($correctAnswers / $totalQuestions) * 100);
    }
}