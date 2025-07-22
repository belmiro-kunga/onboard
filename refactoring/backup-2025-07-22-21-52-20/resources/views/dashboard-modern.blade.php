{{-- Dashboard Ultra Moderno HCP - Design System Premium --}}
<x-layouts.employee title="Dashboard">
    <!-- Background com Gradiente Animado -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 transition-all duration-500">
        <!-- Elementos Decorativos de Fundo -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-indigo-400/10 to-pink-400/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <!-- Container Principal -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-8">
            <!-- Header Premium do Dashboard -->
            <div class="mb-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Seção de Boas-vindas -->
                    <div class="flex items-center space-x-6">
                        <!-- Avatar Premium -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-300"></div>
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=00B2FF&background=F3F4F6' }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="relative w-20 h-20 rounded-2xl border-4 border-white dark:border-slate-700 shadow-2xl">
                            <div class="absolute -bottom-2 -right-2 w-7 h-7 bg-green-500 rounded-full border-3 border-white dark:border-slate-900 flex items-center justify-center">
                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                            </div>
                        </div>
                        
                        <!-- Saudação Personalizada -->
                        <div>
                            <div class="flex items-center space-x-3 mb-2">
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                                    Olá, {{ explode(' ', auth()->user()->name)[0] }}!
                                </h1>
                                <div class="text-2xl animate-bounce">👋</div>
                            </div>
                            <p class="text-lg text-slate-600 dark:text-slate-400 font-medium">
                                Pronto para continuar sua jornada de aprendizado?
                            </p>
                            <div class="flex items-center space-x-4 mt-2">
                                <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span>Online agora</span>
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400" id="current-time"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ações Premium do Header -->
                    <div class="flex items-center space-x-4">
                        <!-- Barra de Busca Premium -->
                        <div class="relative group hidden lg:block">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Buscar módulos, conquistas..." 
                                       class="w-80 pl-12 pr-6 py-4 bg-white/90 dark:bg-slate-700/90 backdrop-blur-xl border-0 rounded-2xl text-sm text-slate-700 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-700 transition-all shadow-lg">
                                <x-icon name="search" size="sm" class="absolute left-4 top-4 text-slate-500 dark:text-slate-300" />
                                <div class="absolute right-4 top-3.5">
                                    <kbd class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-600 rounded text-slate-500 dark:text-slate-300">⌘K</kbd>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação Premium -->
                        <div class="flex items-center space-x-3">
                            <!-- Notificações Premium -->
                            <button class="relative group p-4 bg-white/90 dark:bg-slate-700/90 backdrop-blur-xl rounded-2xl hover:bg-white dark:hover:bg-slate-600 transition-all shadow-lg hover:shadow-xl">
                                <x-icon name="bell" size="sm" class="text-slate-600 dark:text-slate-200 group-hover:text-blue-500 transition-colors" />
                                <span class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg animate-pulse">3</span>
                            </button>
                            
                            <!-- Configurações Premium -->
                            <button class="group p-4 bg-white/90 dark:bg-slate-700/90 backdrop-blur-xl rounded-2xl hover:bg-white dark:hover:bg-slate-600 transition-all shadow-lg hover:shadow-xl">
                                <x-icon name="settings" size="sm" class="text-slate-600 dark:text-slate-200 group-hover:text-blue-500 transition-colors group-hover:rotate-90 duration-300" />
                            </button>
                            
                            <!-- Modo Tela Cheia -->
                            <button class="group p-4 bg-white/90 dark:bg-slate-700/90 backdrop-blur-xl rounded-2xl hover:bg-white dark:hover:bg-slate-600 transition-all shadow-lg hover:shadow-xl">
                                <x-icon name="maximize" size="sm" class="text-slate-600 dark:text-slate-200 group-hover:text-blue-500 transition-colors" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Coluna Principal (8/12) -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Progress Card Premium -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-3xl p-8 text-white shadow-2xl">
                        <!-- Elementos Decorativos -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                            <x-icon name="trending-up" size="md" class="text-white" />
                                        </div>
                                        <div>
                                            <h2 class="text-2xl font-bold mb-1">Progresso do Onboarding</h2>
                                            <p class="text-blue-100 font-medium">Continue sua jornada de excelência</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-5xl font-bold mb-1">{{ $progress['overall_percentage'] ?? 75 }}%</div>
                                    <div class="text-blue-100 text-sm font-medium">Concluído</div>
                                    <div class="text-xs text-blue-200 mt-1">{{ $stats['completed_modules'] ?? 8 }}/{{ count($modulesWithProgress ?? []) + 8 }} módulos</div>
                                </div>
                            </div>
                            
                            <!-- Barra de Progresso Premium -->
                            <div class="relative mb-4">
                                <div class="w-full bg-white/20 rounded-full h-4 backdrop-blur-sm">
                                    <div class="bg-gradient-to-r from-white to-blue-100 rounded-full h-4 transition-all duration-2000 shadow-lg relative overflow-hidden" 
                                         style="width: {{ $progress['overall_percentage'] ?? 75 }}%">
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="absolute -top-1 bg-white rounded-full w-6 h-6 shadow-lg transition-all duration-2000 flex items-center justify-center" 
                                     style="left: {{ $progress['overall_percentage'] ?? 75 }}%">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                </div>
                            </div>

                            <!-- Métricas Rápidas -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $stats['total_points'] ?? 1250 }}</div>
                                    <div class="text-xs text-blue-200">Pontos Ganhos</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold">#{{ $stats['ranking_position'] ?? 3 }}</div>
                                    <div class="text-xs text-blue-200">Posição Global</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $stats['completed_modules'] ?? 8 }}</div>
                                    <div class="text-xs text-blue-200">Módulos Concluídos</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de Estatísticas -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Pontos -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                    <x-icon name="star" size="sm" class="text-green-600 dark:text-green-400" />
                                </div>
                                <span class="text-xs text-slate-500 dark:text-slate-400">+12%</span>
                            </div>
                            <div class="text-2xl font-bold text-slate-800 dark:text-white mb-1">
                                {{ $stats['total_points'] ?? 1250 }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Pontos</div>
                        </div>

                        <!-- Nível -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                    <x-icon name="user" size="sm" class="text-purple-600 dark:text-purple-400" />
                                </div>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Nível</span>
                            </div>
                            <div class="text-2xl font-bold text-slate-800 dark:text-white mb-1">
                                {{ $stats['current_level'] ?? 'Avançado' }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Atual</div>
                        </div>

                        <!-- Módulos -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                    <x-icon name="play" size="sm" class="text-blue-600 dark:text-blue-400" />
                                </div>
                                <span class="text-xs text-slate-500 dark:text-slate-400">+3</span>
                            </div>
                            <div class="text-2xl font-bold text-slate-800 dark:text-white mb-1">
                                {{ $stats['completed_modules'] ?? 8 }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Concluídos</div>
                        </div>

                        <!-- Ranking -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                                    <x-icon name="trophy" size="sm" class="text-orange-600 dark:text-orange-400" />
                                </div>
                                <span class="text-xs text-green-500">↑2</span>
                            </div>
                            <div class="text-2xl font-bold text-slate-800 dark:text-white mb-1">
                                #{{ $stats['ranking_position'] ?? 3 }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Posição</div>
                        </div>
                    </div>

                    <!-- Próxima Ação -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                    <x-icon name="arrow-right" size="md" class="text-white" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-1">Próximo Módulo</h3>
                                    <p class="text-indigo-100">{{ $progress['next_module']['title'] ?? 'Segurança da Informação' }}</p>
                                </div>
                            </div>
                            <button class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl font-medium transition-colors">
                                Começar
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Módulos - Design Orgânico -->
                    <div class="relative overflow-hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white/20 dark:border-slate-700/50">
                        <!-- Elementos Decorativos -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full -translate-y-16 translate-x-16 blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-br from-green-400/10 to-blue-400/10 rounded-full translate-y-12 -translate-x-12 blur-2xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Header com Design Fluido -->
                            <div class="p-8 border-b border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-transparent via-slate-50/50 to-transparent dark:from-transparent dark:via-slate-700/20 dark:to-transparent">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <x-icon name="layers" size="sm" class="text-white" />
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Seus Módulos</h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">Jornada de aprendizado personalizada</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Tabs Orgânicas -->
                                    <div class="relative bg-slate-100/80 dark:bg-slate-700/80 backdrop-blur-sm rounded-2xl p-1.5 shadow-inner">
                                        <div class="absolute inset-1 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl opacity-0 transition-opacity duration-300" id="tab-indicator"></div>
                                        <div class="relative flex space-x-1">
                                            <button class="relative px-6 py-3 text-sm font-semibold bg-white dark:bg-slate-600 text-blue-600 dark:text-blue-400 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">
                                                <span class="relative z-10">Ativos</span>
                                            </button>
                                            <button class="relative px-6 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 rounded-xl transition-all duration-300 hover:bg-white/50 dark:hover:bg-slate-600/50">
                                                <span class="relative z-10">Concluídos</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Lista de Módulos com Cards Orgânicos -->
                            <div class="p-8">
                                <div class="space-y-6">
                                    @forelse($modulesWithProgress ?? [] as $module)
                                        <div class="group relative">
                                            <!-- Card Principal com Forma Orgânica -->
                                            <div class="relative overflow-hidden bg-gradient-to-r from-slate-50/80 via-white/90 to-slate-50/80 dark:from-slate-700/50 dark:via-slate-800/80 dark:to-slate-700/50 backdrop-blur-sm rounded-[1.5rem] border border-slate-200/50 dark:border-slate-600/30 shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer group-hover:scale-[1.02] group-hover:-translate-y-1">
                                                <!-- Elementos Decorativos do Card -->
                                                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full -translate-y-10 translate-x-10 blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                                                <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-br from-green-400/10 to-blue-400/10 rounded-full translate-y-8 -translate-x-8 blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                                                
                                                <div class="relative z-10 flex items-center p-6">
                                                    <!-- Ícone Orgânico do Módulo -->
                                                    <div class="relative mr-6">
                                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl blur-md opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                                                        <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                                            <x-icon name="play" size="md" class="text-white group-hover:scale-110 transition-transform duration-300" />
                                                            <!-- Indicador de Status -->
                                                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-3 border-white dark:border-slate-800 flex items-center justify-center shadow-lg">
                                                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Conteúdo Principal -->
                                                    <div class="flex-1">
                                                        <div class="flex items-start justify-between mb-3">
                                                            <div class="flex-1">
                                                                <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                                                    {{ $module['title'] ?? 'Módulo de Exemplo' }}
                                                                </h4>
                                                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                                                                    {{ Str::limit($module['description'] ?? 'Descrição do módulo de aprendizado', 80) }}
                                                                </p>
                                                            </div>
                                                            
                                                            <!-- Badge de Tempo -->
                                                            <div class="ml-4 text-right">
                                                                <div class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-full text-xs font-semibold text-blue-700 dark:text-blue-300 shadow-sm">
                                                                    <x-icon name="clock" size="xs" class="mr-1.5" />
                                                                    {{ $module['estimated_duration'] ?? 25 }} min
                                                                </div>
                                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                                    {{ $module['status'] === 'completed' ? '✅ Concluído' : '🔄 Em andamento' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Barra de Progresso Orgânica -->
                                                        <div class="relative">
                                                            <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 mb-2">
                                                                <span class="font-medium">Progresso</span>
                                                                <span class="font-bold text-blue-600 dark:text-blue-400">{{ $module['completion_percentage'] ?? 65 }}%</span>
                                                            </div>
                                                            <div class="relative h-3 bg-gradient-to-r from-slate-200 via-slate-100 to-slate-200 dark:from-slate-600 dark:via-slate-700 dark:to-slate-600 rounded-full overflow-hidden shadow-inner">
                                                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-blue-600 rounded-full transition-all duration-1000 shadow-lg" 
                                                                     style="width: {{ $module['completion_percentage'] ?? 65 }}%">
                                                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                                                </div>
                                                                <!-- Indicador de Posição -->
                                                                <div class="absolute top-0 h-3 w-1 bg-white rounded-full shadow-lg transition-all duration-1000" 
                                                                     style="left: {{ $module['completion_percentage'] ?? 65 }}%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Hover Overlay -->
                                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-[1.5rem]"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- Estado Vazio Orgânico -->
                                        <div class="text-center py-16">
                                            <div class="relative mx-auto mb-8">
                                                <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-[2rem] flex items-center justify-center mx-auto shadow-2xl">
                                                    <x-icon name="book" size="xl" class="text-slate-400" />
                                                </div>
                                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                                    <x-icon name="plus" size="xs" class="text-white" />
                                                </div>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-3">
                                                Nenhum módulo disponível
                                            </h3>
                                            <p class="text-slate-600 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                                                Os módulos serão disponibilizados em breve. Prepare-se para uma jornada incrível de aprendizado!
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Orgânica (4/12) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Notificações Orgânicas -->
                    <div class="relative overflow-hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[1.5rem] shadow-2xl border border-white/20 dark:border-slate-700/50">
                        <!-- Elementos Decorativos -->
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-red-400/10 to-pink-400/10 rounded-full -translate-y-10 translate-x-10 blur-xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Header Fluido -->
                            <div class="p-6 border-b border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-transparent via-red-50/30 to-transparent dark:from-transparent dark:via-red-900/10 dark:to-transparent">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <x-icon name="bell" size="sm" class="text-white" />
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-800 dark:text-white">Notificações</h3>
                                            <p class="text-xs text-slate-600 dark:text-slate-400">Últimas atualizações</p>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-lg"></div>
                                        <div class="absolute inset-0 w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Lista de Notificações -->
                            <div class="p-6">
                                <div class="space-y-4">
                                    @for($i = 0; $i < 3; $i++)
                                        <div class="group relative">
                                            <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-slate-50/80 via-white/90 to-slate-50/80 dark:from-slate-700/50 dark:via-slate-800/80 dark:to-slate-700/50 backdrop-blur-sm rounded-2xl border border-slate-200/50 dark:border-slate-600/30 hover:shadow-lg transition-all duration-300 cursor-pointer group-hover:scale-[1.02]">
                                                <!-- Ícone da Notificação -->
                                                <div class="relative">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                        <x-icon name="star" size="xs" class="text-white" />
                                                    </div>
                                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-slate-800"></div>
                                                </div>
                                                
                                                <!-- Conteúdo -->
                                                <div class="flex-1">
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        Nova conquista desbloqueada! 🎉
                                                    </p>
                                                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-2">
                                                        Você completou 5 módulos consecutivos e ganhou o badge "Dedicado"
                                                    </p>
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                                        <p class="text-xs text-slate-500 dark:text-slate-500">
                                                            há {{ 2 + $i }} horas
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                
                                <!-- Ver Todas -->
                                <div class="mt-6 text-center">
                                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500/10 to-purple-500/10 text-blue-600 dark:text-blue-400 rounded-xl hover:from-blue-500/20 hover:to-purple-500/20 transition-all duration-300 text-sm font-medium">
                                        Ver todas as notificações
                                        <x-icon name="arrow-right" size="xs" class="ml-2" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conquistas Orgânicas -->
                    <div class="relative overflow-hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[1.5rem] shadow-2xl border border-white/20 dark:border-slate-700/50">
                        <!-- Elementos Decorativos -->
                        <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br from-yellow-400/10 to-orange-400/10 rounded-full -translate-y-12 -translate-x-12 blur-xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Header -->
                            <div class="p-6 border-b border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-transparent via-yellow-50/30 to-transparent dark:from-transparent dark:via-yellow-900/10 dark:to-transparent">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <x-icon name="trophy" size="sm" class="text-white" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 dark:text-white">Conquistas</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">Badges desbloqueados</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Grid de Conquistas -->
                            <div class="p-6">
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach(['star', 'trophy', 'award', 'target', 'zap', 'heart'] as $icon)
                                        <div class="group relative">
                                            <div class="text-center p-4 bg-gradient-to-br from-slate-50/80 via-white/90 to-slate-50/80 dark:from-slate-700/50 dark:via-slate-800/80 dark:to-slate-700/50 backdrop-blur-sm rounded-2xl border border-slate-200/50 dark:border-slate-600/30 hover:shadow-lg transition-all duration-300 cursor-pointer group-hover:scale-105 group-hover:-translate-y-1">
                                                <!-- Badge Icon -->
                                                <div class="relative mx-auto mb-3">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-xl group-hover:rotate-12 transition-transform duration-500">
                                                        <x-icon name="{{ $icon }}" size="sm" class="text-white group-hover:scale-110 transition-transform duration-300" />
                                                    </div>
                                                    <!-- Shine Effect -->
                                                    <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                </div>
                                                
                                                <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                                    Badge {{ $loop->iteration }}
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                                    Nível {{ $loop->iteration }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Ver Todas -->
                                <div class="mt-6 text-center">
                                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500/10 to-orange-500/10 text-orange-600 dark:text-orange-400 rounded-xl hover:from-yellow-500/20 hover:to-orange-500/20 transition-all duration-300 text-sm font-medium">
                                        Ver todas as conquistas
                                        <x-icon name="arrow-right" size="xs" class="ml-2" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ranking Orgânico -->
                    <div class="relative overflow-hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[1.5rem] shadow-2xl border border-white/20 dark:border-slate-700/50">
                        <!-- Elementos Decorativos -->
                        <div class="absolute bottom-0 right-0 w-28 h-28 bg-gradient-to-br from-purple-400/10 to-blue-400/10 rounded-full translate-y-14 translate-x-14 blur-xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Header -->
                            <div class="p-6 border-b border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-transparent via-purple-50/30 to-transparent dark:from-transparent dark:via-purple-900/10 dark:to-transparent">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <x-icon name="users" size="sm" class="text-white" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 dark:text-white">Top Performers</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">Ranking global</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Lista de Ranking -->
                            <div class="p-6">
                                <div class="space-y-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="group relative">
                                            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-slate-50/80 via-white/90 to-slate-50/80 dark:from-slate-700/50 dark:via-slate-800/80 dark:to-slate-700/50 backdrop-blur-sm rounded-2xl border border-slate-200/50 dark:border-slate-600/30 hover:shadow-lg transition-all duration-300 cursor-pointer group-hover:scale-[1.02]">
                                                <!-- Posição -->
                                                <div class="relative">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-sm font-bold shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                        {{ $i }}
                                                    </div>
                                                    @if($i <= 3)
                                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                                            <x-icon name="crown" size="xs" class="text-white" />
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Avatar -->
                                                <div class="relative">
                                                    <img src="https://ui-avatars.com/api/?name=User{{ $i }}&color=00B2FF&background=F3F4F6" 
                                                         alt="User {{ $i }}" 
                                                         class="w-12 h-12 rounded-2xl border-2 border-white dark:border-slate-700 shadow-lg group-hover:scale-105 transition-transform duration-300">
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-slate-800"></div>
                                                </div>
                                                
                                                <!-- Info -->
                                                <div class="flex-1">
                                                    <div class="text-sm font-semibold text-slate-800 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                        Usuário {{ $i }}
                                                    </div>
                                                    <div class="flex items-center space-x-2 mt-1">
                                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                                            {{ 1500 - ($i * 100) }} pontos
                                                        </div>
                                                        <div class="w-1 h-1 bg-slate-400 rounded-full"></div>
                                                        <div class="text-xs text-green-500 font-medium">
                                                            +{{ 50 - ($i * 5) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Badge de Nível -->
                                                <div class="text-right">
                                                    <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-purple-100 to-blue-100 dark:from-purple-900/30 dark:to-blue-900/30 rounded-full text-xs font-semibold text-purple-700 dark:text-purple-300">
                                                        Nv. {{ 10 - $i }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                
                                <!-- Ver Ranking Completo -->
                                <div class="mt-6 text-center">
                                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500/10 to-blue-500/10 text-purple-600 dark:text-purple-400 rounded-xl hover:from-purple-500/20 hover:to-blue-500/20 transition-all duration-300 text-sm font-medium">
                                        Ver ranking completo
                                        <x-icon name="arrow-right" size="xs" class="ml-2" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Dashboard Premium - Interações Avançadas
        document.addEventListener('DOMContentLoaded', function() {
            initDashboard();
        });

        function initDashboard() {
            // Atualizar horário em tempo real
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
            
            // Animar contadores com efeito premium
            animateCounters();
            
            // Animar barras de progresso
            animateProgressBars();
            
            // Configurar busca com atalho de teclado
            setupSearchShortcut();
            
            // Configurar hover effects avançados
            setupHoverEffects();
            
            // Configurar tabs interativas
            setupTabs();
            
            // Configurar notificações em tempo real
            setupRealTimeNotifications();
        }

        // Atualizar horário atual
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Animar contadores com efeito premium
        function animateCounters() {
            const counters = document.querySelectorAll('.text-2xl.font-bold, .text-5xl.font-bold');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.textContent.replace(/\D/g, ''));
                        
                        if (target && !counter.dataset.animated) {
                            counter.dataset.animated = 'true';
                            animateCounter(counter, target);
                        }
                    }
                });
            });
            
            counters.forEach(counter => observer.observe(counter));
        }

        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 60;
            const duration = 2000;
            const stepTime = duration / 60;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Preservar formatação especial (ex: #3, 75%)
                const originalText = element.textContent;
                if (originalText.includes('#')) {
                    element.textContent = '#' + Math.floor(current);
                } else if (originalText.includes('%')) {
                    element.textContent = Math.floor(current) + '%';
                } else {
                    element.textContent = Math.floor(current);
                }
            }, stepTime);
        }

        // Animar barras de progresso
        function animateProgressBars() {
            const progressBars = document.querySelectorAll('[style*="width:"]');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const bar = entry.target;
                        if (!bar.dataset.animated) {
                            bar.dataset.animated = 'true';
                            const width = bar.style.width;
                            bar.style.width = '0%';
                            bar.style.transition = 'width 2s cubic-bezier(0.4, 0, 0.2, 1)';
                            
                            setTimeout(() => {
                                bar.style.width = width;
                            }, 100);
                        }
                    }
                });
            });
            
            progressBars.forEach(bar => observer.observe(bar));
        }

        // Configurar atalho de busca (Cmd/Ctrl + K)
        function setupSearchShortcut() {
            document.addEventListener('keydown', function(e) {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('input[placeholder*="Buscar"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            });
        }

        // Configurar hover effects avançados
        function setupHoverEffects() {
            // Cards com efeito de elevação
            const cards = document.querySelectorAll('.bg-white, .dark\\:bg-slate-800');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Botões com efeito ripple
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        }

        // Configurar tabs interativas
        function setupTabs() {
            const tabButtons = document.querySelectorAll('[class*="px-4 py-2"]');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remover estado ativo de todos os tabs
                    tabButtons.forEach(tab => {
                        tab.classList.remove('bg-white', 'dark:bg-slate-600', 'text-blue-600', 'dark:text-blue-400', 'shadow-sm');
                        tab.classList.add('text-slate-600', 'dark:text-slate-400');
                    });
                    
                    // Ativar tab clicado
                    this.classList.add('bg-white', 'dark:bg-slate-600', 'text-blue-600', 'dark:text-blue-400', 'shadow-sm');
                    this.classList.remove('text-slate-600', 'dark:text-slate-400');
                });
            });
        }

        // Configurar notificações em tempo real (simulação)
        function setupRealTimeNotifications() {
            // Simular chegada de novas notificações
            setInterval(() => {
                const notificationBadge = document.querySelector('.bg-gradient-to-r.from-red-500');
                if (notificationBadge && Math.random() > 0.95) {
                    const currentCount = parseInt(notificationBadge.textContent) || 0;
                    notificationBadge.textContent = currentCount + 1;
                    
                    // Efeito de pulsação
                    notificationBadge.style.animation = 'pulse 1s ease-in-out 3';
                }
            }, 30000); // A cada 30 segundos
        }

        // Adicionar CSS para animações
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }
            
            .animate-delay-100 { animation-delay: 0.1s; }
            .animate-delay-200 { animation-delay: 0.2s; }
            .animate-delay-300 { animation-delay: 0.3s; }
            .animate-delay-400 { animation-delay: 0.4s; }
            .animate-delay-500 { animation-delay: 0.5s; }
        `;
        document.head.appendChild(style);

        // Configurar modo tela cheia
        document.querySelector('[class*="maximize"]')?.parentElement.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });

        // Configurar busca em tempo real
        const searchInput = document.querySelector('input[placeholder*="Buscar"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                // Implementar lógica de busca aqui
                console.log('Buscando por:', query);
            });
        }
    </script>
    @endpush
</x-layouts.employee>