<x-layouts.admin title="Gerenciar Quizzes - HCP Admin">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            Gerenciar Quizzes
                        </h1>
                        <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Crie, edite e gerencie quizzes do sistema de onboarding
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('admin.quizzes.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Novo Quiz
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Quizzes</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['total_quizzes'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Quizzes Ativos</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $stats['active_quizzes'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Total de Tentativas</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ number_format($stats['total_attempts'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontuação Média</p>
                            <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ number_format($stats['average_score'] ?? 0, 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm p-6 mb-8">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Buscar
                        </label>
                        <input type="text" name="search" value="{{ $search }}" 
                               placeholder="Título ou descrição..."
                               class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Categoria
                        </label>
                        <select name="category" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                            <option value="all" {{ $category === 'all' ? 'selected' : '' }}>Todas</option>
                            <option value="hr" {{ $category === 'hr' ? 'selected' : '' }}>RH</option>
                            <option value="it" {{ $category === 'it' ? 'selected' : '' }}>TI</option>
                            <option value="security" {{ $category === 'security' ? 'selected' : '' }}>Segurança</option>
                            <option value="processes" {{ $category === 'processes' ? 'selected' : '' }}>Processos</option>
                            <option value="culture" {{ $category === 'culture' ? 'selected' : '' }}>Cultura</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Dificuldade
                        </label>
                        <select name="difficulty" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                            <option value="all" {{ $difficulty === 'all' ? 'selected' : '' }}>Todas</option>
                            <option value="basic" {{ $difficulty === 'basic' ? 'selected' : '' }}>Básico</option>
                            <option value="intermediate" {{ $difficulty === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                            <option value="advanced" {{ $difficulty === 'advanced' ? 'selected' : '' }}>Avançado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                            Status
                        </label>
                        <select name="status" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Quizzes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($quizzes as $quiz)
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Header do Card -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-2">
                                        {{ $quiz->title }}
                                    </h3>
                                    <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 line-clamp-2">
                                        {{ $quiz->description }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $quiz->is_active 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $quiz->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $quiz->formatted_category }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ $quiz->formatted_difficulty }}
                                </span>
                                @if($quiz->time_limit)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                        {{ $quiz->formatted_time_limit }}
                                    </span>
                                @endif
                            </div>

                            <!-- Estatísticas -->
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $quiz->questions_count }}</p>
                                    <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Questões</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $quiz->attempts_count }}</p>
                                    <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Tentativas</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $quiz->passing_score }}%</p>
                                    <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Aprovação</p>
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.quizzes.duplicate', $quiz) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-hcp-secondary-400 dark:text-hcp-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-hcp-secondary-900 dark:text-white">Nenhum quiz encontrado</h3>
                            <p class="mt-1 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Comece criando seu primeiro quiz.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('admin.quizzes.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Criar Quiz
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginação -->
            @if($quizzes->hasPages())
                <div class="mt-8">
                    {{ $quizzes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>