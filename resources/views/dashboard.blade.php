{{-- Dashboard Interativo HCP - Mobile App Experience --}}
<x-layouts.employee title="Dashboard">
    <!-- Mobile App Header - Apenas para mobile -->
    <div class="md:hidden bg-white dark:bg-hcp-secondary-800 border-b border-hcp-secondary-200 dark:border-hcp-secondary-600 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-3">
            <!-- Avatar e saudação -->
            <div class="flex items-center space-x-3">
                <div class="relative" x-data="{ profileMenuOpen: false }" x-cloak>
                    <button @click="profileMenuOpen = !profileMenuOpen" 
                            onclick="toggleProfileMenu(this)"
                            class="relative focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2 rounded-full">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-10 h-10 rounded-full border-2 border-hcp-500 cursor-pointer hover:border-hcp-400 transition-colors">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-hcp-success-500 rounded-full border-2 border-white dark:border-hcp-secondary-800"></div>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="profileMenuOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="profileMenuOpen = false"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600 z-50">
                        
                        <!-- Header do menu -->
                        <div class="px-4 py-3 border-b border-hcp-secondary-200 dark:border-hcp-secondary-600">
                            <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ auth()->user()->email }}</p>
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">{{ auth()->user()->department }}</p>
                        </div>
                        
                        <!-- Opções do menu -->
                        <div class="py-1">
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                <x-icon name="home" size="sm" class="mr-3" />
                                Dashboard
                            </a>
                            <a href="{{ route('gamification.dashboard') }}" 
                               class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                <x-icon name="star" size="sm" class="mr-3" />
                                Gamificação
                            </a>
                            <a href="{{ route('notifications.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                <x-icon name="bell" size="sm" class="mr-3" />
                                Notificações
                            </a>
                            <div class="border-t border-hcp-secondary-200 dark:border-hcp-secondary-600 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-hcp-error-600 dark:text-hcp-error-400 hover:bg-hcp-error-50 dark:hover:bg-hcp-error-900/20 transition-colors">
                                    <x-icon name="log-out" size="sm" class="mr-3" />
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                        Olá, {{ explode(' ', auth()->user()->name)[0] }}!
                    </h1>
                    <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400" id="greeting-time"></p>
                </div>
            </div>

            <!-- Ações do header mobile -->
            <div class="flex items-center space-x-2">
                <!-- Notificações -->
                <button class="relative p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors rounded-full hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 touch-target">
                    <x-icon name="bell" size="sm" />
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-hcp-error-500 rounded-full animate-pulse"></span>
                </button>

                <!-- Menu/Configurações -->
                <button class="p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors rounded-full hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 touch-target">
                    <x-icon name="settings" size="sm" />
                </button>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="min-h-screen bg-gradient-to-br from-hcp-secondary-50 via-hcp-secondary-100 to-hcp-accent-50 dark:from-hcp-secondary-900 dark:via-hcp-secondary-800 dark:to-hcp-secondary-900 pb-20 md:pb-0 border-4 border-red-500">
        <!-- Mobile: Pull to refresh indicator -->
        <div id="pull-to-refresh" class="hidden md:hidden fixed top-20 left-1/2 transform -translate-x-1/2 z-40 bg-white dark:bg-hcp-secondary-800 rounded-full px-4 py-2 shadow-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 border-2 border-hcp-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Atualizando...</span>
            </div>
        </div>

        <div class="px-4 sm:px-6 lg:px-8 py-4 md:py-6 lg:py-8">
            <!-- INDICADOR DE MUDANÇA APLICADA -->
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 text-center font-bold text-lg shadow-lg">
                🎉 LAYOUT DO DASHBOARD MODIFICADO COM SUCESSO!
            </div>
            
            <!-- Saudação personalizada com avatar (Desktop) -->
            <div class="hidden md:block mb-8 animate-fade-in-down">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="relative" x-data="{ profileMenuOpen: false }">
                        <button @click="profileMenuOpen = !profileMenuOpen" 
                                class="relative focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2 rounded-2xl">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-16 h-16 rounded-2xl border-4 border-white dark:border-hcp-secondary-700 shadow-hcp-lg cursor-pointer hover:border-hcp-400 transition-colors">
                            <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-hcp-success-500 rounded-full border-4 border-white dark:border-hcp-secondary-800 flex items-center justify-center">
                                <x-icon name="check" size="xs" class="text-white" />
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="profileMenuOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             @click.away="profileMenuOpen = false"
                             class="absolute left-0 mt-2 w-48 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600 z-50">
                            
                            <!-- Header do menu -->
                            <div class="px-4 py-3 border-b border-hcp-secondary-200 dark:border-hcp-secondary-600">
                                <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <!-- Opções do menu -->
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                    <x-icon name="home" size="sm" class="mr-3" />
                                    Dashboard
                                </a>
                                <a href="{{ route('gamification.dashboard') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                    <x-icon name="user" size="sm" class="mr-3" />
                                    Gamificação
                                </a>
                                <a href="{{ route('notifications.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                    <x-icon name="bell" size="sm" class="mr-3" />
                                    Notificações
                                </a>
                                <div class="border-t border-hcp-secondary-200 dark:border-hcp-secondary-600 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-hcp-error-600 dark:text-hcp-error-400 hover:bg-hcp-error-50 dark:hover:bg-hcp-error-900/20 transition-colors">
                                        <x-icon name="log-out" size="sm" class="mr-3" />
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-1">
                            <span id="greeting-desktop"></span>, {{ auth()->user()->name }}! 👋
                        </h2>
                        <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-300">
                            Pronto para mais uma etapa da sua jornada?
                        </p>
                    </div>
                </div>
            </div>

            <!-- Layout Principal Reorganizado -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 lg:gap-8">
                <!-- Coluna Principal (3/4 da largura) -->
                <div class="xl:col-span-3 space-y-6 lg:space-y-8">
                    <!-- Progress Bar Principal -->
                    <div class="animate-fade-in-up animate-delay-100">
                        <x-card padding="lg" class="bg-gradient-to-r from-hcp-500 via-hcp-600 to-hcp-accent-500 text-white border-0 shadow-hcp-xl">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-white/90">Progresso do Onboarding</h3>
                                <span class="text-3xl font-bold">{{ $progress['overall_percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="relative mb-3">
                                <div class="w-full bg-white/20 rounded-full h-4">
                                    <div class="bg-white rounded-full h-4 transition-all duration-1000 ease-out progress-bar shadow-lg" 
                                         style="width: {{ $progress['overall_percentage'] ?? 0 }}%"></div>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full animate-shimmer"></div>
                            </div>
                            <p class="text-white/80 text-sm">
                                Continue assim! Você está indo muito bem 🚀
                            </p>
                        </x-card>
                    </div>

                    <!-- Cards de Estatísticas -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 animate-fade-in-up animate-delay-200">
                        <!-- Pontos -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-200" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-success-400 to-hcp-success-600 rounded-2xl flex items-center justify-center mx-auto mb-4 animate-bounce-gentle shadow-hcp-lg">
                                <x-icon name="star" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2 counter" data-target="{{ $stats['total_points'] ?? 0 }}">
                                0
                            </h3>
                            <p class="text-sm lg:text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 font-medium">
                                Pontos
                            </p>
                        </x-card>

                        <!-- Nível -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-300" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-accent-400 to-hcp-accent-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-hcp-lg">
                                <x-icon name="user" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-lg lg:text-xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                                {{ $stats['current_level'] ?? 'Iniciante' }}
                            </h3>
                            <p class="text-sm lg:text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 font-medium">
                                Nível
                            </p>
                        </x-card>

                        <!-- Módulos -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-400" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-info-400 to-hcp-info-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-hcp-lg">
                                <x-icon name="play" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2 counter" data-target="{{ $stats['completed_modules'] ?? 0 }}">
                                0
                            </h3>
                            <p class="text-sm lg:text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 font-medium">
                                Concluídos
                            </p>
                        </x-card>

                        <!-- Ranking -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-500" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-warning-400 to-hcp-warning-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-hcp-lg">
                                <x-icon name="users" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                                #<span class="counter" data-target="{{ $stats['ranking_position'] ?? 7 }}">0</span>
                            </h3>
                            <p class="text-sm lg:text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 font-medium">
                                Posição
                            </p>
                        </x-card>
                    </div>

                    <!-- Próxima Ação Recomendada -->
                    <div class="animate-fade-in-up animate-delay-600">
                        <x-card class="bg-gradient-to-r from-hcp-accent-500 via-hcp-accent-600 to-hcp-primary-500 text-white border-0 hover-lift shadow-hcp-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center animate-pulse shadow-lg">
                                        <x-icon name="arrow-right" size="lg" class="text-white" />
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white mb-2">Próxima Recomendação</h3>
                                        <p class="text-white/80 text-base">{{ $progress['next_module']['title'] ?? 'Introdução à Cultura HCP' }}</p>
                                    </div>
                                </div>
                                <button class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-hcp-lg transition-all duration-200 hover:scale-105 active:scale-95 shadow-lg">
                                    <span class="hidden sm:inline font-medium">Começar</span>
                                    <x-icon name="arrow-right" size="sm" class="sm:hidden" />
                                </button>
                            </div>
                        </x-card>
                    </div>

                    <!-- Módulos Ativos -->
                    <div class="animate-fade-in-left animate-delay-700">
                        <x-card padding="lg">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-semibold text-hcp-secondary-900 dark:text-white">
                                    Suas Missões Ativas
                                </h3>
                                <button class="text-hcp-500 hover:text-hcp-400 text-sm font-medium transition-colors">
                                    Ver todas
                                </button>
                            </div>
                            
                            <div class="space-y-4" id="modules-container">
                                @forelse($modulesWithProgress as $module)
                                    <div class="module-card group cursor-pointer" data-module="{{ $module['id'] }}">
                                        <div class="flex items-center p-6 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-2xl hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-all duration-200 hover:scale-[1.02] hover:shadow-hcp-lg border border-hcp-secondary-200/50 dark:border-hcp-secondary-600/50">
                                            <div class="relative mr-6">
                                                <div class="w-16 h-16 bg-gradient-to-br from-hcp-500 to-hcp-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200 shadow-hcp-md">
                                                    <x-icon name="play" size="lg" class="text-white" />
                                                </div>
                                                @if($module['status'] === 'completed')
                                                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-hcp-success-500 rounded-full border-3 border-white dark:border-hcp-secondary-700 animate-pulse shadow-lg"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white group-hover:text-hcp-500 dark:group-hover:text-hcp-400 transition-colors mb-2">
                                                    {{ $module['title'] }}
                                                </h4>
                                                <p class="text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-3">
                                                    {{ $module['description'] }}
                                                </p>
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-1 bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-3">
                                                        <div class="bg-hcp-success-500 h-3 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $module['completion_percentage'] }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ $module['completion_percentage'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="text-right ml-4">
                                                @if($module['status'] === 'completed')
                                                    <div class="text-base font-semibold text-hcp-success-500 mb-2">Concluído</div>
                                                @elseif($module['status'] === 'in_progress')
                                                    <div class="text-base font-semibold text-hcp-success-500 mb-2">Em andamento</div>
                                                @elseif($module['is_available'])
                                                    <div class="text-base font-semibold text-hcp-primary-500 mb-2">Disponível</div>
                                                @else
                                                    <div class="text-base font-semibold text-hcp-secondary-500 dark:text-hcp-secondary-400 mb-2">Bloqueado</div>
                                                @endif
                                                <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ $module['estimated_duration'] ?? '30' }} min</div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <x-icon name="play" size="lg" class="text-hcp-secondary-500" />
                                        </div>
                                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">Nenhum módulo disponível</h3>
                                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Os módulos serão disponibilizados em breve.</p>
                                    </div>
                                @endforelse
                            </div>
                        </x-card>
                    </div>
                </div>

                <!-- Sidebar (1/4 da largura) -->
                <div class="space-y-6 lg:space-y-8 animate-fade-in-right animate-delay-800">
                    <!-- Notificações -->
                    <x-card padding="lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                                Notificações
                            </h3>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="w-3 h-3 bg-hcp-error-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        <div class="space-y-4">
                            @forelse(auth()->user()->notifications()->latest()->limit(3)->get() as $notification)
                                <div class="flex items-start space-x-4 p-4 bg-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-50 dark:bg-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-900/20 rounded-2xl border-l-4 border-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-500 shadow-sm">
                                    <x-icon name="{{ $notification->type === 'achievement_earned' ? 'star' : 'bell' }}" size="md" class="text-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-500 mt-1" />
                                    <div class="flex-1">
                                        <p class="text-base font-semibold text-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-700 dark:text-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-300">
                                            {{ $notification->title }}
                                        </p>
                                        <p class="text-sm text-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-600 dark:text-hcp-{{ $notification->type === 'achievement_earned' ? 'success' : 'info' }}-400 mt-1">
                                            {{ $notification->message }}
                                        </p>
                                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-2">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <x-icon name="bell" size="lg" class="text-hcp-secondary-400 mx-auto mb-2" />
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                        Nenhuma notificação
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </x-card>

                    <!-- Conquistas Recentes -->
                    <x-card padding="lg">
                        <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                            Conquistas
                        </h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-2xl hover:scale-105 transition-transform duration-200 cursor-pointer shadow-sm">
                                <div class="w-12 h-12 bg-hcp-gradient rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-hcp-md">
                                    <x-icon name="star" size="sm" class="text-white" />
                                </div>
                                <p class="text-sm font-semibold text-hcp-secondary-900 dark:text-white">Primeiro Passo</p>
                            </div>
                            <div class="text-center p-4 bg-hcp-secondary-100 dark:bg-hcp-secondary-600 rounded-2xl opacity-50">
                                <div class="w-12 h-12 bg-hcp-secondary-300 dark:bg-hcp-secondary-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <x-icon name="star-outline" size="sm" class="text-hcp-secondary-500" />
                                </div>
                                <p class="text-sm font-semibold text-hcp-secondary-600 dark:text-hcp-secondary-400">Explorador</p>
                            </div>
                            <div class="text-center p-4 bg-hcp-secondary-100 dark:bg-hcp-secondary-600 rounded-2xl opacity-50">
                                <div class="w-12 h-12 bg-hcp-secondary-300 dark:bg-hcp-secondary-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <x-icon name="star-outline" size="sm" class="text-hcp-secondary-500" />
                                </div>
                                <p class="text-sm font-semibold text-hcp-secondary-600 dark:text-hcp-secondary-400">Especialista</p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Ranking Semanal -->
                    <x-card padding="lg">
                        <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-6">
                            Ranking da Semana
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 p-3 bg-hcp-warning-50 dark:bg-hcp-warning-900/20 rounded-2xl">
                                <div class="w-10 h-10 bg-hcp-warning-500 rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-white text-sm font-bold">1</span>
                                </div>
                                <img src="https://ui-avatars.com/api/?name=Ana+Silva&color=0ea5e9&background=f0f9ff" 
                                     alt="Ana Silva" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="text-base font-semibold text-hcp-secondary-900 dark:text-white">Ana Silva</p>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">320 pts</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-2xl">
                                <div class="w-10 h-10 bg-hcp-secondary-400 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">2</span>
                                </div>
                                <img src="https://ui-avatars.com/api/?name=Carlos+Pereira&color=0ea5e9&background=f0f9ff" 
                                     alt="Carlos Pereira" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="text-base font-semibold text-hcp-secondary-900 dark:text-white">Carlos Pereira</p>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">285 pts</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-2xl">
                                <div class="w-10 h-10 bg-hcp-warning-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">3</span>
                                </div>
                                <img src="https://ui-avatars.com/api/?name=Maria+Santos&color=0ea5e9&background=f0f9ff" 
                                     alt="Maria Santos" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="text-base font-semibold text-hcp-secondary-900 dark:text-white">Maria Santos</p>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">245 pts</p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="dashboard" />

    <!-- Mobile App Enhancements -->
    <div class="md:hidden">
        <!-- Status Bar Spacer for PWA -->
        <div class="h-safe-area-top bg-white dark:bg-hcp-secondary-800"></div>
        
        <!-- Floating Action Button (FAB) -->
        <button class="fixed bottom-24 right-4 w-14 h-14 bg-hcp-gradient rounded-full shadow-hcp-lg flex items-center justify-center z-40 hover:scale-110 active:scale-95 transition-all duration-200 touch-target"
                onclick="if (navigator.vibrate) navigator.vibrate(20);">
            <x-icon name="plus" class="text-white" size="md" />
        </button>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Saudação baseada no horário
            function updateGreeting() {
                const hour = new Date().getHours();
                let greeting = '';
                
                if (hour < 12) {
                    greeting = 'Bom dia';
                } else if (hour < 18) {
                    greeting = 'Boa tarde';
                } else {
                    greeting = 'Boa noite';
                }
                
                const greetingDesktop = document.getElementById('greeting-desktop');
                const greetingTime = document.getElementById('greeting-time');
                
                if (greetingDesktop) greetingDesktop.textContent = greeting;
                if (greetingTime) greetingTime.textContent = greeting;
            }
            
            // Contador animado
            function animateCounters() {
                const counters = document.querySelectorAll('.counter');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = 2000;
                    const step = target / (duration / 16);
                    let current = 0;
                    
                    const timer = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        counter.textContent = Math.floor(current);
                    }, 16);
                });
            }
            
            // Pull to refresh para mobile
            let startY = 0;
            let currentY = 0;
            let pullDistance = 0;
            let isPulling = false;
            let isRefreshing = false;
            
            function initPullToRefresh() {
                if (window.innerWidth > 768) return; // Apenas mobile
                
                const container = document.body;
                const indicator = document.getElementById('pull-to-refresh');
                
                container.addEventListener('touchstart', (e) => {
                    if (window.scrollY === 0 && !isRefreshing) {
                        startY = e.touches[0].clientY;
                        isPulling = true;
                    }
                }, { passive: true });
                
                container.addEventListener('touchmove', (e) => {
                    if (!isPulling || isRefreshing) return;
                    
                    currentY = e.touches[0].clientY;
                    pullDistance = currentY - startY;
                    
                    if (pullDistance > 0 && pullDistance < 100) {
                        e.preventDefault();
                        indicator.style.transform = `translateY(${pullDistance}px)`;
                        indicator.classList.remove('hidden');
                        
                        // Haptic feedback leve
                        if (navigator.vibrate && pullDistance > 50) {
                            navigator.vibrate(10);
                        }
                    }
                }, { passive: false });
                
                container.addEventListener('touchend', () => {
                    if (isPulling && pullDistance > 60 && !isRefreshing) {
                        triggerRefresh();
                    } else {
                        resetPullToRefresh();
                    }
                    isPulling = false;
                });
            }
            
            function triggerRefresh() {
                isRefreshing = true;
                const indicator = document.getElementById('pull-to-refresh');
                indicator.classList.remove('hidden');
                
                // Haptic feedback de sucesso
                if (navigator.vibrate) {
                    navigator.vibrate([50, 50, 50]);
                }
                
                // Simular refresh
                setTimeout(() => {
                    resetPullToRefresh();
                    showToast('success', 'Dashboard atualizado!');
                    // Aqui você pode adicionar lógica real de refresh
                    location.reload();
                }, 1500);
            }
            
            function resetPullToRefresh() {
                isRefreshing = false;
                const indicator = document.getElementById('pull-to-refresh');
                indicator.style.transform = 'translateY(0)';
                setTimeout(() => {
                    indicator.classList.add('hidden');
                }, 300);
            }
            
            // Gestos de swipe para cards
            function initSwipeGestures() {
                const moduleCards = document.querySelectorAll('.module-card');
                
                moduleCards.forEach(card => {
                    let startX = 0;
                    let currentX = 0;
                    let isSwipingCard = false;
                    
                    card.addEventListener('touchstart', (e) => {
                        startX = e.touches[0].clientX;
                        isSwipingCard = true;
                    }, { passive: true });
                    
                    card.addEventListener('touchmove', (e) => {
                        if (!isSwipingCard) return;
                        
                        currentX = e.touches[0].clientX;
                        const diffX = currentX - startX;
                        
                        if (Math.abs(diffX) > 10) {
                            card.style.transform = `translateX(${diffX * 0.3}px)`;
                            
                            // Feedback visual
                            if (diffX > 50) {
                                card.style.backgroundColor = 'rgba(34, 197, 94, 0.1)';
                            } else if (diffX < -50) {
                                card.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
                            }
                        }
                    }, { passive: true });
                    
                    card.addEventListener('touchend', () => {
                        if (!isSwipingCard) return;
                        
                        const diffX = currentX - startX;
                        
                        if (Math.abs(diffX) > 80) {
                            // Haptic feedback
                            if (navigator.vibrate) {
                                navigator.vibrate(30);
                            }
                            
                            if (diffX > 0) {
                                showToast('success', 'Módulo marcado como favorito!');
                            } else {
                                showToast('info', 'Módulo removido da lista!');
                            }
                        }
                        
                        // Reset
                        card.style.transform = '';
                        card.style.backgroundColor = '';
                        isSwipingCard = false;
                    });
                });
            }
            
            // Interações com haptic feedback
            function addHapticFeedback() {
                const touchTargets = document.querySelectorAll('.touch-target');
                
                touchTargets.forEach(target => {
                    target.addEventListener('touchstart', () => {
                        if (navigator.vibrate) {
                            navigator.vibrate(10);
                        }
                    }, { passive: true });
                });
                
                // Feedback para botões importantes
                const importantButtons = document.querySelectorAll('button[class*="bg-hcp-"]');
                importantButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        if (navigator.vibrate) {
                            navigator.vibrate(20);
                        }
                    });
                });
            }
            
            // Otimizações de performance para mobile
            function optimizeForMobile() {
                // Lazy loading para imagens
                const images = document.querySelectorAll('img[src]');
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.classList.add('animate-fade-in-up');
                                imageObserver.unobserve(img);
                            }
                        });
                    });
                    
                    images.forEach(img => imageObserver.observe(img));
                }
                
                // Otimizar animações para dispositivos com bateria baixa
                if ('getBattery' in navigator) {
                    navigator.getBattery().then(battery => {
                        if (battery.level < 0.2) {
                            document.body.classList.add('reduce-animations');
                        }
                    });
                }
            }
            
            // Detecção de orientação
            function handleOrientationChange() {
                const isLandscape = window.innerWidth > window.innerHeight;
                document.body.classList.toggle('landscape', isLandscape);
                
                // Ajustar layout para landscape
                if (isLandscape && window.innerWidth < 768) {
                    document.body.classList.add('mobile-landscape');
                } else {
                    document.body.classList.remove('mobile-landscape');
                }
            }
            
            // Inicializar tudo
            updateGreeting();
            animateCounters();
            initPullToRefresh();
            initSwipeGestures();
            addHapticFeedback();
            optimizeForMobile();
            handleOrientationChange();
            
            // Event listeners
            window.addEventListener('orientationchange', handleOrientationChange);
            window.addEventListener('resize', handleOrientationChange);
            
            // Atualizar saudação a cada minuto
            setInterval(updateGreeting, 60000);
            
            // Prevenção de zoom duplo toque
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function (event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
            
            // Status da conexão
            function updateConnectionStatus() {
                const isOnline = navigator.onLine;
                if (!isOnline) {
                    showToast('warning', 'Você está offline. Algumas funcionalidades podem não funcionar.');
                }
            }
            
            window.addEventListener('online', () => {
                showToast('success', 'Conexão restaurada!');
            });
            
            window.addEventListener('offline', updateConnectionStatus);
            
            console.log('🚀 Dashboard mobile otimizado carregado!');
        });
    </script>
    
    <!-- Fallback JavaScript para dropdown do avatar -->
    <script>
        function toggleProfileMenu(button) {
            const dropdown = button.nextElementSibling;
            if (dropdown) {
                const isVisible = dropdown.style.display !== 'none';
                dropdown.style.display = isVisible ? 'none' : 'block';
                
                // Fechar outros dropdowns
                document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.style.display = 'none';
                    }
                });
            }
        }
        
        // Fechar dropdowns ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[x-data]')) {
                document.querySelectorAll('[x-show]').forEach(menu => {
                    if (menu.style.display === 'block') {
                        menu.style.display = 'none';
                    }
                });
            }
        });
        
        // Verificar se Alpine.js está funcionando
        setTimeout(() => {
            if (typeof Alpine === 'undefined') {
                console.log('Alpine.js não carregou, usando fallback JavaScript');
                // Adicionar data-dropdown-menu aos dropdowns
                document.querySelectorAll('[x-show]').forEach(menu => {
                    menu.setAttribute('data-dropdown-menu', 'true');
                });
            }
        }, 1000);
    </script>
    @endpush
</x-layouts.app>
