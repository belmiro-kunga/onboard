<x-layouts.app title="Simulados - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        Simulados 📝
                    </h1>
                    <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Teste seus conhecimentos com simulados práticos
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('simulados.history') }}" 
                       class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Meu Histórico
                    </a>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Tentativas</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['total_tentativas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Aprovações</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['tentativas_aprovadas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Média de Score</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ number_format($stats['media_score'] ?? 0, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos Ganhos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['pontos_ganhos'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-4 mb-8">
                <form action="{{ route('simulados.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="categoria" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Categoria</label>
                        <select id="categoria" name="categoria" class="w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-800 text-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring-hcp-primary-500">
                            <option value="">Todas as categorias</option>
                            <option value="technical">Técnico</option>
                            <option value="security">Segurança</option>
                            <option value="compliance">Compliance</option>
                            <option value="customer_service">Atendimento ao Cliente</option>
                            <option value="leadership">Liderança</option>
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-[200px]">
                        <label for="nivel" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Nível</label>
                        <select id="nivel" name="nivel" class="w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-800 text-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring-hcp-primary-500">
                            <option value="">Todos os níveis</option>
                            <option value="basic">Básico</option>
                            <option value="intermediate">Intermediário</option>
                            <option value="advanced">Avançado</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-md transition-colors duration-200">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Simulados -->
            @if(isset($simulados) && count($simulados) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($simulados as $simulado)
                        <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="p-6">
                                <!-- Header do Simulado -->
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                                            {{ $simulado->titulo }}
                                        </h3>
                                        <div class="flex items-center text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs px-2 py-0.5 rounded-full mr-2">
                                                {{ $simulado->categoria_formatada }}
                                            </span>
                                            <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs px-2 py-0.5 rounded-full">
                                                {{ $simulado->nivel_formatado }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-4 line-clamp-2">
                                    {{ $simulado->descricao }}
                                </p>

                                <!-- Informações do Simulado -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $simulado->duracao }} minutos
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $simulado->questoes_count }} questões
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $simulado->passing_score }}% para aprovação
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-hcp-secondary-500 dark:text-hcp-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                            {{ $simulado->pontos_recompensa }} pontos
                                        </span>
                                    </div>
                                </div>

                                <!-- Status do Usuário -->
                                @php
                                    $tentativas = $simulado->tentativas->where('user_id', auth()->id());
                                    $bestAttempt = $tentativas->where('status', 'completed')->sortByDesc('score')->first();
                                    $hasPassed = $bestAttempt && $bestAttempt->score >= $simulado->passing_score;
                                @endphp

                                @if($hasPassed)
                                    <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg mb-4">
                                        <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-700 dark:text-green-400">
                                            Aprovado com {{ $bestAttempt->score }}%
                                        </span>
                                    </div>
                                @elseif($tentativas->where('status', 'completed')->count() > 0)
                                    <div class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg mb-4">
                                        <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                            Melhor tentativa: {{ $bestAttempt->score ?? 0 }}%
                                        </span>
                                    </div>
                                @elseif($tentativas->where('status', 'in_progress')->count() > 0)
                                    <div class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg mb-4">
                                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700 dark:text-blue-400">
                                            Simulado em andamento
                                        </span>
                                    </div>
                                @endif

                                <!-- Ações -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('simulados.show', $simulado->id) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        @if($hasPassed)
                                            Ver Detalhes
                                        @elseif($tentativas->where('status', 'in_progress')->count() > 0)
                                            Continuar
                                        @else
                                            Iniciar Simulado
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-hcp-secondary-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400 dark:text-hcp-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-hcp-secondary-900 dark:text-white mb-2">Nenhum simulado disponível</h3>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6">
                        Não há simulados disponíveis no momento. Verifique novamente mais tarde.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>