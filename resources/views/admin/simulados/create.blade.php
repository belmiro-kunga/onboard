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

                    <!-- Configurações do Simulado -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tempo Limite -->
                        <div>
                            <label for="time_limit" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Tempo Limite (minutos) <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit', 60) }}" min="1" required
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('time_limit')
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

                        <!-- Máximo de Tentativas -->
                        <div>
                            <label for="max_attempts" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Máximo de Tentativas <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="max_attempts" id="max_attempts" value="{{ old('max_attempts', 3) }}" min="1" required
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('max_attempts')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Ativar Simulado
                            </label>
                        </div>
                    </div>

                    <!-- Período de Disponibilidade -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Data de Início -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Data de Início (opcional)
                            </label>
                            <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Data de Término -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Data de Término (opcional)
                            </label>
                            <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="mt-1 block w-full rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            @error('end_date')
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