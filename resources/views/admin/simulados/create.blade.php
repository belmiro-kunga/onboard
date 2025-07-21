<x-layouts.admin title="Criar Simulado - HCP">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                    📝 Criar Novo Simulado
                </h1>
                <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    Preencha os campos abaixo para criar um novo simulado.
                </p>
            </div>

            <!-- Formulário -->
            <div class="bg-white dark:bg-hcp-secondary-800 overflow-hidden shadow-sm rounded-lg">
                <form action="{{ route('admin.simulados.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Título -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                            Título <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                            Descrição <span class="text-red-600">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoria e Nível -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Categoria -->
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Categoria <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="categoria" id="categoria" value="{{ old('categoria') }}" required
                                placeholder="Ex: Técnico, Segurança, Compliance"
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('categoria')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nível -->
                        <div>
                            <label for="nivel" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Nível <span class="text-red-600">*</span>
                            </label>
                            <select name="nivel" id="nivel" required
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                                <option value="">Selecione o nível</option>
                                <option value="basic" {{ old('nivel') == 'basic' ? 'selected' : '' }}>Básico</option>
                                <option value="intermediate" {{ old('nivel') == 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                                <option value="advanced" {{ old('nivel') == 'advanced' ? 'selected' : '' }}>Avançado</option>
                            </select>
                            @error('nivel')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Configurações do Simulado -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Duração -->
                        <div>
                            <label for="duracao" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Duração (minutos) <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="duracao" id="duracao" value="{{ old('duracao', 60) }}" min="1" required
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('duracao')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nota Mínima para Aprovação -->
                        <div>
                            <label for="passing_score" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Nota Mínima para Aprovação (%) <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" required
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('passing_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pontos de Recompensa -->
                        <div>
                            <label for="pontos_recompensa" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Pontos de Recompensa <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="pontos_recompensa" id="pontos_recompensa" value="{{ old('pontos_recompensa', 100) }}" min="0" required
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('pontos_recompensa')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                            Status <span class="text-red-600">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            <option value="">Selecione o status</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Rascunho</option>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Período de Disponibilidade -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Data de Disponibilidade -->
                        <div>
                            <label for="disponivel_em" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Disponível em (opcional)
                            </label>
                            <input type="datetime-local" name="disponivel_em" id="disponivel_em" value="{{ old('disponivel_em') }}"
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('disponivel_em')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Data de Expiração -->
                        <div>
                            <label for="expiracao_em" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Expira em (opcional)
                            </label>
                            <input type="datetime-local" name="expiracao_em" id="expiracao_em" value="{{ old('expiracao_em') }}"
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('expiracao_em')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3 pt-5 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <a href="{{ route('admin.simulados.index') }}" class="inline-flex items-center px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded-md shadow-sm text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 bg-white dark:bg-hcp-secondary-800 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-hcp-primary-600 hover:bg-hcp-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                            Criar Simulado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>