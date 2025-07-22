<x-layouts.app title="Resultado: {{ $simulado->titulo }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header com Resultado -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 {{ $hasPassed ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
                    <span class="text-3xl">
                        {{ $hasPassed ? '🎉' : '📚' }}
                    </span>
                </div>
                
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                    {{ $hasPassed ? 'Parabéns!' : 'Continue Estudando!' }}
                </h1>
                
                <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-4">
                    {{ $hasPassed ? 'Você foi aprovado no simulado!' : 'Você não atingiu a pontuação mínima.' }}
                </p>
                
                <div class="inline-flex items-center space-x-6 text-sm">
                    <div class="text-center">
                        <div class="text-2xl font-bold {{ $hasPassed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tentativa->score }}%
                        </div>
                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">Pontuação</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ gmdate('H:i:s', $tentativa->tempo_gasto) }}
                        </div>
                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">Tempo gasto</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ $stats['acertos'] ?? 0 }}/{{ $stats['total'] ?? 0 }}
                        </div>
                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">Acertos</div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('simulados.show', $simulado->id) }}" 
                   class="px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors font-medium">
                    📋 Ver Simulado
                </a>

                <a href="{{ route('simulados.report', ['id' => $simulado->id, 'tentativa' => $tentativa->id]) }}" 
                   class="px-6 py-3 bg-hcp-secondary-600 text-white rounded-lg hover:bg-hcp-secondary-700 transition-colors font-medium">
                    📊 Relatório Detalhado
                </a>
                
                @if($hasPassed)
                    <a href="{{ route('certificates.index') }}" 
                       class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        🏆 Ver Certificado
                    </a>
                @endif
            </div>

            <!-- Resumo do Desempenho -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-8">
                <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">Resumo do Desempenho</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gráfico de Acertos -->
                    <div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">Acertos vs. Erros</h3>
                        <div class="relative h-48">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-32 h-32 rounded-full border-8 border-hcp-secondary-200 dark:border-hcp-secondary-700 relative">
                                    <div class="absolute inset-0 rounded-full border-8 border-green-500 dark:border-green-400"
                                         style="clip: rect(0, {{ 32 * ($stats['acertos'] / $stats['total']) }}px, 64px, 0);">
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ round(($stats['acertos'] / $stats['total']) * 100) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute bottom-0 w-full flex justify-around">
                                <div class="text-center">
                                    <div class="inline-block w-4 h-4 bg-green-500 rounded-full mb-1"></div>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                        {{ $stats['acertos'] }} Acertos
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div class="inline-block w-4 h-4 bg-red-500 rounded-full mb-1"></div>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                        {{ $stats['erros'] }} Erros
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estatísticas -->
                    <div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">Estatísticas</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Tempo médio por questão:</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">
                                    {{ gmdate('i:s', $stats['tempo_medio_questao']) }}
                                </span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Sequência de acertos:</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">
                                    {{ $stats['max_sequencia_acertos'] }}
                                </span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Questões não respondidas:</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">
                                    {{ $stats['nao_respondidas'] }}
                                </span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontuação mínima:</span>
                                <span class="font-medium text-hcp-secondary-900 dark:text-white">
                                    {{ $simulado->passing_score }}%
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Gamificação - Pontos e Conquistas -->
            @if($hasPassed)
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-green-200 dark:border-green-800 p-6 mb-8">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">Recompensas</h2>
                    
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <!-- Pontos Ganhos -->
                        <div class="text-center mb-4 md:mb-0">
                            <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Pontos Ganhos</div>
                            <div class="text-3xl font-bold text-hcp-500 points-earned" data-points="{{ $stats['pontos_ganhos'] ?? $simulado->pontos_recompensa }}">
                                +{{ $stats['pontos_ganhos'] ?? $simulado->pontos_recompensa }}
                            </div>
                        </div>
                        
                        <!-- Certificado -->
                        <div class="text-center">
                            <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Certificado</div>
                            <div class="flex space-x-2">
                                <div class="achievement-item relative p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg" 
                                     data-id="simulado_completed" 
                                     data-title="Simulado Completado" 
                                     data-description="Você completou um simulado com sucesso!">
                                    <span class="text-2xl" title="Simulado Completado">🏆</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bônus -->
                        @if(isset($stats['pontos_bonus']) && $stats['pontos_bonus'] > 0)
                            <div class="text-center mt-4 md:mt-0">
                                <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-1">Bônus</div>
                                <div class="text-xl font-bold text-yellow-500">+{{ $stats['pontos_bonus'] }}</div>
                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                    {{ $stats['motivo_bonus'] ?? 'Desempenho excepcional' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Recomendações -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">Próximos Passos</h2>
                
                <div class="space-y-4">
                    @if($hasPassed)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Parabéns pela aprovação! Você pode baixar seu certificado na seção de certificados.
                            </p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Continue praticando com outros simulados para aprimorar ainda mais seus conhecimentos.
                            </p>
                        </div>
                    @else
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Você não atingiu a pontuação mínima de {{ $simulado->passing_score }}%. Revise o relatório detalhado para identificar áreas de melhoria.
                            </p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Recomendamos revisar o material de estudo relacionado a este simulado antes de tentar novamente.
                            </p>
                        </div>
                    @endif
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-hcp-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <p class="ml-3 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Confira o relatório detalhado para ver quais questões você acertou e errou, com explicações para cada resposta.
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('simulados.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver Outros Simulados
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/quiz-gamification.js') }}"></script>
</x-layouts.app>