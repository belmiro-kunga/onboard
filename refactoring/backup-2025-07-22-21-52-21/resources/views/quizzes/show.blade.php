<x-layouts.app title="{{ $quiz->title }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center mb-6">
                <a href="{{ route('quizzes.index') }}" class="mr-4 p-2 rounded-lg bg-white dark:bg-hcp-secondary-800 shadow-sm border border-hcp-secondary-200 dark:border-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors">
                    ← Voltar
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        {{ $quiz->title }}
                    </h1>
                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        {{ $quiz->formatted_category }} • {{ $quiz->formatted_difficulty }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Quiz Info -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h2 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            📋 Informações do Quiz
                        </h2>
                        
                        <div class="prose dark:prose-invert max-w-none mb-6">
                            <p class="text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                {{ $quiz->description }}
                            </p>
                        </div>

                        @if($quiz->instructions)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                                <h3 class="font-medium text-blue-900 dark:text-blue-200 mb-2">📝 Instruções</h3>
                                <div class="text-sm text-blue-800 dark:text-blue-300">
                                    {!! nl2br(e($quiz->instructions)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Quiz Details -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                    {{ $quiz->questions->count() }}
                                </div>
                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    Questões
                                </div>
                            </div>
                            
                            <div class="text-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                    {{ $quiz->formatted_time_limit }}
                                </div>
                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    Tempo Limite
                                </div>
                            </div>
                            
                            <div class="text-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                    {{ $quiz->passing_score }}%
                                </div>
                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    Nota Mínima
                                </div>
                            </div>
                            
                            <div class="text-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                    {{ $quiz->points_reward }}
                                </div>
                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    Pontos
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            @if($canAttempt)
                                <a href="{{ route('quizzes.start', $quiz->id) }}" 
                                   class="flex-1 px-6 py-3 bg-green-500 text-white text-center rounded-lg hover:bg-green-600 transition-colors font-medium">
                                    🚀 {{ count($userAttempts) > 0 ? 'Nova Tentativa' : 'Iniciar Quiz' }}
                                </a>
                            @else
                                <div class="flex-1 px-6 py-3 bg-hcp-secondary-300 dark:bg-hcp-secondary-600 text-hcp-secondary-500 dark:text-hcp-secondary-400 text-center rounded-lg font-medium cursor-not-allowed">
                                    @if(isset($quiz->user_passed) && $quiz->user_passed)
                                        ✅ Quiz Aprovado
                                    @else
                                        ❌ Tentativas Esgotadas
                                    @endif
                                </div>
                            @endif

                            @if($currentAttempt)
                                <a href="{{ route('quizzes.attempt', [$quiz->id, $currentAttempt]) }}" 
                                   class="flex-1 px-6 py-3 bg-orange-500 text-white text-center rounded-lg hover:bg-orange-600 transition-colors font-medium">
                                    ⏱ Continuar Tentativa
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Previous Attempts -->
                    @if(count($userAttempts) > 0)
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                            <h2 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                                📊 Suas Tentativas
                            </h2>
                            
                            <div class="space-y-3">
                                @foreach($userAttempts as $attempt)
                                    <div class="flex items-center justify-between p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white {{ $attempt->status_color === 'green' ? 'bg-green-500' : ($attempt->status_color === 'red' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                                #{{ $attempt->attempt_number }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-hcp-secondary-900 dark:text-white">
                                                    {{ $attempt->formatted_status }}
                                                </div>
                                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    {{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : 'Em andamento' }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right">
                                            @if($attempt->completed_at)
                                                <div class="text-lg font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $attempt->score }}%
                                                </div>
                                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    {{ $attempt->formatted_time_spent }}
                                                </div>
                                            @else
                                                <div class="text-sm text-orange-600 dark:text-orange-400">
                                                    {{ $attempt->getRemainingTime() }} min restantes
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($attempt->completed_at)
                                            <a href="{{ route('quizzes.results', [$quiz->id, $attempt]) }}" 
                                               class="ml-4 px-3 py-1 bg-hcp-500 text-white rounded text-sm hover:bg-hcp-600 transition-colors">
                                                Ver Resultados
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Your Performance -->
                    @if($bestAttempt)
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                                🎯 Seu Desempenho
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold {{ $bestAttempt->passed ? 'text-green-600' : 'text-red-600' }} mb-2">
                                        {{ $bestAttempt->score }}%
                                    </div>
                                    <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Melhor Pontuação
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ count($userAttempts) }}
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Tentativas
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $bestAttempt->formatted_time_spent }}
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Melhor Tempo
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quiz Statistics -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            📈 Estatísticas Gerais
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Tentativas</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ $statistics->total_attempts }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Aprovação</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ $statistics->pass_rate }}%</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontuação Média</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ $statistics->average_score }}%</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Tempo Médio</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ number_format($statistics->average_time ?? 0, 1) }} min</span>
                            </div>
                        </div>
                    </div>

                    <!-- Related Module -->
                    @if(isset($quiz->module) && $quiz->module)
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                                📚 Módulo Relacionado
                            </h3>
                            
                            <div class="space-y-3">
                                <h4 class="font-medium text-hcp-secondary-900 dark:text-white">
                                    {{ $quiz->module->title }}
                                </h4>
                                <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    {{ $quiz->module->description }}
                                </p>
                                <a href="{{ route('modules.show', $quiz->module->id ?? $quiz->module) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors text-sm">
                                    📖 Revisar Módulo
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                            ⚡ Ações Rápidas
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
                            
                            @if(isset($quiz->user_passed) && $quiz->user_passed)
                                <a href="{{ route('certificates.index') }}" 
                                   class="block w-full px-4 py-2 bg-green-500 text-white text-center rounded-lg hover:bg-green-600 transition-colors text-sm">
                                    🎓 Ver Certificado
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
</x-layouts.app>