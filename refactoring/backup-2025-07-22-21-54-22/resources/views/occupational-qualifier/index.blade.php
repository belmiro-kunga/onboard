<x-layouts.employee title="Qualificador Ocupacional">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        🎯 Qualificador Ocupacional
                    </h1>
                    <p class="mt-2 text-lg text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Avalie suas competências profissionais e ganhe certificações
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">📋</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total Disponível</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ count($qualifiers) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">✅</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Completados</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ collect($qualifiers)->where('status', 'completed')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">📊</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Média Geral</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ collect($qualifiers)->where('status', 'completed')->avg('score') ?? 0 }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🏆</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos Ganhos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ collect($qualifiers)->where('status', 'completed')->sum('points_reward') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Qualifiers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($qualifiers as $qualifier)
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg overflow-hidden border border-hcp-secondary-200 dark:border-hcp-secondary-700 transition-all duration-300 hover:shadow-xl hover:border-hcp-500 dark:hover:border-hcp-400 {{ $qualifier['status'] === 'completed' ? 'opacity-90' : '' }}">
                        
                        <!-- Header -->
                        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white line-clamp-2">
                                    {{ $qualifier['title'] }}
                                </h3>
                                <div class="flex flex-col items-end space-y-1 ml-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $qualifier['level'] === 'basic' ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300' : ($qualifier['level'] === 'intermediate' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-300') }}">
                                        {{ ucfirst($qualifier['level']) }}
                                    </span>
                                    @if($qualifier['status'] === 'completed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300">
                                            ✅ Concluído
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 line-clamp-2 mb-3">
                                {{ $qualifier['description'] }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                <span>📂 {{ ucfirst($qualifier['category']) }}</span>
                                <span>❓ {{ $qualifier['questions_count'] }} questões</span>
                                <span>⏱ {{ $qualifier['duration'] }} min</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        @if($qualifier['status'] === 'completed')
                            <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700/50">
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $qualifier['score'] }}%
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Pontuação
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $qualifier['score'] >= $qualifier['passing_score'] ? 'Aprovado' : 'Reprovado' }}
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Status
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $qualifier['points_reward'] }}
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Pontos
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700/50">
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $qualifier['passing_score'] }}%
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Nota Mínima
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                            {{ $qualifier['points_reward'] }}
                                        </div>
                                        <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            Pontos Possíveis
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="p-4">
                            @if($qualifier['status'] === 'completed')
                                <div class="flex space-x-2">
                                    <a href="{{ route('occupational-qualifier.result', ['id' => $qualifier['id'], 'score' => $qualifier['score']]) }}" 
                                       class="flex-1 px-4 py-2 bg-hcp-500 text-white text-center rounded-lg hover:bg-hcp-600 transition-colors text-sm font-medium">
                                        📊 Ver Resultado
                                    </a>
                                    <a href="{{ route('occupational-qualifier.show', $qualifier['id']) }}" 
                                       class="flex-1 px-4 py-2 bg-hcp-secondary-300 dark:bg-hcp-secondary-600 text-hcp-secondary-700 dark:text-hcp-secondary-300 text-center rounded-lg hover:bg-hcp-secondary-400 dark:hover:bg-hcp-secondary-500 transition-colors text-sm font-medium">
                                        🔄 Refazer
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('occupational-qualifier.show', $qualifier['id']) }}" 
                                   class="w-full px-4 py-2 bg-hcp-gradient text-white text-center rounded-lg hover:shadow-hcp-lg transition-all duration-200 text-sm font-medium block">
                                    🚀 Iniciar Qualificador
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Info Section -->
            <div class="mt-12 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-8">
                <h2 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white mb-6 text-center">
                    🎯 Como Funciona o Qualificador Ocupacional
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-hcp-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl">1️⃣</span>
                        </div>
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">Escolha o Qualificador</h3>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Selecione o qualificador adequado à sua área de atuação e nível de experiência.
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-hcp-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl">2️⃣</span>
                        </div>
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">Responda as Questões</h3>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Complete todas as questões dentro do tempo limite estabelecido.
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-hcp-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl">3️⃣</span>
                        </div>
                        <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">Ganhe Pontos</h3>
                        <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Receba pontos de gamificação e certificação baseados no seu desempenho.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="qualifiers" />
</x-layouts.employee>