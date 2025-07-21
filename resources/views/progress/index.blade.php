<x-layouts.employee title="Progresso">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            📊 Acompanhamento de Progresso
                        </h1>
                        <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Visualize seu desenvolvimento e conquistas no onboarding
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <select id="period-filter" class="px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Última Semana</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Último Mês</option>
                            <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Último Trimestre</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Último Ano</option>
                            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Todo o Período</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Progresso Geral -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Módulos -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">Módulos</h3>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Concluídos</span>
                            <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ $generalProgress['modules']['completed'] }}/{{ $generalProgress['modules']['total'] }}</span>
                        </div>
                        <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: {{ $generalProgress['modules']['percentage'] }}%"></div>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ $generalProgress['modules']['percentage'] }}%</p>
                    </div>
                </div>     
           <!-- Quizzes -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">Quizzes</h3>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Aprovados</span>
                            <span class="font-medium text-hcp-secondary-900 dark:text-white">{{ $generalProgress['quizzes']['passed'] }}/{{ $generalProgress['quizzes']['total'] }}</span>
                        </div>
                        <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $generalProgress['quizzes']['percentage'] }}%"></div>
                        </div>
                        <p class="text-2xl font-bold text-green-600">{{ $generalProgress['quizzes']['percentage'] }}%</p>
                    </div>
                </div>

                <!-- Certificados -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">Certificados</h3>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-yellow-600 mb-1">{{ $generalProgress['certificates'] }}</p>
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Conquistados</p>
                    </div>
                </div>

                <!-- Pontos e Nível -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">Gamificação</h3>
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos</span>
                            <span class="font-bold text-purple-600">{{ number_format($generalProgress['points'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Nível</span>
                            <span class="font-bold text-purple-600">{{ $generalProgress['level'] ?? 'Rookie' }}</span>
                        </div>
                    </div>
                </div>
            </div>      
      <!-- Timeline de Atividades -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        🕒 Timeline de Atividades
                    </h3>
                    
                    @if(count($timeline) > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($timeline as $activity)
                                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors">
                                    <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($activity['icon'] === 'book-open')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            @elseif($activity['icon'] === 'academic-cap')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                {{ $activity['title'] }}
                                            </p>
                                            @if($activity['points'] > 0)
                                                <span class="text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 px-2 py-1 rounded-full">
                                                    +{{ $activity['points'] }} pts
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 truncate">
                                            {{ $activity['description'] }}
                                        </p>
                                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-500 mt-1">
                                            {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Nenhuma atividade registrada no período selecionado.
                            </p>
                        </div>
                    @endif
                </div>    
            <!-- Estatísticas de Quizzes -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        📊 Estatísticas de Quizzes
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $quizStats['total_attempts'] ?? 0 }}</p>
                                <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Tentativas</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ $quizStats['pass_rate'] ?? 0 }}%</p>
                                <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Aprovação</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">{{ $quizStats['average_score'] ?? 0 }}%</p>
                                <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Média</p>
                            </div>
                            <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <p class="text-2xl font-bold text-purple-600">{{ $quizStats['best_score'] ?? 0 }}%</p>
                                <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Melhor</p>
                            </div>
                        </div>
                        
                        <div class="text-center p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <p class="text-lg font-bold text-orange-600">{{ $quizStats['formatted_time'] ?? '0h 0m' }}</p>
                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Tempo Total</p>
                        </div>

                        @if(isset($quizStats['by_category']) && count($quizStats['by_category']) > 0)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white mb-3">Por Categoria</h4>
                                <div class="space-y-2">
                                    @foreach($quizStats['by_category'] as $category => $stats)
                                        <div class="flex items-center justify-between p-2 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded">
                                            <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 capitalize">{{ $category }}</span>
                                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ round($stats['avg_score'] ?? 0, 1) }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>    
        <!-- Progresso por Módulo -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6 mb-8">
                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                    📚 Progresso por Módulo
                </h3>
                
                <div class="space-y-4">
                    @forelse($moduleProgress ?? [] as $module)
                        <div class="border border-hcp-secondary-200 dark:border-hcp-secondary-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-medium text-hcp-secondary-900 dark:text-white">{{ $module['title'] }}</h4>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 capitalize">{{ $module['category'] }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if($module['is_overdue'] ?? false)
                                        <span class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs px-2 py-1 rounded-full animate-pulse">
                                            Atrasado
                                        </span>
                                    @endif
                                    <span class="bg-{{ ($module['status'] ?? 'not_started') === 'completed' ? 'green' : (($module['status'] ?? 'not_started') === 'in_progress' ? 'blue' : 'gray') }}-100 text-{{ ($module['status'] ?? 'not_started') === 'completed' ? 'green' : (($module['status'] ?? 'not_started') === 'in_progress' ? 'blue' : 'gray') }}-800 dark:bg-{{ ($module['status'] ?? 'not_started') === 'completed' ? 'green' : (($module['status'] ?? 'not_started') === 'in_progress' ? 'blue' : 'gray') }}-900/30 dark:text-{{ ($module['status'] ?? 'not_started') === 'completed' ? 'green' : (($module['status'] ?? 'not_started') === 'in_progress' ? 'blue' : 'gray') }}-400 text-xs px-2 py-1 rounded-full">
                                        @if(($module['status'] ?? 'not_started') === 'completed')
                                            ✅ Concluído
                                        @elseif(($module['status'] ?? 'not_started') === 'in_progress')
                                            🔄 Em Progresso
                                        @else
                                            ⏳ Não Iniciado
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Progresso</span>
                                <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ $module['progress_percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2 mb-3">
                                <div class="bg-{{ ($module['status'] ?? 'not_started') === 'completed' ? 'green' : 'blue' }}-500 h-2 rounded-full transition-all duration-300" style="width: {{ $module['progress_percentage'] ?? 0 }}%"></div>
                            </div>
                            
                            <div class="flex justify-between text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                <span>Tempo gasto: {{ $module['formatted_time'] ?? '0h 0m' }}</span>
                                @if($module['completed_at'] ?? false)
                                    <span>Concluído em: {{ \Carbon\Carbon::parse($module['completed_at'])->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Nenhum módulo encontrado.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Insights Comparativos -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                    📈 Insights Comparativos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg">
                        <p class="text-3xl font-bold text-blue-600 mb-2">{{ $insights['comparisons']['modules_vs_average'] ?? 0 }}%</p>
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">vs Média da Empresa</p>
                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-500 mt-1">Módulos Concluídos</p>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg">
                        <p class="text-3xl font-bold text-green-600 mb-2">{{ $insights['comparisons']['quiz_vs_average'] ?? 0 }}%</p>
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">vs Média da Empresa</p>
                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-500 mt-1">Performance em Quizzes</p>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg">
                        <p class="text-3xl font-bold text-purple-600 mb-2">{{ $insights['comparisons']['time_vs_average'] ?? 0 }}%</p>
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">vs Média da Empresa</p>
                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-500 mt-1">Tempo por Módulo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Filtro de período
        document.getElementById('period-filter').addEventListener('change', function() {
            const period = this.value;
            const url = new URL(window.location);
            url.searchParams.set('period', period);
            window.location.href = url.toString();
        });

        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.bg-white, .rounded-xl');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s forwards`;
            });
        });

        // CSS para animações
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-layouts.employee>