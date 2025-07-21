<x-layouts.admin title="Gerenciamento de Simulados - HCP">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        📝 Gerenciamento de Simulados
                    </h1>
                    <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Crie e gerencie simulados para avaliar o conhecimento dos colaboradores.
                    </p>
                </div>
                <a href="{{ route('admin.simulados.create') }}" class="inline-flex items-center px-4 py-2 bg-hcp-primary-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-hcp-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Novo Simulado
                </a>
            </div>

            <!-- Filtros -->
            <div class="mb-6 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm p-4">
                <form action="{{ route('admin.simulados.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Buscar por título ou descrição" class="w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                    </div>
                    <div class="w-40">
                        <label for="status" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <label for="order_by" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Ordenar por</label>
                        <select name="order_by" id="order_by" class="w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            <option value="created_at" {{ request('order_by') === 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                            <option value="title" {{ request('order_by') === 'title' ? 'selected' : '' }}>Título</option>
                            <option value="passing_score" {{ request('order_by') === 'passing_score' ? 'selected' : '' }}>Nota de Aprovação</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <label for="order_direction" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Direção</label>
                        <select name="order_direction" id="order_direction" class="w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            <option value="desc" {{ request('order_direction') === 'desc' ? 'selected' : '' }}>Decrescente</option>
                            <option value="asc" {{ request('order_direction') === 'asc' ? 'selected' : '' }}>Crescente</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-md hover:bg-hcp-secondary-300 dark:hover:bg-hcp-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-secondary-500">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Simulados -->
            <div class="bg-white dark:bg-hcp-secondary-800 overflow-hidden shadow-sm rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                        <thead class="bg-hcp-secondary-50 dark:bg-hcp-secondary-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Título</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Nota Mínima</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Questões</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Tentativas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Criado em</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-hcp-secondary-800 divide-y divide-hcp-secondary-200 dark:divide-hcp-secondary-700">
                            @forelse($simulados as $simulado)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-hcp-secondary-900 dark:text-white">
                                                {{ $simulado->title }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $simulado->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $simulado->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        {{ $simulado->passing_score }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        {{ $simulado->questoes_count ?? $simulado->questoes()->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        {{ $simulado->tentativas_count ?? $simulado->tentativas()->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        {{ $simulado->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.simulados.show', $simulado) }}" class="text-hcp-primary-600 hover:text-hcp-primary-900 dark:text-hcp-primary-400 dark:hover:text-hcp-primary-300" title="Visualizar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.simulados.edit', $simulado) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.simulados.questoes', $simulado) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="Gerenciar Questões">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.simulados.atribuicoes', $simulado) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Atribuir a Usuários">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.simulados.toggle-active', $simulado) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="{{ $simulado->is_active ? 'text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300' : 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' }}" title="{{ $simulado->is_active ? 'Desativar' : 'Ativar' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($simulado->is_active)
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        @endif
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.simulados.destroy', $simulado) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este simulado?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Excluir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                        Nenhum simulado encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <div class="px-6 py-4 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    {{ $simulados->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>