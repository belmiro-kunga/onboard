<x-layouts.admin title="Gerenciar Atribuições">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Gerenciar Atribuições</h1>
                            <p class="text-indigo-100 mt-1">Atribua cursos, quizzes e módulos aos usuários</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.assignments.create') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Nova Atribuição</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Estatísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Total</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-blue-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Atribuídas -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Atribuídas</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['assigned'] }}</p>
                        </div>
                        <div class="bg-yellow-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Em Progresso -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Em Progresso</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['in_progress'] }}</p>
                        </div>
                        <div class="bg-blue-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Concluídas -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Concluídas</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['completed'] }}</p>
                        </div>
                        <div class="bg-green-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Atrasadas -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Atrasadas</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['overdue'] }}</p>
                        </div>
                        <div class="bg-red-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6 mb-8">
                <form action="{{ route('admin.assignments.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 flex-grow">
                        <!-- Usuário -->
                        <div class="w-full sm:w-auto">
                            <select name="user_id" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                                <option value="">Todos os Usuários</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo -->
                        <div class="w-full sm:w-auto">
                            <select name="type" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                                <option value="">Todos os Tipos</option>
                                @foreach($types as $class => $name)
                                    <option value="{{ $class }}" {{ request('type') === $class ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="w-full sm:w-auto">
                            <select name="status" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                                <option value="">Todos os Status</option>
                                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Atribuído</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Em Progresso</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluído</option>
                                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Atrasado</option>
                            </select>
                        </div>

                        <!-- Atrasadas -->
                        <div class="w-full sm:w-auto">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }} class="rounded border-slate-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-white">Apenas atrasadas</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.assignments.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-300">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Lista de Atribuições -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                <div class="p-6 border-b border-slate-700/50">
                    <h3 class="text-xl font-bold text-white">Lista de Atribuições</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-700/30 border-b border-slate-700/50">
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Prazo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Atribuído por</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @forelse($assignments as $assignment)
                                <tr class="hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-500/20 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-indigo-300">
                                                        {{ strtoupper(substr($assignment->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-white">{{ $assignment->user->name }}</div>
                                                <div class="text-xs text-slate-400">{{ $assignment->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">{{ $assignment->assignable->title ?? 'Item removido' }}</div>
                                        @if($assignment->is_mandatory)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300 mt-1">
                                                Obrigatório
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-700 text-slate-300">
                                            {{ $assignment->type_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $assignment->status_color }}-500/20 text-{{ $assignment->status_color }}-300">
                                            {{ $assignment->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                        @if($assignment->due_date)
                                            <div class="{{ $assignment->isOverdue() ? 'text-red-400' : 'text-slate-300' }}">
                                                {{ $assignment->due_date->format('d/m/Y') }}
                                            </div>
                                            @if($assignment->isOverdue())
                                                <div class="text-xs text-red-400">Atrasado</div>
                                            @endif
                                        @else
                                            <span class="text-slate-500">Sem prazo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                        {{ $assignment->assignedBy->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.assignments.show', $assignment) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.assignments.edit', $assignment) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta atribuição?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <p class="text-lg font-medium">Nenhuma atribuição encontrada</p>
                                            <p class="text-sm mt-1">Tente ajustar os filtros ou crie uma nova atribuição</p>
                                            <a href="{{ route('admin.assignments.create') }}" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-medium transition-all duration-300">
                                                Criar Atribuição
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="p-6 border-t border-slate-700/50">
                    {{ $assignments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>