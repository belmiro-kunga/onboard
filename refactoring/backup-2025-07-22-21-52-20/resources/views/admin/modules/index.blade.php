<x-layouts.admin title="Gerenciar Módulos">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Módulos</h1>
            <a href="{{ route('admin.modules.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700">
                + Novo Módulo
            </a>
        </div>

        <!-- Filtros -->
        <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar título ou descrição"
                   class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white px-3 py-2">
            <select name="category" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white px-3 py-2">
                <option value="">Todas as categorias</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(request('category') == $cat)>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            <select name="difficulty" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white px-3 py-2">
                <option value="">Todos os níveis</option>
                @foreach($difficultyLevels as $level)
                    <option value="{{ $level }}" @selected(request('difficulty') == $level)>{{ ucfirst($level) }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white px-3 py-2">
                <option value="">Todos os status</option>
                <option value="active" @selected(request('status') == 'active')>Ativo</option>
                <option value="inactive" @selected(request('status') == 'inactive')>Inativo</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md">Filtrar</button>
        </form>

        <!-- Tabela de módulos -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Ordem</th>
                        <th>Status</th>
                        <th>Dificuldade</th>
                        <th>Pontos</th>
                        <th>Duração</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modules as $module)
                        <tr>
                            <td class="font-semibold">{{ $module->title }}</td>
                            <td>{{ ucfirst($module->category) }}</td>
                            <td>{{ $module->order_index }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                    {{ $module->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $module->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                    {{ $module->difficulty_level === 'advanced' ? 'bg-purple-100 text-purple-800' : ($module->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $module->formatted_difficulty ?? ucfirst($module->difficulty_level) }}
                                </span>
                            </td>
                            <td>{{ $module->points_reward }}</td>
                            <td>{{ $module->formatted_duration ?? $module->estimated_duration . ' min' }}</td>
                            <td>{{ ucfirst($module->content_type) }}</td>
                            <td>
                                <a href="{{ route('admin.modules.edit', $module) }}" class="text-indigo-600 hover:underline">Editar</a>
                                <a href="{{ route('admin.modules.show', $module) }}" class="text-blue-600 hover:underline ml-2">Ver</a>
                                <a href="{{ route('admin.modules.contents', $module) }}" class="text-green-600 hover:underline ml-2">Conteúdos</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-8">Nenhum módulo encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $modules->links() }}
        </div>
    </div>
</x-layouts.admin> 