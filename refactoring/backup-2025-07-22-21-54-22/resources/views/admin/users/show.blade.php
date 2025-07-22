<x-layouts.admin title="Detalhes do Usuário">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Detalhes do Usuário</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Informações detalhadas sobre {{ $user->name }}.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                       class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    @if($user->id !== auth()->id())
                        <button onclick="deleteUser({{ $user->id }})" 
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Excluir
                        </button>
                    @endif
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informações Pessoais</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Função</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                        {{ $user->role === 'manager' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $user->role === 'employee' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </dd>
                            </div>
                            @if($user->department)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Departamento</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->department }}</dd>
                            </div>
                            @endif
                            @if($user->position)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cargo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->position }}</dd>
                            </div>
                            @endif
                            @if($user->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->phone }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Último Login</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="space-y-6">
                <!-- Gamification Stats -->
                @if($user->gamification)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Gamificação</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($user->gamification->total_points ?? 0) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pontos Totais</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $user->gamification->current_level ?? 'Iniciante' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Nível Atual</div>
                        </div>
                        @if($user->gamification->rank_position)
                        <div class="text-center">
                            <div class="text-lg font-medium text-yellow-600 dark:text-yellow-400">
                                #{{ $user->gamification->rank_position }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Posição no Ranking</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Progress Stats -->
                @if($user->progress && $user->progress->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Progresso</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $user->progress->where('status', 'completed')->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Módulos Concluídos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $user->progress->where('status', 'in_progress')->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Em Progresso</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Achievements -->
                @if($user->achievements && $user->achievements->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Conquistas</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="text-center mb-4">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $user->achievements->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Conquistas Desbloqueadas</div>
                        </div>
                        <div class="space-y-2">
                            @foreach($user->achievements->take(5) as $achievement)
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $achievement->name }}</span>
                            </div>
                            @endforeach
                            @if($user->achievements->count() > 5)
                            <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                +{{ $user->achievements->count() - 5 }} mais
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Atividade Recente</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2">Nenhuma atividade recente encontrada.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function deleteUser(userId) {
            if (confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) {
                // Create a form to submit the DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}`;
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);
                
                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @endpush
</x-layouts.admin>