<x-layouts.admin title="Questões - {{ $quiz->title }}">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                           class="mr-4 p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                                Questões do Quiz
                            </h1>
                            <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                {{ $quiz->title }}
                            </p>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.quiz-questions.create', $quiz) }}" 
                       class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nova Questão
                    </a>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Questões</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $questions->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Questões Ativas</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $questions->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Tempo Estimado</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $questions->count() * 2 }}min</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos Totais</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $questions->sum('points') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Questões -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white">
                        Lista de Questões
                    </h3>
                </div>

                @if($questions->count() > 0)
                    <div class="divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                        @foreach($questions as $question)
                            <div class="p-6 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-3">
                                            <span class="bg-hcp-primary-100 dark:bg-hcp-primary-900 text-hcp-primary-600 dark:text-hcp-primary-400 text-sm font-medium px-3 py-1 rounded-full mr-3">
                                                {{ $question->order_index }}
                                            </span>
                                            <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wide">
                                                {{ $question->formatted_type }}
                                            </span>
                                            @if(!$question->is_active)
                                                <span class="ml-2 text-xs text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 px-2 py-1 rounded">
                                                    Inativa
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h4 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                                            {{ Str::limit($question->question, 100) }}
                                        </h4>
                                        
                                        <div class="flex items-center space-x-4 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                {{ $question->points }} {{ $question->points === 1 ? 'ponto' : 'pontos' }}
                                            </span>
                                            
                                            @if($question->explanation)
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Tem explicação
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('admin.quiz-questions.preview', [$quiz, $question]) }}" 
                                           class="p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors"
                                           title="Visualizar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.quiz-questions.edit', [$quiz, $question]) }}" 
                                           class="p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.quiz-questions.toggle-active', [$quiz, $question]) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors"
                                                    title="{{ $question->is_active ? 'Desativar' : 'Ativar' }}">
                                                @if($question->is_active)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.quiz-questions.destroy', [$quiz, $question]) }}" class="inline" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta questão?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                    title="Excluir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-hcp-secondary-400 dark:text-hcp-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                            Nenhuma questão criada
                        </h3>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                            Comece criando a primeira questão para este quiz.
                        </p>
                        <a href="{{ route('admin.quiz-questions.create', $quiz) }}" 
                           class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Criar Primeira Questão
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin> 