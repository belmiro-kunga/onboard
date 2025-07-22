<x-layouts.app title="Relatório: {{ $simulado->titulo }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        Relatório Detalhado
                    </h1>
                    <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        {{ $simulado->titulo }} - {{ $tentativa->created_at->format('d/m/Y') }}
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('simulados.result', ['id' => $simulado->id, 'tentativa' => $tentativa->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Resumo -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $tentativa->score >= $simulado->passing_score ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }} mr-4">
                            <span class="text-2xl font-bold">{{ $tentativa->score }}%</span>
                        </div>
                        <div>
                            <p class="font-medium {{ $tentativa->score >= $simulado->passing_score ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                {{ $tentativa->score >= $simulado->passing_score ? 'Aprovado' : 'Não aprovado' }}
                            </p>
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Mínimo: {{ $simulado->passing_score }}%
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-6">
                        <div class="text-center">
                            <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                {{ $respostas->where('correta', true)->count() }}/{{ $questoes->count() }}
                            </div>
                            <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">Acertos</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                {{ gmdate('H:i:s', $tentativa->tempo_gasto) }}
                            </div>
                            <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">Tempo total</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Questões -->
            <div class="space-y-8">
                @foreach($questoes as $index => $questao)
                    @php
                        $resposta = $respostasPorQuestao[$questao->id] ?? null;
                        $isCorrect = $resposta && $resposta->correta;
                        $isAnswered = $resposta !== null;
                    @endphp
                    
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border {{ $isCorrect ? 'border-green-200 dark:border-green-800' : ($isAnswered ? 'border-red-200 dark:border-red-800' : 'border-yellow-200 dark:border-yellow-800') }} p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <span class="bg-hcp-500 text-white text-sm font-medium px-3 py-1 rounded-full mr-3">
                                    {{ $index + 1 }}
                                </span>
                                
                                @if($isCorrect)
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Resposta Correta
                                    </span>
                                @elseif($isAnswered)
                                    <span class="inline-flex items-center text-red-600 dark:text-red-400">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Resposta Incorreta
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-yellow-600 dark:text-yellow-400">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Não Respondida
                                    </span>
                                @endif
                            </div>
                            
                            @if($isAnswered)
                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    Tempo: {{ gmdate('i:s', $resposta->tempo_questao) }}
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-6">
                            {{ $questao->pergunta }}
                        </h3>
                        
                        <div class="space-y-3 mb-6">
                            @foreach(json_decode($questao->opcoes) as $key => $opcao)
                                @php
                                    $isSelected = $isAnswered && $resposta->resposta === $key;
                                    $isCorrectOption = $key === $questao->resposta_correta;
                                    
                                    if ($isSelected && $isCorrect) {
                                        $optionClass = 'border-green-500 bg-green-50 dark:bg-green-900/20';
                                    } elseif ($isSelected && !$isCorrect) {
                                        $optionClass = 'border-red-500 bg-red-50 dark:bg-red-900/20';
                                    } elseif (!$isSelected && $isCorrectOption) {
                                        $optionClass = 'border-green-500 bg-green-50 dark:bg-green-900/20';
                                    } else {
                                        $optionClass = 'border-hcp-secondary-200 dark:border-hcp-secondary-700';
                                    }
                                @endphp
                                
                                <div class="flex items-center p-3 border rounded-lg {{ $optionClass }}">
                                    <div class="flex-shrink-0 w-5 h-5 border rounded-full mr-3 flex items-center justify-center 
                                        {{ $isSelected ? 'border-hcp-primary-500 bg-hcp-primary-500' : ($isCorrectOption ? 'border-green-500 bg-green-500' : 'border-hcp-secondary-400') }}">
                                        @if($isSelected || $isCorrectOption)
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-hcp-secondary-900 dark:text-white">{{ $opcao }}</span>
                                    
                                    @if($isSelected && !$isCorrect)
                                        <span class="ml-auto text-red-600 dark:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </span>
                                    @elseif($isCorrectOption)
                                        <span class="ml-auto text-green-600 dark:text-green-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="{{ $isCorrect ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' }} border rounded-lg p-4">
                            <h4 class="font-medium {{ $isCorrect ? 'text-green-700 dark:text-green-400' : 'text-blue-700 dark:text-blue-400' }} mb-2">
                                {{ $isCorrect ? 'Muito bem!' : 'Explicação' }}
                            </h4>
                            <p class="text-sm {{ $isCorrect ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                {{ $questao->explicacao ?? 'A resposta correta é a opção ' . $questao->resposta_correta . '.' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botões de Navegação -->
            <div class="mt-8 flex justify-between">
                <a href="{{ route('simulados.result', ['id' => $simulado->id, 'tentativa' => $tentativa->id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar para Resultados
                </a>
                
                <a href="{{ route('simulados.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Outros Simulados
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>