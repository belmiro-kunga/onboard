<x-layouts.app title="Conquistas - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        🏅 Conquistas
                    </h1>
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Suas conquistas desbloqueadas e objetivos disponíveis
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg p-4 shadow-sm border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ count($earnedAchievements) }}
                            </div>
                            <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Conquistas Desbloqueadas
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-600">
                    <nav class="flex space-x-8">
                        <button class="py-2 px-1 border-b-2 border-hcp-500 text-sm font-medium text-hcp-500 tab-button active" data-tab="earned">
                            🏆 Conquistadas ({{ count($earnedAchievements) }})
                        </button>
                        <button class="py-2 px-1 border-b-2 border-transparent text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 hover:border-hcp-300 transition-colors tab-button" data-tab="available">
                            🎯 Disponíveis ({{ count($availableAchievements) }})
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Earned Achievements -->
                <div id="earned-tab" class="tab-panel active">
                    @if(count($earnedAchievements) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($earnedAchievements as $achievement)
                                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 overflow-hidden achievement-card earned">
                                    <div class="p-6">
                                        <!-- Achievement Icon -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-16 h-16 rounded-full flex items-center justify-center" 
                                                 style="background: linear-gradient(135deg, {{ $achievement->rarity_color }}, {{ $achievement->rarity_color }}80); border: 3px solid {{ $achievement->rarity_color }}">
                                                <span class="text-2xl">🏆</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                                                      style="background-color: {{ $achievement->rarity_color }}20; color: {{ $achievement->rarity_color }}">
                                                    {{ $achievement->formatted_rarity }}
                                                </span>
                                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                                    +{{ $achievement->points_reward }} pontos
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Achievement Info -->
                                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">
                                            {{ $achievement->name }}
                                        </h3>
                                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-4">
                                            {{ $achievement->description }}
                                        </p>

                                        <!-- Earned Date -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                {{ $achievement->formatted_category }}
                                            </span>
                                            <span class="text-xs text-hcp-500 font-medium">
                                                Conquistado em {{ $achievement->pivot->earned_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Shine Effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 -translate-x-full animate-shine"></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🏅</div>
                            <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                                Nenhuma conquista ainda
                            </h3>
                            <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400 mb-6">
                                Complete módulos, faça quizzes e participe ativamente para desbloquear conquistas!
                            </p>
                            <a href="{{ route('modules.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                                🚀 Começar Aprendizado
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Available Achievements -->
                <div id="available-tab" class="tab-panel hidden">
                    @if(count($availableAchievements) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($availableAchievements as $achievement)
                                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 overflow-hidden achievement-card available">
                                    <div class="p-6">
                                        <!-- Achievement Icon -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-16 h-16 rounded-full flex items-center justify-center opacity-60" 
                                                 style="background: linear-gradient(135deg, {{ $achievement['rarity_color'] }}, {{ $achievement['rarity_color'] }}40); border: 2px dashed {{ $achievement['rarity_color'] }}">
                                                <span class="text-2xl grayscale">🏆</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                                                      style="background-color: {{ $achievement['rarity_color'] }}20; color: {{ $achievement['rarity_color'] }}">
                                                    {{ $achievement['rarity_formatted'] }}
                                                </span>
                                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                                    +{{ $achievement['points_reward'] }} pontos
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Achievement Info -->
                                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">
                                            {{ $achievement['name'] }}
                                        </h3>
                                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-4">
                                            {{ $achievement['description'] }}
                                        </p>

                                        <!-- Progress -->
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                                    Progresso
                                                </span>
                                                <span class="text-xs font-medium" style="color: {{ $achievement['rarity_color'] }}">
                                                    {{ $achievement['progress']['current'] }}/{{ $achievement['progress']['required'] }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                                                <div class="h-2 rounded-full transition-all duration-500" 
                                                     style="width: {{ $achievement['progress']['percentage'] }}%; background-color: {{ $achievement['rarity_color'] }}">
                                                </div>
                                            </div>
                                            <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                                {{ $achievement['progress']['percentage'] }}% completo
                                            </div>
                                        </div>

                                        <!-- Category -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                {{ $achievement['category_formatted'] }}
                                            </span>
                                            @if($achievement['progress']['percentage'] >= 80)
                                                <span class="text-xs text-orange-500 font-medium animate-pulse">
                                                    🔥 Quase lá!
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🎯</div>
                            <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                                Todas as conquistas desbloqueadas!
                            </h3>
                            <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Parabéns! Você conquistou todas as conquistas disponíveis.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('gamification.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-300 dark:hover:bg-hcp-secondary-600 transition-colors">
                    ← Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="gamification" />

    @push('styles')
    <style>
        .achievement-card.earned {
            position: relative;
            overflow: hidden;
        }

        .achievement-card.available {
            opacity: 0.8;
        }

        .achievement-card.available:hover {
            opacity: 1;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) skewX(-12deg); }
            100% { transform: translateX(200%) skewX(-12deg); }
        }

        .animate-shine {
            animation: shine 2s infinite;
        }

        .grayscale {
            filter: grayscale(100%);
        }

        .tab-button.active {
            border-color: #4F46E5;
            color: #4F46E5;
        }

        .tab-panel {
            display: block;
        }

        .tab-panel.hidden {
            display: none;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabName = button.dataset.tab;

                    // Update buttons
                    tabButtons.forEach(btn => {
                        if (btn.dataset.tab === tabName) {
                            btn.classList.add('active', 'border-hcp-500', 'text-hcp-500');
                            btn.classList.remove('border-transparent', 'text-hcp-secondary-600', 'dark:text-hcp-secondary-400');
                        } else {
                            btn.classList.remove('active', 'border-hcp-500', 'text-hcp-500');
                            btn.classList.add('border-transparent', 'text-hcp-secondary-600', 'dark:text-hcp-secondary-400');
                        }
                    });

                    // Update panels
                    tabPanels.forEach(panel => {
                        if (panel.id === `${tabName}-tab`) {
                            panel.classList.remove('hidden');
                            panel.classList.add('active');
                        } else {
                            panel.classList.add('hidden');
                            panel.classList.remove('active');
                        }
                    });
                });
            });

            // Achievement card hover effects
            const achievementCards = document.querySelectorAll('.achievement-card');
            achievementCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    if (card.classList.contains('earned')) {
                        card.style.transform = 'translateY(-4px) scale(1.02)';
                        card.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
                    }
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>