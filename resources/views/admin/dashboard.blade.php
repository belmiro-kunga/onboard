<x-layouts.admin title="Dashboard Administrativo - HCP">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                    🎛️ Dashboard Administrativo
                </h1>
                <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    Visão geral do sistema de onboarding HCP
                </p>
            </div>

            <!-- Alertas do Sistema -->
            @if(count($alerts) > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">🚨 Alertas do Sistema</h2>
                    <div class="space-y-3">
                        @foreach($alerts as $alert)
                            <div class="flex items-center p-4 rounded-lg border-l-4 
                                {{ $alert['type'] === 'error' ? 'bg-red-50 border-red-400 dark:bg-red-900/20 dark:border-red-600' : 
                                   ($alert['type'] === 'warning' ? 'bg-yellow-50 border-yellow-400 dark:bg-yellow-900/20 dark:border-yellow-600' : 
                                    'bg-blue-50 border-blue-400 dark:bg-blue-900/20 dark:border-blue-600') }}">
                                <div class="flex-1">
                                    <h3 class="font-medium text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-800 dark:text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-400">
                                        {{ $alert['title'] }}
                                    </h3>
                                    <p class="text-sm text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-700 dark:text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-300">
                                        {{ $alert['message'] }}
                                    </p>
                                </div>
                                <a href="{{ $alert['action'] }}" class="ml-4 px-3 py-1 bg-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-600 text-white text-sm rounded hover:bg-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-700 transition-colors">
                                    Ver Detalhes
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Estatísticas Gerais -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Usuários</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
                            <p class="text-xs text-green-600">+{{ $stats['new_users_this_month'] }} este mês</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Conclusão</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['average_completion_rate'] }}%</p>
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">Média geral</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Quizzes Ativos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['total_quizzes'] }}</p>
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ number_format($stats['total_quiz_attempts']) }} tentativas</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Certificados</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ number_format($stats['total_certificates']) }}</p>
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">Emitidos</p>
                        </div>
                    </div>
                </div>
            </div>        
    <!-- Estatísticas de Quizzes e Atividade Recente -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Estatísticas de Quizzes -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        📊 Estatísticas de Quizzes
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ $quizStats['pass_rate'] }}%</p>
                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Aprovação</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ $quizStats['average_score'] }}%</p>
                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontuação Média</p>
                        </div>
                    </div>

                    @if(count($quizStats['by_category']) > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white mb-3">Performance por Categoria</h4>
                            <div class="space-y-2">
                                @foreach($quizStats['by_category'] as $category => $stats)
                                    <div class="flex items-center justify-between p-2 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded">
                                        <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 capitalize">{{ $category }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ round($stats['avg_score'], 1) }}%</span>
                                            <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">({{ $stats['attempts'] }})</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(count($quizStats['difficult_quizzes']) > 0)
                        <div>
                            <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white mb-3">Quizzes Mais Difíceis</h4>
                            <div class="space-y-2">
                                @foreach($quizStats['difficult_quizzes'] as $quiz)
                                    <div class="flex items-center justify-between p-2 bg-red-50 dark:bg-red-900/20 rounded">
                                        <span class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 truncate">{{ $quiz['title'] }}</span>
                                        <span class="text-sm font-medium text-red-600">{{ round($quiz['avg_score'], 1) }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Atividade Recente -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        🕒 Atividade Recente
                    </h3>
                    
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors">
                                <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($activity['icon'] === 'user-plus')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        @elseif($activity['icon'] === 'academic-cap')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                        {{ $activity['title'] }}
                                    </p>
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
                </div>
            </div>

            <!-- Usuários Ativos e Métricas de Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Usuários Ativos -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                            👥 Usuários Ativos
                        </h3>
                        <a href="{{ route('admin.users.index') }}" class="text-hcp-primary-600 hover:text-hcp-primary-700 text-sm font-medium">
                            Ver todos →
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($activeUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-hcp-secondary-900 dark:text-white">{{ $user['name'] }}</p>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ $user['department'] }}</p>
                                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-500">
                                        Último login: {{ \Carbon\Carbon::parse($user['last_login'])->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-purple-600">{{ number_format($user['total_points']) }} pts</p>
                                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ $user['level'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Métricas de Performance -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                        📈 Métricas de Performance
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ $performanceMetrics['growth_rate'] }}%</p>
                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Crescimento</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ $performanceMetrics['engagement_rate'] }}%</p>
                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Engajamento</p>
                        </div>
                    </div>

                    <!-- Gráfico Simples de Atividade -->
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white mb-3">Atividade dos Últimos 7 Dias</h4>
                        <div class="flex items-end space-x-1 h-20">
                            @foreach(array_slice($performanceMetrics['daily_stats'], -7) as $day)
                                <div class="flex-1 bg-hcp-primary-200 dark:bg-hcp-primary-800 rounded-t" 
                                     style="height: {{ max(10, ($day['quiz_attempts'] * 5)) }}px"
                                     title="{{ $day['formatted_date'] }}: {{ $day['quiz_attempts'] }} tentativas">
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-2">
                            @foreach(array_slice($performanceMetrics['daily_stats'], -7) as $day)
                                <span>{{ $day['formatted_date'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Links Rápidos -->
            <div class="mt-8 bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                    🚀 Ações Rápidas
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.quizzes.create') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Criar Quiz</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-700 dark:text-green-300">Gerenciar Usuários</span>
                    </a>
                    
                    <a href="{{ route('admin.quizzes.index') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Ver Quizzes</span>
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Relatórios</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>