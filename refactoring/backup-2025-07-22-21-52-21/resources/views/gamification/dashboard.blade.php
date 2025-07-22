<x-layouts.employee title="Gamificação">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        🎮 Gamificação
                    </h1>
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Acompanhe seu progresso, conquistas e posição no ranking
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Points -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">⭐</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Pontos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white" id="total-points">
                                {{ number_format($stats['total_points'] ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Current Level -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, {{ $stats['level_color'] ?? '#6B7280' }}, {{ $stats['level_color'] ?? '#6B7280' }}80)">
                                <span class="text-white text-xl">🏆</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Nível Atual</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ $stats['level_formatted'] ?? 'Rookie' }}
                            </p>
                        </div>
                    </div>
                    <!-- Level Progress -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-hcp-secondary-600 dark:text-hcp-secondary-400">Progresso</span>
                            <span class="text-hcp-500 font-medium">{{ $stats['level_progress'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $stats['level_progress'] ?? 0 }}%; background: linear-gradient(90deg, {{ $stats['level_color'] ?? '#6B7280' }}, {{ $stats['level_color'] ?? '#6B7280' }}80)">
                            </div>
                        </div>
                        @if(($stats['next_level_requirement'] ?? 0) > 0)
                            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">
                                {{ number_format($stats['next_level_requirement']) }} pontos para o próximo nível
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Achievements -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🏅</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Conquistas</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ $stats['achievements_count'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ranking Position -->
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">📊</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Posição no Ranking</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                #{{ $stats['rank_position'] ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Recent Activities -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white flex items-center">
                                📈 Atividades Recentes
                            </h3>
                        </div>
                        <div class="p-6">
                            @if(isset($stats['recent_activities']) && count($stats['recent_activities']) > 0)
                                <div class="space-y-4">
                                    @foreach($stats['recent_activities'] as $activity)
                                        <div class="flex items-center justify-between p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ ($activity['color'] ?? 'green') === 'green' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                    {{ ($activity['color'] ?? 'green') === 'green' ? '↗️' : '↘️' }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                        {{ $activity['reason'] ?? 'Atividade' }}
                                                    </p>
                                                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                        {{ $activity['date'] ?? 'Agora' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold {{ ($activity['color'] ?? 'green') === 'green' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $activity['points'] ?? 0 }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4">📊</div>
                                    <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Nenhuma atividade recente
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Global Ranking -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white flex items-center">
                                    🌍 Ranking Global
                                </h3>
                                <a href="{{ route('gamification.ranking') }}" class="text-sm text-hcp-500 hover:text-hcp-600 font-medium">
                                    Ver completo →
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if(isset($globalRanking) && count($globalRanking) > 0)
                                <div class="space-y-3">
                                    @foreach($globalRanking as $rank)
                                        <div class="flex items-center justify-between p-3 rounded-lg {{ ($rank['user']['id'] ?? 0) === auth()->id() ? 'bg-hcp-100 dark:bg-hcp-800 border border-hcp-300 dark:border-hcp-600' : 'bg-hcp-secondary-50 dark:bg-hcp-secondary-700' }}">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ ($rank['position'] ?? 0) <= 3 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' : 'bg-hcp-secondary-200 dark:bg-hcp-secondary-600 text-hcp-secondary-700 dark:text-hcp-secondary-300' }}">
                                                    {{ $rank['position'] ?? 0 }}
                                                </div>
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-hcp-500 to-hcp-600 flex items-center justify-center text-white font-bold">
                                                    {{ substr($rank['user']['name'] ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                        {{ $rank['user']['name'] ?? 'Usuário' }}
                                                        @if(($rank['user']['id'] ?? 0) === auth()->id())
                                                            <span class="text-xs text-hcp-500">(Você)</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                        {{ $rank['level_formatted'] ?? 'Rookie' }} • {{ $rank['achievements_count'] ?? 0 }} conquistas
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-hcp-secondary-900 dark:text-white">
                                                    {{ number_format($rank['points'] ?? 0) }}
                                                </p>
                                                <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    pontos
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4">🏆</div>
                                    <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Ranking em construção
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- Recent Achievements -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white flex items-center">
                                    🏅 Conquistas Recentes
                                </h3>
                                <a href="{{ route('gamification.achievements') }}" class="text-sm text-hcp-500 hover:text-hcp-600 font-medium">
                                    Ver todas →
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if(isset($stats['recent_achievements']) && count($stats['recent_achievements']) > 0)
                                <div class="space-y-4">
                                    @foreach($stats['recent_achievements'] as $achievement)
                                        <div class="flex items-center space-x-3 p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: {{ $achievement['rarity_color'] ?? '#6B7280' }}20; border: 2px solid {{ $achievement['rarity_color'] ?? '#6B7280' }}">
                                                <span class="text-xl">🏆</span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                    {{ $achievement['name'] ?? 'Conquista' }}
                                                </p>
                                                <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    {{ $achievement['earned_at'] ?? 'Agora' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4">🏅</div>
                                    <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400 text-sm">
                                        Nenhuma conquista ainda
                                    </p>
                                    <p class="text-xs text-hcp-secondary-400 dark:text-hcp-secondary-500 mt-1">
                                        Complete módulos para desbloquear!
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Available Achievements Preview -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white flex items-center">
                                🎯 Próximas Conquistas
                            </h3>
                        </div>
                        <div class="p-6">
                            @if(isset($availableAchievements) && count($availableAchievements) > 0)
                                <div class="space-y-4">
                                    @foreach(array_slice($availableAchievements, 0, 3) as $achievement)
                                        <div class="p-3 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                    {{ $achievement['name'] ?? 'Conquista' }}
                                                </p>
                                                <span class="text-xs px-2 py-1 rounded-full" style="background-color: {{ $achievement['rarity_color'] ?? '#6B7280' }}20; color: {{ $achievement['rarity_color'] ?? '#6B7280' }}">
                                                    {{ $achievement['rarity_formatted'] ?? 'Comum' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-3">
                                                {{ $achievement['description'] ?? 'Descrição da conquista' }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1 mr-3">
                                                    <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-2">
                                                        <div class="h-2 rounded-full transition-all duration-300" 
                                                             style="width: {{ $achievement['progress']['percentage'] ?? 0 }}%; background-color: {{ $achievement['rarity_color'] ?? '#6B7280' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    {{ $achievement['progress']['current'] ?? 0 }}/{{ $achievement['progress']['required'] ?? 1 }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4">🎯</div>
                                    <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400 text-sm">
                                        Todas as conquistas desbloqueadas!
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="gamification" />

    <!-- Floating Animations Container -->
    <div id="floating-animations" class="fixed inset-0 pointer-events-none z-50"></div>

    @push('scripts')
    <script src="{{ asset('js/gamification-animations.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize gamification animations
            window.gamificationAnimations = new GamificationAnimations();
            
            // Listen for real-time events
            if (window.Echo) {
                window.Echo.private('user.{{ auth()->id() }}')
                    .listen('.points.awarded', (e) => {
                        window.gamificationAnimations.showPointsFloat(e.points, e.reason);
                        updateTotalPoints(e.points);
                    })
                    .listen('.user.level.up', (e) => {
                        window.gamificationAnimations.showLevelUp(e.old_level, e.new_level);
                    })
                    .listen('.achievement.earned', (e) => {
                        window.gamificationAnimations.showAchievementEarned(e.achievement);
                    });
            }
        });

        function updateTotalPoints(addedPoints) {
            const totalPointsElement = document.getElementById('total-points');
            if (totalPointsElement) {
                const currentPoints = parseInt(totalPointsElement.textContent.replace(/,/g, ''));
                const newTotal = currentPoints + addedPoints;
                totalPointsElement.textContent = new Intl.NumberFormat().format(newTotal);
            }
        }
    </script>
    @endpush
</x-layouts.employee>