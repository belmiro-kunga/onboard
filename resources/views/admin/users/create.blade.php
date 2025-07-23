<x-layouts.admin title="Novo Usuário">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Novo Usuário
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Preencha os campos abaixo para criar um novo usuário no sistema.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para a lista
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6 divide-y divide-gray-200">
                @csrf
                <div class="space-y-6 py-6 px-4 sm:p-6">
                    <!-- Informações Pessoais -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Informações Pessoais</h3>
                            <p class="mt-1 text-sm text-gray-500">Informações básicas do usuário.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Nome Completo -->
                            <div class="sm:col-span-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nome Completo <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="João da Silva">
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="sm:col-span-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    E-mail <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="joao@empresa.com">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Telefone -->
                            <div class="sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Telefone
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="(00) 00000-0000">
                                </div>
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informações Profissionais -->
                    <div class="pt-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Informações Profissionais</h3>
                            <p class="mt-1 text-sm text-gray-500">Dados profissionais do usuário.</p>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Departamento -->
                            <div class="sm:col-span-3">
                                <label for="department" class="block text-sm font-medium text-gray-700">
                                    Departamento
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="department" id="department" value="{{ old('department') }}"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="Ex: TI, RH, Financeiro">
                                </div>
                                @error('department')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cargo -->
                            <div class="sm:col-span-3">
                                <label for="position" class="block text-sm font-medium text-gray-700">
                                    Cargo
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="position" id="position" value="{{ old('position') }}"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="Ex: Desenvolvedor, Analista">
                                </div>
                                @error('position')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configurações de Acesso -->
                    <div class="pt-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Configurações de Acesso</h3>
                            <p class="mt-1 text-sm text-gray-500">Defina as permissões e credenciais de acesso.</p>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Função -->
                            <div class="sm:col-span-3">
                                <label for="role" class="block text-sm font-medium text-gray-700">
                                    Função <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select name="role" id="role" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Selecione uma função</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Gestor</option>
                                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Colaborador</option>
                                    </select>
                                </div>
                                @error('role')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    Selecione o nível de acesso do usuário no sistema.
                                </p>
                            </div>

                            <!-- Status -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Status
                                </label>
                                <div class="mt-2 flex items-center">
                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_active" name="is_active" type="checkbox" value="1" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}
                                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active" class="font-medium text-gray-700">Usuário ativo</label>
                                            <p class="text-gray-500">Desmarque para desativar o acesso deste usuário.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Senha -->
                            <div class="sm:col-span-3">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Senha <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="password" name="password" id="password" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="••••••••">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    Mínimo de 8 caracteres, incluindo letras e números.
                                </p>
                            </div>

                            <!-- Confirmação de Senha -->
                            <div class="sm:col-span-3">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirmar Senha <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Salvar Usuário
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>