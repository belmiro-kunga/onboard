<x-layouts.admin title="Gerenciar Usuários">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Usuários</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Gerencie todos os usuários do sistema, incluindo administradores e usuários regulares.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Usuário
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar</label>
                            <input type="text" name="search" id="search" 
                                   value="{{ request('search') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nome ou email...">
                        </div>

                        <!-- Filter -->
                        <div>
                            <label for="filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filtrar</label>
                            <select name="filter" id="filter" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Ativos</option>
                                <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                                <option value="admin" {{ request('filter') === 'admin' ? 'selected' : '' }}>Administradores</option>
                                <option value="user" {{ request('filter') === 'user' ? 'selected' : '' }}>Usuários</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded-lg" id="bulkActions" style="display: none;">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <span id="selectedCount">0</span> usuário(s) selecionado(s)
                        </span>
                        <select id="bulkAction" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Ações em massa</option>
                            <option value="activate">Ativar</option>
                            <option value="deactivate">Desativar</option>
                            <option value="delete">Excluir</option>
                        </select>
                        <button onclick="executeBulkAction()" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Executar
                        </button>
                    </div>
                    <button onclick="clearSelection()" 
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm">
                        Limpar seleção
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="relative px-6 py-3">
                                        <input type="checkbox" id="selectAll" 
                                               class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Usuário
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Função
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Criado em
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Ações</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="relative px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" 
                                                   class="user-checkbox rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="toggleUserStatus({{ $user->id }})" 
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Ver
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                    Editar
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <button onclick="deleteUser({{ $user->id }})" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Excluir
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Nenhum usuário encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-8">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Action Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput">
        <input type="hidden" name="users" id="bulkUsersInput">
    </form>

    @push('scripts')
    <script>
        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox functionality
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (checkboxes.length > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = checkboxes.length;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        function clearSelection() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        }

        function executeBulkAction() {
            const action = document.getElementById('bulkAction').value;
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            
            if (!action) {
                alert('Selecione uma ação');
                return;
            }
            
            if (checkboxes.length === 0) {
                alert('Selecione pelo menos um usuário');
                return;
            }
            
            if (action === 'delete' && !confirm('Tem certeza que deseja excluir os usuários selecionados?')) {
                return;
            }
            
            const userIds = Array.from(checkboxes).map(cb => cb.value);
            
            document.getElementById('bulkActionInput').value = action;
            document.getElementById('bulkUsersInput').value = JSON.stringify(userIds);
            document.getElementById('bulkActionForm').submit();
        }

        function toggleUserStatus(userId) {
            window.safeFetch(`/admin/users/${userId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast('success', 'Status do usuário alterado com sucesso!');
                    }
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.error || 'Erro ao alterar status do usuário');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.handleAjaxError(error, () => toggleUserStatus(userId));
            });
        }

        function deleteUser(userId) {
            if (confirm('Tem certeza que deseja excluir este usuário?')) {
                window.safeFetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        if (typeof showToast === 'function') {
                            showToast('success', 'Usuário excluído com sucesso!');
                        }
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        throw new Error('Erro ao excluir usuário');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.handleAjaxError(error, () => deleteUser(userId));
                });
            }
        }
    </script>
    @endpush
</x-layouts.admin> 