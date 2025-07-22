<x-layouts.app title="{{ $title }} - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        🏆 {{ $title }}
                    </h1>
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Veja como você se compara com outros colaboradores
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <a href="{{ route('gamification.ranking', ['type' => 'global']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $type === 'global' ? 'bg-hcp-500 text-white' : 'bg-white dark:bg-hcp-secondary-800 text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-50 dark:hover:bg-hcp-secondary-700' }}">
                        🌍 Global
                    </a>
                    @if(auth()->user()->department)
                        <a href="{{ route('gamification.ranking', ['type' => 'department']) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $type === 'department' ? 'bg-hcp-500 text-white' : 'bg-white dark:bg-hcp-secondary-800 text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-50 dark:hover:bg-hcp-secondary-700' }}">
                            🏢 {{ auth()->user()->department }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Ranking List -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                @if(count($ranking) > 0)
                    <div class="divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                        @foreach($ranking as $rank)
                            <div class="p-6 {{ $rank['user']['id'] === auth()->id() ? 'bg-hcp-50 dark:bg-hcp-800/50 border-l-4 border-hcp-500' : '' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Position -->
                                        <div class="flex-shrink-0">
                                            @if($rank['position'] <= 3)
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg {{ $rank['position'] === 1 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' : ($rank['position'] === 2 ? 'bg-gradient-to-r from-gray-300 to-gray-500 text-white' : 'bg-gradient-to-r from-orange-400 to-orange-600 text-white') }}">
                                                    {{ $rank['position'] === 1 ? '🥇' : ($rank['position'] === 2 ? '🥈' : '🥉') }}
                                                </div>
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-hcp-secondary-200 dark:bg-hcp-secondary-600 flex items-center justify-center font-bold text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                                    {{ $rank['position'] }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Avatar -->
                                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-hcp-500 to-hcp-600 flex items-center justify-center text-white font-bold text-xl">
                                            {{ substr($rank['user']['name'], 0, 1) }}
                                        </div>

                                        <!-- User Info -->
                                        <div>
                                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                                                {{ $rank['user']['name'] }}
                                                @if($rank['user']['id'] === auth()->id())
                                                    <span class="text-sm text-hcp-500 font-normal">(Você)</span>
                                                @endif
                                            </h3>
                                            <div class="flex items-center space-x-4 mt-1">
                                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                                    {{ $rank['level_formatted'] }}
                                                </span>
                                                @if($rank['user']['department'])
                                                    <span class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                        {{ $rank['user']['department'] }}
                                                    </span>
                                                @endif
                                                <span class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    🏅 {{ $rank['achievements_count'] }} conquistas
                                                </span>
                                                @if(isset($rank['streak_days']) && $rank['streak_days'] > 0)
                                                    <span class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                        🔥 {{ $rank['streak_days'] }} dias
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Points -->
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ number_format($rank['points']) }}
                                        </div>
                                        <div class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            pontos
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar for Top 3 -->
                                @if($rank['position'] <= 3 && count($ranking) > 1)
                                    @php
                                        $maxPoints = $ranking[0]['points'];
                                        $percentage = $maxPoints > 0 ? ($rank['points'] / $maxPoints) * 100 : 0;
                                    @endphp
                                    <div class="mt-4">
                                        <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-500 {{ $rank['position'] === 1 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : ($rank['position'] === 2 ? 'bg-gradient-to-r from-gray-300 to-gray-500' : 'bg-gradient-to-r from-orange-400 to-orange-600') }}" 
                                                 style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($type === 'global' && count($ranking) >= 20)
                        <div class="p-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="flex items-center justify-between">
                                @if($page > 1)
                                    <a href="{{ route('gamification.ranking', ['type' => $type, 'page' => $page - 1]) }}" 
                                       class="px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                                        ← Anterior
                                    </a>
                                @else
                                    <div></div>
                                @endif

                                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    Página {{ $page }}
                                </span>

                                <a href="{{ route('gamification.ranking', ['type' => $type, 'page' => $page + 1]) }}" 
                                   class="px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                                    Próxima →
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">🏆</div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                            Ranking em construção
                        </h3>
                        <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                            Complete módulos e ganhe pontos para aparecer no ranking!
                        </p>
                    </div>
                @endif
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
</x-layouts.app>