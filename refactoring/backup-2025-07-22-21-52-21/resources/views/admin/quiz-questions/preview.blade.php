<x-layouts.admin title="Preview Questão - {{ $quiz->title }}">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('admin.quiz-questions.index', $quiz) }}" 
                           class="mr-4 p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                                Preview da Questão
                            </h1>
                            <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Quiz: {{ $quiz->title }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.quiz-questions.edit', [$quiz, $question]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Preview da Questão -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm">
                <div class="p-6">
                    <!-- Informações da Questão -->
                    <div class="mb-6 p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <span class="bg-hcp-primary-100 dark:bg-hcp-primary-900 text-hcp-primary-600 dark:text-hcp-primary-400 text-sm font-medium px-3 py-1 rounded-full">
                                    Questão {{ $question->order_index }}
                                </span>
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 uppercase tracking-wide">
                                    {{ $question->formatted_type }}
                                </span>
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    {{ $question->points }} {{ $question->points === 1 ? 'ponto' : 'pontos' }}
                                </span>
                            </div>
                            <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                ID: {{ $question->id }}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">Status:</span>
                                <span class="ml-2 {{ $question->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $question->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">Criada em:</span>
                                <span class="ml-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    {{ $question->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">Atualizada em:</span>
                                <span class="ml-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    {{ $question->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Pergunta -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">
                            Pergunta:
                        </h3>
                        <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                            <p class="text-hcp-secondary-900 dark:text-white whitespace-pre-wrap">
                                {{ $question->question }}
                            </p>
                        </div>
                    </div>

                    <!-- Opções/Respostas -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">
                            Opções de Resposta:
                        </h3>
                        
                        @if($question->question_type === 'multiple_choice' || $question->question_type === 'single_choice')
                            <div class="space-y-3">
                                @foreach($question->getOptions() as $index => $option)
                                    <div class="flex items-center p-3 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg {{ in_array($index, $question->correct_answer) ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-600' : 'bg-white dark:bg-hcp-secondary-700' }}">
                                        <div class="flex-shrink-0 w-8 h-8 {{ in_array($index, $question->correct_answer) ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' : 'bg-hcp-secondary-100 dark:bg-hcp-secondary-600 text-hcp-secondary-600 dark:text-hcp-secondary-400' }} rounded-full flex items-center justify-center font-medium text-sm mr-3">
                                            {{ chr(65 + $index) }}
                                        </div>
                                        <span class="text-hcp-secondary-900 dark:text-white">
                                            {{ $option }}
                                        </span>
                                        @if(in_array($index, $question->correct_answer))
                                            <div class="ml-auto">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Correta
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                        @elseif($question->question_type === 'true_false')
                            <div class="space-y-3">
                                <div class="flex items-center p-3 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg {{ in_array('true', $question->correct_answer) ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-600' : 'bg-white dark:bg-hcp-secondary-700' }}">
                                    <span class="text-hcp-secondary-900 dark:text-white font-medium">✅ Verdadeiro</span>
                                    @if(in_array('true', $question->correct_answer))
                                        <div class="ml-auto">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Correta
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center p-3 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg {{ in_array('false', $question->correct_answer) ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-600' : 'bg-white dark:bg-hcp-secondary-700' }}">
                                    <span class="text-hcp-secondary-900 dark:text-white font-medium">❌ Falso</span>
                                    @if(in_array('false', $question->correct_answer))
                                        <div class="ml-auto">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Correta
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                        @elseif($question->question_type === 'drag_drop')
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">Itens para Arrastar:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($question->drag_drop_items ?? [] as $index => $item)
                                            <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-2 rounded-lg text-sm">
                                                {{ $item }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">Ordem Correta:</h4>
                                    <div class="space-y-2">
                                        @foreach($question->correct_answer ?? [] as $index => $itemIndex)
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ (int)$index + 1 }}º:</span>
                                                <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-2 rounded-lg text-sm">
                                                    {{ $question->drag_drop_items[$itemIndex] ?? 'Item ' . ((int)$itemIndex + 1) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Explicação -->
                    @if($question->explanation)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">
                                Explicação:
                            </h3>
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                                <p class="text-hcp-secondary-900 dark:text-white whitespace-pre-wrap">
                                    {{ $question->explanation }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Estatísticas (se disponíveis) -->
                    @if(method_exists($question, 'getStatistics'))
                        @php $statistics = $question->getStatistics(); @endphp
                        @if($statistics['total_answers'] > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-3">
                                    Estatísticas:
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                        <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $statistics['total_answers'] }}
                                        </div>
                                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            Total de Respostas
                                        </div>
                                    </div>
                                    <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ $statistics['accuracy_rate'] }}%
                                        </div>
                                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            Taxa de Acerto
                                        </div>
                                    </div>
                                    <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                            {{ $statistics['difficulty_score'] }}%
                                        </div>
                                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            Nível de Dificuldade
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <a href="{{ route('admin.quiz-questions.edit', [$quiz, $question]) }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Questão
                        </a>
                        <a href="{{ route('admin.quiz-questions.index', $quiz) }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin> 