<x-layouts.admin title="Preview do Quiz">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Preview: {{ $quiz->title }}</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                            Visualização de como o quiz aparece para os usuários.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                   class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Quiz Info Banner -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Modo Preview
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>Esta é uma visualização de como o quiz aparece para os usuários. As respostas não serão salvas.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Container -->
        <div class="mt-8 max-w-4xl mx-auto">
            <!-- Quiz Header -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</h2>
                    @if($quiz->description)
                        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
                    @endif
                </div>
                
                <!-- Quiz Stats -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Questões:</span>
                            <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $quiz->questions->count() }}</span>
                        </div>
                        @if($quiz->time_limit)
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Tempo:</span>
                                <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $quiz->time_limit }} min</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Pontuação mínima:</span>
                            <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $quiz->passing_score ?? 70 }}%</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="ml-1 font-medium {{ $quiz->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $quiz->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($quiz->questions->count() > 0)
                <!-- Questions -->
                <div class="space-y-6">
                    @foreach($quiz->questions as $index => $question)
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                            <div class="px-6 py-4">
                                <!-- Question Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm font-medium">
                                            {{ (int)$index + 1 }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($question->type) }} • {{ $question->points }} pontos
                                        </span>
                                    </div>
                                    @if(!$question->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Inativa
                                        </span>
                                    @endif
                                </div>

                                <!-- Question Text -->
                                <div class="mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $question->question_text }}
                                    </h3>
                                    @if($question->explanation)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <strong>Explicação:</strong> {{ $question->explanation }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Question Options -->
                                @if($question->type === 'multiple_choice' || $question->type === 'single_choice')
                                    <div class="space-y-3">
                                        @foreach($question->options as $optionIndex => $option)
                                            <div class="flex items-center">
                                                <input type="{{ $question->type === 'multiple_choice' ? 'checkbox' : 'radio' }}" 
                                                       id="question_{{ $question->id }}_option_{{ $optionIndex }}"
                                                       name="question_{{ $question->id }}"
                                                       value="{{ $optionIndex }}"
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                                       disabled>
                                                <label for="question_{{ $question->id }}_option_{{ $optionIndex }}" 
                                                       class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($question->type === 'true_false')
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="radio" 
                                                   id="question_{{ $question->id }}_true"
                                                   name="question_{{ $question->id }}"
                                                   value="true"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                                   disabled>
                                            <label for="question_{{ $question->id }}_true" 
                                                   class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                Verdadeiro
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" 
                                                   id="question_{{ $question->id }}_false"
                                                   name="question_{{ $question->id }}"
                                                   value="false"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                                   disabled>
                                            <label for="question_{{ $question->id }}_false" 
                                                   class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                Falso
                                            </label>
                                        </div>
                                    </div>
                                @elseif($question->type === 'text')
                                    <div>
                                        <textarea rows="3" 
                                                  class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                  placeholder="Digite sua resposta aqui..."
                                                  disabled></textarea>
                                    </div>
                                @elseif($question->type === 'number')
                                    <div>
                                        <input type="number" 
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               placeholder="Digite um número..."
                                               disabled>
                                    </div>
                                @endif

                                <!-- Correct Answer (Preview Mode) -->
                                @if($question->correct_answer)
                                    <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h4 class="text-sm font-medium text-green-800 dark:text-green-200">
                                                    Resposta Correta (Preview)
                                                </h4>
                                                <div class="mt-1 text-sm text-green-700 dark:text-green-300">
                                                    @if(is_array($question->correct_answer))
                                                        @foreach($question->correct_answer as $answerIndex)
                                                            {{ $question->options[$answerIndex] ?? 'Opção ' . ((int)$answerIndex + 1) }}
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    @else
                                                        {{ $question->options[$question->correct_answer] ?? 'Opção ' . ((int)$question->correct_answer + 1) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button (Disabled in Preview) -->
                <div class="mt-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-200 dark:bg-gray-600 cursor-not-allowed"
                            disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Enviar Respostas (Desabilitado no Preview)
                    </button>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Este é apenas um preview. As respostas não serão salvas.
                    </p>
                </div>
            @else
                <!-- No Questions -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma questão</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Este quiz ainda não possui questões.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('admin.quiz-questions.create', $quiz) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Adicionar Questão
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin> 