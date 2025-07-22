<x-layouts.app title="Estatísticas - {{ $quiz->title }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="flex items-center mb-6">
                <a href="{{ route('quizzes.show', $quiz->id) }}" class="mr-4 p-2 rounded-lg bg-white dark:bg-hcp-secondary-800 shadow-sm border border-hcp-secondary-200 dark:border-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors">
                    ← Voltar
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        📊 Estatísticas: {{ $quiz->title }}
                    </h1>
                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Análise detalhada do desempenho
                    </p>
                </div>
            </div>

            <!-- Estatísticas Gerais -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <span class="text-2xl">📝</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Tentativas</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $generalStats['total_attempts'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <span class="text-2xl">✅</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Aprovação</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $generalStats['pass_rate'] }}%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                            <span class="text-2xl">📊</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontuação Média</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $generalStats['average_score'] }}%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <span class="text-2xl">⏱️</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Tempo Médio</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ round($generalStats['average_time'] / 60) }}min</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Estatísticas por Questão -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        🎯 Desempenho por Questão
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($questionStats as $index => $questionStat)
                            @php
                                $question = $questionStat['question'];
                                $stats = $questionStat['stats'];
                                $accuracyColor = $stats['accuracy_rate'] >= 80 ? 'green' : ($stats['accuracy_rate'] >= 60 ? 'yellow' : 'red');
                            @endphp
                            
                            <div class="border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-hcp-secondary-900 dark:text-white mb-1">
                                            Questão {{ $index + 1 }}
                                        </h4>
                                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 line-clamp-2">
                                            {{ Str::limit($question->question, 100) }}
                                        </p>
                                    </div>
                                    
                                    <div class="ml-4 text-right">
                                        <div class="text-lg font-bold text-{{ $accuracyColor }}-600 dark:text-{{ $accuracyColor }}-400">
                                            {{ $stats['accuracy_rate'] }}%
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            {{ $stats['correct_answers'] }}/{{ $stats['total_answers'] }}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-2">
                                    <div class="bg-{{ $accuracyColor }}-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $stats['accuracy_rate'] }}%"></div>
                                </div>
                                
                                <div class="mt-2 flex justify-between text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    <span>Dificuldade: {{ $stats['difficulty_score'] }}%</span>
                                    <span>{{ $question->formatted_type }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Estatísticas por Departamento -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        🏢 Desempenho por Departamento
                    </h2>
                    
                    <div class="space-y-4">
                        @forelse($departmentStats as $dept)
                            <div class="border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-hcp-secondary-900 dark:text-white">
                                        {{ strtoupper($dept->department) }}
                                    </h4>
                                    <span class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        {{ $dept->total_attempts }} tentativas
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Média:</span>
                                        <span class="font-medium text-hcp-secondary-900 dark:text-white ml-1">
                                            {{ round($dept->average_score) }}%
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Aprovações:</span>
                                        <span class="font-medium text-green-600 dark:text-green-400 ml-1">
                                            {{ $dept->passed_attempts }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-3 w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-2">
                                    <div class="bg-hcp-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $dept->average_score }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    <div class="text-4xl mb-4">📊</div>
                                    <p>Nenhum dado por departamento disponível</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tentativas Recentes -->
            <div class="mt-8 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6">
                <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                    🕒 Tentativas Recentes
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-hcp-secondary-50 dark:bg-hcp-secondary-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Usuário
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Data
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                            @forelse($recentAttempts as $attempt)
                                <tr class="hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-hcp-500 flex items-center justify-center text-white text-sm font-medium">
                                                    {{ substr($attempt->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                    {{ $attempt->user->name }}
                                                </div>
                                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    {{ $attempt->user->department }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold {{ $attempt->score >= 90 ? 'text-green-600 dark:text-green-400' : ($attempt->score >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                            {{ $attempt->score }}%
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $attempt->passed ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            {{ $attempt->passed ? 'Aprovado' : 'Reprovado' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-hcp-secondary-900 dark:text-white">
                                        {{ $attempt->formatted_time_spent }}
                                    </td>
                                    
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        {{ $attempt->completed_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center">
                                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            <div class="text-4xl mb-4">📝</div>
                                            <p>Nenhuma tentativa recente</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="quizzes" />
</x-layouts.app>