<x-layouts.app title="Ranking de Quizzes - HCP Onboarding">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                    🏆 Ranking de Quizzes
                </h1>
                <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    Veja como você se compara com seus colegas
                </p>
            </div>

            <!-- Filtros -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Departamento
                        </label>
                        <select name="department" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white">
                            <option value="all" {{ $department === 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="hr" {{ $department === 'hr' ? 'selected' : '' }}>RH</option>
                            <option value="it" {{ $department === 'it' ? 'selected' : '' }}>TI</option>
                            <option value="security" {{ $department === 'security' ? 'selected' : '' }}>Segurança</option>
                            <option value="processes" {{ $department === 'processes' ? 'selected' : '' }}>Processos</option>
                            <option value="culture" {{ $department === 'culture' ? 'selected' : '' }}>Cultura</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Categoria
                        </label>
                        <select name="category" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white">
                            <option value="all" {{ $category === 'all' ? 'selected' : '' }}>Todas</option>
                            <option value="hr" {{ $category === 'hr' ? 'selected' : '' }}>Recursos Humanos</option>
                            <option value="it" {{ $category === 'it' ? 'selected' : '' }}>TI</option>
                            <option value="security" {{ $category === 'security' ? 'selected' : '' }}>Segurança</option>
                            <option value="processes" {{ $category === 'processes' ? 'selected' : '' }}>Processos</option>
                            <option value="culture" {{ $category === 'culture' ? 'selected' : '' }}>Cultura</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Período
                        </label>
                        <select name="period" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500 dark:bg-hcp-secondary-700 dark:text-white">
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Esta semana</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Este mês</option>
                            <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Este trimestre</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Este ano</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors font-medium">
                            🔍 Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ranking Table -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <h2 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                        📊 Classificação
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-hcp-secondary-50 dark:bg-hcp-secondary-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Posição
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Usuário
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Departamento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Tentativas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Aprovações
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Média
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">
                                    Melhor
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                            @forelse($ranking as $index => $user)
                                <tr class="hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 {{ $user->id === auth()->id() ? 'bg-hcp-100 dark:bg-hcp-900' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($index < 3)
                                                <span class="text-2xl mr-2">
                                                    {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : '🥉') }}
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                #{{ $index + 1 }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-hcp-500 flex items-center justify-center text-white text-sm font-medium">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                    {{ $user->name }}
                                                    @if($user->id === auth()->id())
                                                        <span class="ml-2 px-2 py-1 text-xs bg-hcp-500 text-white rounded-full">Você</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-800 dark:text-hcp-secondary-200 rounded-full">
                                            {{ strtoupper($user->department) }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-hcp-secondary-900 dark:text-white">
                                        {{ $user->total_attempts }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                {{ $user->passed_attempts }}
                                            </span>
                                            <span class="text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400 ml-1">
                                                ({{ $user->total_attempts > 0 ? round(($user->passed_attempts / $user->total_attempts) * 100) : 0 }}%)
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-2 mr-2">
                                                <div class="bg-hcp-500 h-2 rounded-full" style="width: {{ $user->average_score }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                {{ round($user->average_score) }}%
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold {{ $user->best_score >= 90 ? 'text-green-600 dark:text-green-400' : ($user->best_score >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                            {{ $user->best_score }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                            <div class="text-4xl mb-4">📊</div>
                                            <p class="text-lg font-medium mb-2">Nenhum dado encontrado</p>
                                            <p class="text-sm">Ajuste os filtros ou tente novamente mais tarde.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ações -->
            <div class="mt-8 text-center">
                <a href="{{ route('quizzes.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors font-medium">
                    📚 Fazer Mais Quizzes
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <x-mobile-nav current="quizzes" />
</x-layouts.app>