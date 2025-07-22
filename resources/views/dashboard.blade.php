@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('breadcrumbs')
    <li class="text-hcp-600 dark:text-hcp-400">Dashboard</li>
@endsection

@section('header')
    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
        <span data-greeting></span>, {{ explode(' ', auth()->user()->name)[0] }}! 👋
    </h1>
    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
        Bem-vindo(a) de volta ao seu painel de controle
    </p>
@endsection

@section('content')
    <!-- Progresso Geral -->
    <div class="mb-8">
        <x-card padding="lg" class="bg-gradient-to-r from-hcp-500 via-hcp-600 to-hcp-accent-500 text-white border-0 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0 md:mr-6">
                    <h3 class="text-lg font-semibold text-white/90 mb-1">Seu Progresso no Onboarding</h3>
                    <p class="text-white/80 text-sm">
                        Continue assim! Você está indo muito bem 🚀
                    </p>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium">{{ $progress['completed_modules'] ?? 0 }} de {{ $progress['total_modules'] ?? 0 }} módulos concluídos</span>
                        <span class="font-bold">{{ $progress['overall_percentage'] ?? 0 }}%</span>
                    </div>
                    <div class="relative">
                        <div class="w-full bg-white/20 rounded-full h-3">
                            <div 
                                class="bg-white rounded-full h-3 transition-all duration-1000 ease-out shadow-lg" 
                                style="width: {{ $progress['overall_percentage'] ?? 0 }}%"
                            ></div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full animate-shimmer"></div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Módulos Concluídos -->
        <x-stats-card 
            title="Módulos Concluídos"
            :value="$stats['completed_modules'] ?? 0"
            icon="check-circle"
            trend="up"
            trend-value="12"
            color="success"
        >
            de {{ $stats['total_modules'] ?? 0 }} no total
        </x-stats-card>
        
        <!-- Pontuação -->
        <x-stats-card 
            title="Sua Pontuação"
            :value="$stats['points'] ?? 0"
            icon="award"
            trend="up"
            trend-value="8"
            color="warning"
            gradient="from-amber-500 to-amber-600"
        >
            {{ $stats['points_to_next_level'] ?? 0 }} para o próximo nível
        </x-stats-card>
        
        <!-- Ranking -->
        <x-stats-card 
            title="Seu Ranking"
            :value="'#' . ($stats['ranking'] ?? '--') "
            icon="trending-up"
            trend="up"
            trend-value="5"
            color="hcp"
            gradient="from-blue-500 to-blue-600"
        >
            {{ $stats['total_users'] ?? 0 }} participantes
        </x-stats-card>
        
        <!-- Próximo Nível -->
        <x-stats-card 
            title="Próximo Nível"
            :value="$stats['next_level'] ?? 'Nível 1'"
            icon="zap"
            trend="up"
            trend-value="15"
            color="danger"
            gradient="from-purple-500 to-purple-600"
        >
            {{ $stats['progress_to_next_level'] ?? 0 }}% completo
        </x-stats-card>
    </div>

    <!-- Conteúdo Principal em Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Módulos em Destaque -->
        <div class="lg:col-span-2">
            <x-card padding="lg" class="h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                        Continue de onde parou
                    </h3>
                    <a href="{{ route('modules.index') }}" class="text-sm font-medium text-hcp-600 dark:text-hcp-400 hover:text-hcp-500 dark:hover:text-hcp-300 transition-colors">
                        Ver todos os módulos
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($featuredModules as $module)
                        <x-module-card 
                            :module="$module"
                            :progress="$module['progress'] ?? 0"
                            :showProgress="true"
                            :showAction="true"
                        />
                    @empty
                        @for($i = 0; $i < 4; $i++)
                            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl p-4 border border-hcp-secondary-200 dark:border-hcp-secondary-700 animate-pulse">
                                <div class="h-5 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-3/4 mb-3"></div>
                                <div class="h-3 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 rounded w-full mb-2"></div>
                                <div class="h-3 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 rounded w-5/6 mb-4"></div>
                                <div class="h-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 rounded w-full mb-2"></div>
                                <div class="h-2 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full w-full"></div>
                            </div>
                        @endfor
                    @endforelse
                </div>
            </x-card>
        </div>

        <!-- Atividade Recente -->
        <div>
            <x-card padding="lg" class="h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                        Sua Atividade Recente
                    </h3>
                    <a href="#" class="text-sm font-medium text-hcp-600 dark:text-hcp-400 hover:text-hcp-500 dark:hover:text-hcp-300 transition-colors">
                        Ver histórico
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start pb-4 border-b border-hcp-secondary-100 dark:border-hcp-secondary-700 last:border-0 last:pb-0">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $activity['type'] === 'completed' ? 'bg-hcp-success-100 dark:bg-hcp-success-900/30 text-hcp-success-600 dark:text-hcp-success-400' : 'bg-hcp-100 dark:bg-hcp-900/30 text-hcp-600 dark:text-hcp-400' }} flex items-center justify-center mr-3">
                                <x-icon :name="$activity['icon']" class="w-5 h-5" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-hcp-secondary-900 dark:text-white">
                                    {!! $activity['description'] !!}
                                </p>
                                <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                    {{ $activity['time'] }}
                                </p>
                            </div>
                            @if(isset($activity['badge']))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-hcp-100 dark:bg-hcp-900/50 text-hcp-800 dark:text-hcp-200">
                                    {{ $activity['badge'] }}
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <div class="mx-auto w-12 h-12 rounded-full bg-hcp-secondary-100 dark:bg-hcp-secondary-800 flex items-center justify-center mb-3">
                                <x-icon name="activity" class="w-6 h-6 text-hcp-secondary-400" />
                            </div>
                            <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Nenhuma atividade recente. Comece a explorar os módulos!
                            </p>
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>

    <!-- Conquistas e Ranking -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Conquistas Recentes -->
        <x-card padding="lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                    Conquistas Recentes
                </h3>
                <a href="{{ route('gamification.achievements') }}" class="text-sm font-medium text-hcp-600 dark:text-hcp-400 hover:text-hcp-500 dark:hover:text-hcp-300 transition-colors">
                    Ver todas
                </a>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
                @forelse($recentAchievements as $achievement)
                    <div class="text-center group">
                        <div class="relative inline-flex items-center justify-center mb-2">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                <x-icon :name="$achievement['icon']" class="w-8 h-8" />
                            </div>
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-hcp-success-500 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-hcp-secondary-800">
                                ✓
                            </div>
                        </div>
                        <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white line-clamp-2">
                            {{ $achievement['name'] }}
                        </h4>
                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                            {{ $achievement['date'] }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6">
                        <div class="mx-auto w-12 h-12 rounded-full bg-hcp-secondary-100 dark:bg-hcp-secondary-800 flex items-center justify-center mb-3">
                            <x-icon name="award" class="w-6 h-6 text-hcp-secondary-400" />
                        </div>
                        <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                            Nenhuma conquista recente. Complete módulos para desbloquear!
                        </p>
                    </div>
                @endforelse
            </div>
        </x-card>

        <!-- Ranking de Colaboradores -->
        <x-card padding="lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                    Ranking de Colaboradores
                </h3>
                <a href="{{ route('gamification.ranking') }}" class="text-sm font-medium text-hcp-600 dark:text-hcp-400 hover:text-hcp-500 dark:hover:text-hcp-300 transition-colors">
                    Ver ranking completo
                </a>
            </div>
            
            <div class="space-y-4">
                @foreach($topUsers as $index => $user)
                    <div class="flex items-center group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br {{ $index < 3 ? 'from-yellow-400 to-yellow-600' : 'from-hcp-secondary-400 to-hcp-secondary-600' }} flex items-center justify-center text-white text-xs font-bold mr-3">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center">
                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="w-8 h-8 rounded-full mr-2 border-2 border-white dark:border-hcp-secondary-700 shadow-sm">
                                <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white truncate">
                                    {{ $user['name'] }}
                                </h4>
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex items-center">
                            <x-icon name="award" class="w-4 h-4 text-yellow-500 mr-1" />
                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                {{ $user['points'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
                
                <!-- Posição do usuário atual -->
                @if($userRanking)
                    <div class="pt-4 mt-4 border-t border-hcp-secondary-100 dark:border-hcp-secondary-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-hcp-500 to-hcp-600 flex items-center justify-center text-white text-xs font-bold mr-3">
                                {{ $userRanking['position'] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <img src="{{ $userRanking['avatar'] }}" alt="{{ $userRanking['name'] }}" class="w-8 h-8 rounded-full mr-2 border-2 border-hcp-500 shadow-sm">
                                    <h4 class="text-sm font-medium text-hcp-600 dark:text-hcp-400 truncate">
                                        {{ $userRanking['name'] }}
                                    </h4>
                                </div>
                            </div>
                            <div class="flex-shrink-0 flex items-center">
                                <x-icon name="award" class="w-4 h-4 text-hcp-500 mr-1" />
                                <span class="text-sm font-medium text-hcp-600 dark:text-hcp-400">
                                    {{ $userRanking['points'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-card>
    </div>

    <!-- Próximos Eventos -->
    <div class="mt-6">
        <x-card padding="lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                    Próximos Eventos
                </h3>
                <button class="text-hcp-600 dark:text-hcp-400 hover:text-hcp-500 dark:hover:text-hcp-300 transition-colors">
                    <x-icon name="plus" class="w-5 h-5" />
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($upcomingEvents as $event)
                    <div class="flex items-start p-4 rounded-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-800/50 transition-colors group">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-hcp-50 dark:bg-hcp-900/30 border border-hcp-100 dark:border-hcp-800 flex flex-col items-center justify-center text-center mr-3">
                            <span class="text-sm font-medium text-hcp-700 dark:text-hcp-300">
                                {{ $event['day'] }}
                            </span>
                            <span class="text-xs text-hcp-500 dark:text-hcp-400">
                                {{ $event['month'] }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white group-hover:text-hcp-600 dark:group-hover:text-hcp-400 transition-colors">
                                {{ $event['title'] }}
                            </h4>
                            <div class="flex items-center text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                <x-icon name="clock" class="w-3.5 h-3.5 mr-1" />
                                <span>{{ $event['time'] }}</span>
                                @if(isset($event['location']))
                                    <span class="mx-1">•</span>
                                    <x-icon name="map-pin" class="w-3.5 h-3.5 mr-1" />
                                    <span class="truncate">{{ $event['location'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6">
                        <div class="mx-auto w-12 h-12 rounded-full bg-hcp-secondary-100 dark:bg-hcp-secondary-800 flex items-center justify-center mb-3">
                            <x-icon name="calendar" class="w-6 h-6 text-hcp-secondary-400" />
                        </div>
                        <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                            Nenhum evento agendado para os próximos dias
                        </p>
                        <button class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-hcp-600 hover:bg-hcp-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500">
                            <x-icon name="plus" class="-ml-1 mr-2 h-4 w-4" />
                            Criar Evento
                        </button>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
@endsection

@push('scripts')
<script>
    // Atualizar saudação baseada no horário
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
        
        document.querySelectorAll('[data-greeting]').forEach(el => {
            el.textContent = greeting;
        });
    }
    
    // Inicializar
    // Animate counters
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200; // Lower is faster
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target') || '0');
            const count = parseInt(counter.innerText) || 0;
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(animateCounters, 1);
            } else {
                counter.innerText = target;
            }
        });
    }
    // Handle swipe gestures for module cards
    function initSwipeGestures() {
        const moduleCards = document.querySelectorAll('.module-card');
        
        moduleCards.forEach(card => {
            let startX = 0;
            let currentX = 0;
            let isSwiping = false;
            
            card.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isSwiping = true;
                card.style.transition = 'transform 0.2s ease-out';
            }, { passive: true });
            
            card.addEventListener('touchmove', (e) => {
                if (!isSwiping) return;
                currentX = e.touches[0].clientX;
                const diffX = currentX - startX;
                
                // Only allow horizontal swiping
                if (Math.abs(diffX) > 10) {
                    card.style.transform = `translateX(${diffX}px)`;
                }
            }, { passive: true });
            
            card.addEventListener('touchend', () => {
                if (!isSwiping) return;
                
                const diffX = currentX - startX;
                const threshold = 50; // Minimum swipe distance to trigger action
                
                if (Math.abs(diffX) > threshold) {
                    // Add haptic feedback if available
                    if (navigator.vibrate) {
                        navigator.vibrate(30);
                    }
                    
                    // Handle swipe action
                    if (diffX > 0) {
                        showToast('success', 'Módulo marcado como favorito!');
                    } else {
                        showToast('info', 'Módulo removido da lista!');
                    }
                }
                
                // Reset card position
                card.style.transition = 'transform 0.3s ease-out';
                card.style.transform = '';
                isSwiping = false;
                
                // Remove transition after animation completes
                setTimeout(() => {
                    card.style.transition = '';
                }, 300);
            });
        });
    }
    
    // Initialize connection status monitoring
    function initConnectionMonitoring() {
        const updateConnectionStatus = () => {
            const isOnline = navigator.onLine;
            if (!isOnline) {
                showToast('warning', 'Você está offline. Algumas funcionalidades podem não funcionar.');
            }
        };
        
        window.addEventListener('online', () => {
            showToast('success', 'Conexão restaurada!');
        });
        
        window.addEventListener('offline', updateConnectionStatus);
        
        // Initial check
        updateConnectionStatus();
    }
    
    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize greeting and update every minute
        updateGreeting();
        setInterval(updateGreeting, 60000);
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Start animations
        setTimeout(() => {
            animateCounters();
            initSwipeGestures();
            initConnectionMonitoring();
        }, 500);
        
        console.log('🚀 Dashboard otimizado carregado!');
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
