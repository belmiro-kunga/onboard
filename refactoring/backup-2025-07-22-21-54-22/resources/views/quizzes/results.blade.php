<x-layouts.app title="Resultados - {{ $quiz->title }} - HCP Onboarding">
    <!-- Incluir CSS para feedback e gamificação -->
    <link rel="stylesheet" href="{{ asset('css/quiz-feedback.css') }}">
    
    <style>
        /* Estilos para animações de gamificação */
        .animate-points {
            animation: scaleUp 0.5s ease-out;
        }
        
        .pulse-effect {
            animation: pulse 1s ease-in-out;
        }
        
        .animate-achievement {
            animation: slideInRight 0.5s ease-out;
        }
        
        .animate-streak {
            animation: bounce 0.5s ease-out;
        }
        
        .achievement-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 0.5rem;
            box-shadow: 0 0 15px 5px rgba(255, 215, 0, 0.7);
            animation: glow 2s ease-out;
            z-index: -1;
        }
        
        .fire-emoji {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes scaleUp {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes slideInRight {
            0% { transform: translateX(50px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        @keyframes glow {
            0% { opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 0.8; }
            100% { opacity: 0; }
        }
        
        @keyframes shake {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            50% { transform: rotate(-10deg); }
            75% { transform: rotate(5deg); }
            100% { transform: rotate(0deg); }
        }
    </style>
    
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto quiz-results-container">
            
            <!-- Header com Resultado -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 {{ $attempt->passed ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} quiz-passed">
                    <span class="text-3xl">
                        {{ $attempt->passed ? '🎉' : '📚' }}
                    </span>
                </div>
                
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                    {{ $attempt->passed ? 'Parabéns!' : 'Continue Estudando!' }}
                </h1>
                
                <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-4">
                    {{ $attempt->passed ? 'Você foi aprovado no quiz!' : 'Você não atingiu a pontuação mínima.' }}
                </p>
                
                <div class="inline-flex items-center space-x-6 text-sm">
                    <div class="text-center">
                        <div class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $attempt->score }}%
                        </div>
                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">Pontuação</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ $attempt->formatted_time_spent }}
                        </div>
                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">Tempo gasto</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ $attempt->attempt_number }}
                        </div>
                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">Tentativa</div>
                    </div>
                </div>
            </div>
            
            <!-- Gamificação - Pontos e Conquistas -->
            @if($attempt->passed)
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <!-- Pontos Ganhos -->
                    <div class="text-center mb-4 md:mb-0">
                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Pontos Ganhos</div>
                        <div class="text-3xl font-bold text-hcp-500 points-earned" data-points="{{ $quiz->points_reward }}">
                            +{{ $quiz->points_reward }}
                        </div>
                    </div>
                    
                    <!-- Streak -->
                    <div class="text-center mb-4 md:mb-0">
                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Sequência de Aprovações</div>
                        <div class="text-3xl font-bold text-orange-500 streak-count" data-count="{{ $streak ?? 1 }}">
                            {{ $streak ?? 1 }}
                        </div>
                    </div>
                    
                    <!-- Conquistas -->
                    <div class="text-center">
                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Conquistas</div>
                        <div class="flex space-x-2">
                            @if(isset($achievements) && is_array($achievements) && count($achievements) > 0)
                                @foreach($achievements as $achievement)
                                    <div class="achievement-item relative p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg" 
                                         data-id="{{ $achievement->id }}" 
                                         data-title="{{ $achievement->title }}" 
                                         data-description="{{ $achievement->description }}">
                                        <span class="text-2xl" title="{{ $achievement->title }}">{{ $achievement->icon }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="achievement-item relative p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg" 
                                     data-id="quiz_completed" 
                                     data-title="Quiz Completado" 
                                     data-description="Você completou um quiz com sucesso!">
                                    <span class="text-2xl" title="Quiz Completado">🏆</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('quizzes.show', $quiz->id) }}" 
                   class="px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors font-medium">
                    📋 Ver Quiz
                </a>
                
                @if(!$attempt->passed && $quiz->canUserAttempt(auth()->user()))
                    <a href="{{ route('quizzes.start', $quiz->id) }}" 
                       class="px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors font-medium">
                        🔄 Nova Tentativa
                    </a>
                @endif
                
                @if($certificate)
                    <a href="{{ route('certificates.download', $certificate) }}" 
                       class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                        🏆 Baixar Certificado
                    </a>
                @endif
                
                <a href="{{ route('quizzes.index') }}" 
                   class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                    📚 Outros Quizzes
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revisão das Questões -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                            📝 Revisão das Questões
                        </h2>
                        
                        <div class="space-y-6">
                            @foreach($questions as $index => $question)
                                @php
                                    $result = $question->result;
                                    $isCorrect = $result['is_correct'] ?? false;
                                @endphp
                                
                                <div class="border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg p-4 {{ $isCorrect ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
                                    <!-- Question Header -->
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium mr-3 {{ $isCorrect ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="text-lg {{ $isCorrect ? '✅' : '❌' }}"></span>
                                        </div>
                                        
                                        <span class="text-sm font-medium {{ $isCorrect ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $isCorrect ? 'Correto' : 'Incorreto' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Question Text -->
                                    <h4 class="font-medium text-hcp-secondary-900 dark:text-white mb-3">
                                        {!! nl2br(e($question->question)) !!}
                                    </h4>
                                    
                                    <!-- User Answer -->
                                    <div class="mb-3">
                                        <span class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                            Sua resposta:
                                        </span>
                                        <div class="mt-1 p-2 bg-white dark:bg-hcp-secondary-700 rounded border">
                                            @if($question->question_type === 'multiple_choice')
                                                {{ $question->getOptions()[$question->user_answer] ?? 'Não respondido' }}
                                            @elseif($question->question_type === 'true_false')
                                                {{ $question->user_answer === 'true' ? 'Verdadeiro' : ($question->user_answer === 'false' ? 'Falso' : 'Não respondido') }}
                                            @else
                                                {{ $question->user_answer ?? 'Não respondido' }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Correct Answer -->
                                    @if(!$isCorrect)
                                        <div class="mb-3">
                                            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                Resposta correta:
                                            </span>
                                            <div class="mt-1 p-2 bg-green-50 dark:bg-green-900/30 rounded border border-green-200 dark:border-green-800">
                                                @if($question->question_type === 'multiple_choice')
                                                    @foreach($question->getCorrectAnswer() as $correctKey)
                                                        {{ $question->getOptions()[$correctKey] ?? '' }}
                                                    @endforeach
                                                @elseif($question->question_type === 'true_false')
                                                    {{ $question->getCorrectAnswer()[0] === 'true' ? 'Verdadeiro' : 'Falso' }}
                                                @else
                                                    {{ implode(', ', $question->getCorrectAnswer()) }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Explanation -->
                                    @if($question->explanation)
                                        <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                                            <span class="text-sm font-medium text-blue-900 dark:text-blue-200">
                                                💡 Explicação:
                                            </span>
                                            <p class="mt-1 text-sm text-blue-800 dark:text-blue-300">
                                                {!! nl2br(e($question->explanation)) !!}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Estatísticas -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            📊 Estatísticas
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Questões corretas:</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">
                                    {{ $questions->where('result.is_correct', true)->count() }}/{{ $questions->count() }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontuação mínima:</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ $quiz->passing_score }}%</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos ganhos:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">
                                    {{ $attempt->passed ? $quiz->points_reward : 0 }} pontos
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Recomendações -->
                    @if(count($recommendations) > 0)
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                                💡 Recomendações
                            </h3>
                            
                            <div class="space-y-3">
                                @foreach($recommendations as $recommendation)
                                    <div class="p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                        <h4 class="font-medium text-hcp-secondary-900 dark:text-white mb-1">
                                            {{ $recommendation['title'] }}
                                        </h4>
                                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                            {{ $recommendation['description'] }}
                                        </p>
                                        @if($recommendation['action'])
                                            <a href="{{ $recommendation['action'] }}" 
                                               class="inline-flex items-center text-sm text-hcp-500 hover:text-hcp-600 font-medium">
                                                Ver mais →
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Próximos Passos -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            🎯 Próximos Passos
                        </h3>
                        
                        <div class="space-y-2">
                            <a href="{{ route('quizzes.ranking') }}" 
                               class="block w-full px-4 py-2 bg-purple-500 text-white text-center rounded-lg hover:bg-purple-600 transition-colors text-sm">
                                🏆 Ver Ranking
                            </a>
                            
                            <a href="{{ route('quizzes.index', ['category' => $quiz->category]) }}" 
                               class="block w-full px-4 py-2 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                📂 Quizzes Similares
                            </a>
                            
                            @if($quiz->module)
                                <a href="{{ route('modules.show', $quiz->module) }}" 
                                   class="block w-full px-4 py-2 bg-indigo-500 text-white text-center rounded-lg hover:bg-indigo-600 transition-colors text-sm">
                                    📖 Revisar Módulo
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="quizzes" />
    
    <!-- Incluir script de gamificação -->
    <script src="{{ asset('js/quiz-gamification.js') }}"></script>
    
    <!-- Script para carregar confetti -->
    @if($attempt->passed)
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lançar confetti para celebrar a aprovação
            setTimeout(() => {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
                
                setTimeout(() => {
                    confetti({
                        particleCount: 50,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 }
                    });
                    
                    confetti({
                        particleCount: 50,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 }
                    });
                }, 500);
            }, 1000);
        });
    </script>
    @endif
</x-layouts.app>