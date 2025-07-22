<x-layouts.admin title="Editar Usuário">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Editar Usuário</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Edite as informações do usuário {{ $user->name }}.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6 p-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informações Básicas</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nome Completo *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    E-mail *
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nova Senha (deixe em branco para manter a atual)
                                </label>
                                <input type="password" name="password" id="password"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Confirmar Nova Senha
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Role and Status -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Função e Status</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Função *
                                </label>
                                <select name="role" id="role" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                    <option value="">Selecione uma função</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                                    <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Gerente</option>
                                    <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Funcionário</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status
                                </label>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Usuário ativo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informações Adicionais</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Departamento
                                </label>
                                <input type="text" name="department" id="department" value="{{ old('department', $user->department) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('department')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Position -->
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Cargo
                                </label>
                                <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('position')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Telefone
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Created At (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Criado em
                                </label>
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Statistics (Read-only) -->
                    @if($user->gamification)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Estatísticas do Usuário</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pontos Totais</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->gamification->total_points ?? 0) }}</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Nível Atual</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->gamification->current_level ?? 'Iniciante' }}</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Último Login</div>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>