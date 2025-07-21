<x-layouts.app title="Iniciar Quiz: {{ $quiz->title }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center">
                    <a href="{{ route('quizzes.index') }}" 
                       class="mr-4 p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            {{ $quiz->title }}
                        </h1>
                        <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            {{ $quiz->description }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quiz Info Card -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-100 text-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-800 dark:bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-900/30 dark:text-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-400">
                                {{ $quiz->formatted_category }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $quiz->difficulty_level === 'basic' ? 'green' : ($quiz->difficulty_level === 'intermediate' ? 'yellow' : 'red') }}-100 text-{{ $quiz->difficulty_level === 'basic' ? 'green' : ($quiz->difficulty_level === 'intermediate' ? 'yellow' : 'red') }}-800 dark:bg-{{ $quiz->difficulty_level === 'basic' ? 'green' : ($quiz->difficulty_level === 'intermediate' ? 'yellow' : 'red') }}-900/30 dark:text-{{ $quiz->difficulty_level === 'basic' ? 'green' : ($quiz->difficulty_level === 'intermediate' ? 'yellow' : 'red') }}-400">
                                {{ $quiz->formatted_difficulty }}
                            </span>
                            @if($quiz->time_limit)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $quiz->time_limit }} min
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                {{ $quiz->points_reward }} pontos
                            </span>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    Nota mínima para aprovação: <strong>{{ $quiz->passing_score }}%</strong>
                                </span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    Tentativas permitidas: <strong>{{ $quiz->max_attempts }}</strong>
                                </span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    Questões: <strong>{{ is_object($quiz->questions) && method_exists($quiz->questions, 'count') ? $quiz->questions->count() : (is_array($quiz->questions) ? count($quiz->questions) : 0) }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        @if($canAttempt)
                            <form method="POST" action="{{ route('quizzes.start', $quiz->id) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-600 hover:bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Iniciar Quiz
                                </button>
                            </form>
                            <p class="mt-2 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Tentativa {{ count($previousAttempts) + 1 }} de {{ $quiz->max_attempts }}
                            </p>
                        @else
                            <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 p-3 rounded-lg">
                                <p class="font-medium">Você atingiu o limite de tentativas</p>
                                <p class="text-sm mt-1">Não é possível fazer mais tentativas neste quiz.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Instruções -->
            @if($quiz->instructions)
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        📝 Instruções
                    </h2>
                    <div class="prose dark:prose-invert max-w-none">
                        {{ $quiz->instructions }}
                    </div>
                </div>
            @endif

            <!-- Tentativas Anteriores -->
            @if(count($previousAttempts) > 0)
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        📊 Suas Tentativas Anteriores
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Tentativa
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Data
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Pontuação
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Tempo
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                                @foreach($previousAttempts as $attempt)
                                    <tr class="hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700/50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                            #{{ $attempt->attempt_number }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : 'Não concluída' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($attempt->completed_at)
                                                <span class="font-medium {{ $attempt->passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $attempt->score }}%
                                                </span>
                                            @else
                                                <span class="text-hcp-secondary-500 dark:text-hcp-secondary-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($attempt->completed_at)
                                                @if($attempt->passed)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                        Aprovado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                        Reprovado
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    Incompleta
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $attempt->formatted_time_spent ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right">
                                            @if($attempt->completed_at)
                                                <a href="{{ route('quizzes.results', [$quiz->id, $attempt->id]) }}" 
                                                   class="text-hcp-primary-600 hover:text-hcp-primary-900 dark:text-hcp-primary-400 dark:hover:text-hcp-primary-300">
                                                    Ver Resultados
                                                </a>
                                            @else
                                                <span class="text-hcp-secondary-500 dark:text-hcp-secondary-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>