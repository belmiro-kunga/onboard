{{-- Dashboard Interativo HCP - Versão Limpa com Navegação --}}
<x-layouts.employee title="Dashboard">
    <!-- Dashboard Content -->
    <div class="min-h-screen bg-gradient-to-br from-hcp-secondary-50 via-hcp-secondary-100 to-hcp-accent-50 dark:from-hcp-secondary-900 dark:via-hcp-secondary-800 dark:to-hcp-secondary-900">
        <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            <!-- Saudação personalizada -->
            <div class="mb-8 animate-fade-in-down">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="relative">
                        <img src="{{ auth()->user()->avatar_url }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-16 h-16 rounded-2xl border-4 border-white dark:border-hcp-secondary-700 shadow-hcp-lg">
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-hcp-success-500 rounded-full border-4 border-white dark:border-hcp-secondary-800 flex items-center justify-center">
                            <x-icon name="check" size="xs" class="text-white" />
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

            <!-- Layout Principal -->
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
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6 animate-fade-in-up animate-delay-200">
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

                        <!-- Cursos em Andamento -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-400" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-warning-400 to-hcp-warning-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-hcp-lg animate-pulse">
                                <x-icon name="play-circle" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2 counter" data-target="2">
                                0
                            </h3>
                            <p class="text-sm lg:text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 font-medium">
                                Em Andamento
                            </p>
                        </x-card>

                        <!-- Cursos Concluídos -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-450" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-success-400 to-hcp-success-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-hcp-lg">
                                <x-icon name="check-circle" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2 counter" data-target="1">
                                0
                            </h3>
                            <p class="text-sm lg:text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 font-medium">
                                Concluídos
                            </p>
                        </x-card>

                        <!-- Ranking -->
                        <x-card class="text-center hover-lift animate-fade-in-up animate-delay-500" padding="lg">
                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-info-400 to-hcp-info-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-hcp-lg">
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
                                <a href="{{ route('modules.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-hcp-lg transition-all duration-200 hover:scale-105 active:scale-95 shadow-lg">
                                    <span class="hidden sm:inline font-medium">Começar</span>
                                    <x-icon name="arrow-right" size="sm" class="sm:hidden" />
                                </a>
                            </div>
                        </x-card>
                    </div>

                    <!-- Card de Redirecionamento para Módulos -->
                    <div class="animate-fade-in-left animate-delay-700">
                        <div class="rounded-hcp-xl transition-all duration-300 ease-in-out bg-gradient-to-br from-hcp-500 to-hcp-600 shadow-hcp p-8 hover:shadow-hcp-lg hover:-translate-y-1 hover:scale-[1.02] cursor-pointer" onclick="window.location.href='{{ route('modules.index') }}'">
                            <div class="text-center text-white">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <x-icon name="book-open" size="lg" class="text-white" />
                                </div>
                                <h3 class="text-2xl font-semibold mb-2">Seus Módulos</h3>
                                <p class="text-hcp-100 mb-6">Acesse suas missões ativas e explore novos módulos</p>
                                
                                @php
                                    // Estatísticas dos cursos baseadas nos dados reais
                                    $totalCourses = 8; // Total de cursos disponíveis
                                    $completedCourses = 1; // PHP OOP está completo
                                    $inProgressCourses = 2; // Laravel e JavaScript em andamento
                                    $availableCourses = $totalCourses - $completedCourses; // Cursos disponíveis para fazer
                                @endphp
                                
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $availableCourses }}</div>
                                        <div class="text-xs text-hcp-100">Disponíveis</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $completedCourses }}</div>
                                        <div class="text-xs text-hcp-100">Concluídos</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $inProgressCourses }}</div>
                                        <div class="text-xs text-hcp-100">Em Andamento</div>
                                    </div>
                                </div>
                                
                                <div class="inline-flex items-center px-6 py-3 bg-white text-hcp-500 font-medium rounded-hcp-lg hover:bg-hcp-50 transition-colors">
                                    <x-icon name="arrow-right" size="sm" class="mr-2" />
                                    Ver Módulos
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (1/4 da largura) -->
                <div class="xl:col-span-1 space-y-6">
                    <!-- Ações Rápidas -->
                    <div class="animate-fade-in-right animate-delay-300">
                        <x-card padding="lg">
                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                                Ações Rápidas
                            </h3>
                            <div class="space-y-3">
                                <a href="{{ route('quizzes.index') }}" class="flex items-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-hcp-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors group">
                                    <div class="w-10 h-10 bg-hcp-info-500 rounded-hcp flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <x-icon name="help-circle" size="sm" class="text-white" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-hcp-secondary-900 dark:text-white">Fazer Quiz</p>
                                        <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Teste seus conhecimentos</p>
                                    </div>
                                </a>

                                <a href="{{ route('gamification.dashboard') }}" class="flex items-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-hcp-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors group">
                                    <div class="w-10 h-10 bg-hcp-warning-500 rounded-hcp flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <x-icon name="star" size="sm" class="text-white" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-hcp-secondary-900 dark:text-white">Conquistas</p>
                                        <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Ver medalhas</p>
                                    </div>
                                </a>

                                <a href="{{ route('certificates.index') }}" class="flex items-center p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-hcp-lg hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-colors group">
                                    <div class="w-10 h-10 bg-hcp-success-500 rounded-hcp flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <x-icon name="award" size="sm" class="text-white" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-hcp-secondary-900 dark:text-white">Certificados</p>
                                        <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Baixar certificados</p>
                                    </div>
                                </a>
                            </div>
                        </x-card>
                    </div>

                    <!-- Notificações Recentes -->
                    <div class="animate-fade-in-right animate-delay-400">
                        <x-card padding="lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                                    Notificações
                                </h3>
                                <a href="{{ route('notifications.index') }}" class="text-hcp-500 hover:text-hcp-400 text-sm font-medium transition-colors">
                                    Ver todas
                                </a>
                            </div>
                            <div class="space-y-3">
                                @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                                    <div class="flex items-start space-x-3 p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-hcp-lg">
                                        <div class="w-2 h-2 bg-hcp-500 rounded-full mt-2 flex-shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white truncate">
                                                {{ $notification->title }}
                                            </p>
                                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">
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
                    </div>

                    <!-- Ranking Rápido -->
                    <div class="animate-fade-in-right animate-delay-500">
                        <x-card padding="lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                                    Top Ranking
                                </h3>
                                <a href="{{ route('gamification.ranking') }}" class="text-hcp-500 hover:text-hcp-400 text-sm font-medium transition-colors">
                                    Ver ranking
                                </a>
                            </div>
                            <div class="space-y-3">
                                @for($i = 1; $i <= 3; $i++)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-hcp-warning-400 to-hcp-warning-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ $i }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                Usuário {{ $i }}
                                            </p>
                                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                                {{ 1000 - ($i * 100) }} pontos
                                            </p>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Saudação baseada no horário
        function updateGreeting() {
            const hour = new Date().getHours();
            let greeting = '';
            
            if (hour < 12) {
                greeting = '🌅 Bom dia';
            } else if (hour < 18) {
                greeting = '☀️ Boa tarde';
            } else {
                greeting = '🌙 Boa noite';
            }
            
            const greetingElement = document.getElementById('greeting-desktop');
            if (greetingElement) {
                greetingElement.textContent = greeting;
            }
        }
        
        // Contador animado
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = target / 50;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 30);
            });
        }
        
        // Inicializar quando DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            updateGreeting();
            animateCounters();
            
            // Atualizar saudação a cada minuto
            setInterval(updateGreeting, 60000);
        });
    </script>
    @endpush
</x-layouts.employee>