<x-layouts.employee title="Quizzes">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        🧠 Quizzes Interativos
                    </h1>
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Teste seus conhecimentos e ganhe certificados
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">📚</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Quizzes</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ $stats['total_quizzes'] ?? 0 }}
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
                                {{ $stats['completed_quizzes'] ?? 0 }}
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
                                {{ number_format($stats['average_score'] ?? 0, 1) }}%
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
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Taxa de Sucesso</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                                {{ ($stats['total_quizzes'] ?? 0) > 0 ? number_format((($stats['completed_quizzes'] ?? 0) / ($stats['total_quizzes'] ?? 1)) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-8">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">Categoria</label>
                        <select name="category" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white">
                            <option value="all" {{ ($category ?? 'all') === 'all' ? 'selected' : '' }}>Todas as categorias</option>
                            <option value="hr" {{ ($category ?? '') === 'hr' ? 'selected' : '' }}>Recursos Humanos</option>
                            <option value="it" {{ ($category ?? '') === 'it' ? 'selected' : '' }}>Tecnologia</option>
                            <option value="security" {{ ($category ?? '') === 'security' ? 'selected' : '' }}>Segurança</option>
                            <option value="processes" {{ ($category ?? '') === 'processes' ? 'selected' : '' }}>Processos</option>
                            <option value="culture" {{ ($category ?? '') === 'culture' ? 'selected' : '' }}>Cultura</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">Dificuldade</label>
                        <select name="difficulty" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white">
                            <option value="all" {{ ($difficulty ?? 'all') === 'all' ? 'selected' : '' }}>Todos os níveis</option>
                            <option value="basic" {{ ($difficulty ?? '') === 'basic' ? 'selected' : '' }}>Básico</option>
                            <option value="intermediate" {{ ($difficulty ?? '') === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                            <option value="advanced" {{ ($difficulty ?? '') === 'advanced' ? 'selected' : '' }}>Avançado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white">
                            <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Todos os status</option>
                            <option value="not_started" {{ ($status ?? '') === 'not_started' ? 'selected' : '' }}>Não iniciado</option>
                            <option value="in_progress" {{ ($status ?? '') === 'in_progress' ? 'selected' : '' }}>Em andamento</option>
                            <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="failed" {{ ($status ?? '') === 'failed' ? 'selected' : '' }}>Falhou</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                            🔍 Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quizzes List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($quizzesWithStatus ?? [] as $quiz)
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:scale-[1.02]">
                        <!-- Header -->
                        <div class="p-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                                    {{ $quiz['title'] }}
                                </h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($quiz['status'] === 'completed') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                    @elseif($quiz['status'] === 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                                    @elseif($quiz['status'] === 'failed') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                    @else bg-hcp-secondary-100 text-hcp-secondary-800 dark:bg-hcp-secondary-700 dark:text-hcp-secondary-400
                                    @endif">
                                    @if($quiz['status'] === 'completed') ✅ Concluído
                                    @elseif($quiz['status'] === 'in_progress') 🔄 Em Andamento
                                    @elseif($quiz['status'] === 'failed') ❌ Falhou
                                    @else 📝 Não Iniciado
                                    @endif
                                </span>
                            </div>
                            
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-3">
                                {{ $quiz['description'] }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                <span>📂 {{ ucfirst($quiz['category']) }}</span>
                                <span>❓ {{ $quiz['questions_count'] }} questões</span>
                                <span>⏱ {{ $quiz['time_limit'] ?? 15 }} min</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700/50">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                        {{ $quiz['score'] ?? '-' }}%
                                    </div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Melhor Nota
                                    </div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                        {{ $quiz['attempts'] }}/3
                                    </div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Tentativas
                                    </div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-hcp-secondary-900 dark:text-white">
                                        {{ $quiz['points_reward'] }}
                                    </div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Pontos
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('quizzes.show', $quiz['id']) }}" 
                                   class="flex-1 px-4 py-2 bg-hcp-500 text-white text-center rounded-lg hover:bg-hcp-600 transition-colors text-sm font-medium">
                                    📖 Ver Detalhes
                                </a>
                                
                                @if($quiz['status'] !== 'completed' && $quiz['attempts'] < 3)
                                    <a href="{{ route('quizzes.start', $quiz['id']) }}" 
                                       class="flex-1 px-4 py-2 bg-green-500 text-white text-center rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                                        🚀 {{ $quiz['attempts'] > 0 ? 'Tentar Novamente' : 'Iniciar Quiz' }}
                                    </a>
                                @else
                                    <div class="flex-1 px-4 py-2 bg-hcp-secondary-300 dark:bg-hcp-secondary-600 text-hcp-secondary-500 dark:text-hcp-secondary-400 text-center rounded-lg text-sm font-medium cursor-not-allowed">
                                        {{ $quiz['status'] === 'completed' ? '✅ Concluído' : '❌ Sem tentativas' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">🧠</div>
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                            Nenhum quiz encontrado
                        </h3>
                        <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400 mb-6">
                            Tente ajustar os filtros para encontrar quizzes disponíveis.
                        </p>
                        <a href="{{ route('quizzes.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                            🔄 Ver Todos os Quizzes
                        </a>
                    </div>
                @endforelse
            </div>

            @if(empty($quizzesWithStatus))
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🧠</div>
                    <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-2">
                        Nenhum quiz encontrado
                    </h3>
                    <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400 mb-6">
                        Tente ajustar os filtros para encontrar quizzes disponíveis.
                    </p>
                    <a href="{{ route('quizzes.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors">
                        🔄 Ver Todos os Quizzes
                    </a>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-8 text-center">
                <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('quizzes.ranking') }}" 
                       class="inline-flex items-center px-6 py-3 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                        🏆 Ver Ranking
                    </a>
                    <a href="{{ route('certificates.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        🎓 Meus Certificados
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="quizzes" />

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quiz card hover effects
            const quizCards = document.querySelectorAll('.quiz-card');
            
            quizCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Auto-submit form on filter change
            const filterSelects = document.querySelectorAll('select[name="category"], select[name="difficulty"], select[name="department"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
    @endpush
</x-layouts.employee>