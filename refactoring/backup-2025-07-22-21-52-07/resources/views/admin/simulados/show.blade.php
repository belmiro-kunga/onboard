<x-layouts.admin title="Detalhes do Simulado - HCP">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        {{ $simulado->title }}
                    </h1>
                    <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        {{ $simulado->description }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.simulados.edit', $simulado) }}" class="inline-flex items-center px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded-md shadow-sm text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 bg-white dark:bg-hcp-secondary-800 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('admin.simulados.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-hcp-primary-600 hover:bg-hcp-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Status do Simulado -->
            <div class="mb-6">
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $simulado->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                    {{ $simulado->is_active ? 'Ativo' : 'Inativo' }}
                </span>
            </div>

            <!-- Informações e Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Informações do Simulado -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        ℹ️ Informações do Simulado
                    </h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Tempo Limite:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->time_limit }} minutos</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Nota Mínima:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->passing_score }}%</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Máx. Tentativas:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->max_attempts }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Questões:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->questoes->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Criado em:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($simulado->start_date)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Início:</dt>
                                <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->start_date->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                        @if($simulado->end_date)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Término:</dt>
                                <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $simulado->end_date->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Estatísticas -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        📊 Estatísticas
                    </h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Total de Tentativas:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $totalTentativas }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Tentativas Concluídas:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ $tentativasConcluidas }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Média de Notas:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ number_format($mediaNotas, 1) }}%</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">Taxa de Aprovação:</dt>
                            <dd class="text-sm text-hcp-secondary-900 dark:text-white">{{ number_format($taxaAprovacao, 1) }}%</dd>
                        </div>
                    </dl>
                </div>

                <!-- Ações Rápidas -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        🚀 Ações Rápidas
                    </h2>
                    <div class="space-y-3">
                        <a href="{{ route('admin.simulados.questoes', $simulado) }}" class="flex items-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">Gerenciar Questões</span>
                        </a>
                        <a href="{{ route('admin.simulados.atribuicoes', $simulado) }}" class="flex items-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">Atribuir a Usuários</span>
                        </a>
                        <a href="{{ route('admin.simulados.resultados', $simulado) }}" class="flex items-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">Ver Resultados</span>
                        </a>
                        <form action="{{ route('admin.simulados.toggle-active', $simulado) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center w-full p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors">
                                <svg class="w-6 h-6 {{ $simulado->is_active ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($simulado->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @endif
                                </svg>
                                <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ $simulado->is_active ? 'Desativar Simulado' : 'Ativar Simulado' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>