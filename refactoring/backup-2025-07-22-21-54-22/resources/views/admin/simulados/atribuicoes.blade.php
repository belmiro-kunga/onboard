<x-layouts.admin title="Atribuir Simulado a Usuários - HCP">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            👥 Atribuir Simulado a Usuários
                        </h1>
                        <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Selecione os usuários que deverão realizar o simulado "{{ $simulado->title }}".
                        </p>
                    </div>
                    <a href="{{ route('admin.simulados.show', $simulado) }}" class="inline-flex items-center px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded-md shadow-sm text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 bg-white dark:bg-hcp-secondary-800 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Formulário de Atribuição -->
            <div class="bg-white dark:bg-hcp-secondary-800 overflow-hidden shadow-sm rounded-lg">
                <form action="{{ route('admin.simulados.atribuir', $simulado) }}" method="POST" class="p-6">
                    @csrf

                    <!-- Barra de Pesquisa -->
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">
                            Buscar Usuários
                        </label>
                        <div class="relative">
                            <input type="text" id="search" placeholder="Digite para filtrar usuários..."
                                class="block w-full pl-10 pr-3 py-2 rounded-md border-hcp-secondary-300 dark:border-hcp-secondary-700 dark:bg-hcp-secondary-900 dark:text-white shadow-sm focus:border-hcp-primary-500 focus:ring focus:ring-hcp-primary-500 focus:ring-opacity-50">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-hcp-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Seleção de Usuários -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                Selecione os Usuários
                            </label>
                            <div class="flex space-x-2">
                                <button type="button" id="select-all" class="text-xs text-hcp-primary-600 hover:text-hcp-primary-800 dark:text-hcp-primary-400 dark:hover:text-hcp-primary-300">
                                    Selecionar Todos
                                </button>
                                <button type="button" id="deselect-all" class="text-xs text-hcp-secondary-600 hover:text-hcp-secondary-800 dark:text-hcp-secondary-400 dark:hover:text-hcp-secondary-300">
                                    Desmarcar Todos
                                </button>
                            </div>
                        </div>

                        <div class="border border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded-md overflow-hidden">
                            <div class="max-h-96 overflow-y-auto p-2 bg-white dark:bg-hcp-secondary-900">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2" id="users-container">
                                    @foreach($usuarios as $usuario)
                                        <div class="user-item flex items-center p-2 rounded-md hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-800" data-name="{{ strtolower($usuario->name) }}" data-email="{{ strtolower($usuario->email) }}">
                                            <input type="checkbox" name="user_ids[]" id="user-{{ $usuario->id }}" value="{{ $usuario->id }}" 
                                                {{ in_array($usuario->id, $usuariosAtribuidos) ? 'checked' : '' }}
                                                class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded">
                                            <label for="user-{{ $usuario->id }}" class="ml-2 block text-sm text-hcp-secondary-900 dark:text-hcp-secondary-100 cursor-pointer">
                                                <div>{{ $usuario->name }}</div>
                                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ $usuario->email }}</div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('user_ids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3 pt-5 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <a href="{{ route('admin.simulados.show', $simulado) }}" class="inline-flex items-center px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-700 rounded-md shadow-sm text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 bg-white dark:bg-hcp-secondary-800 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-hcp-primary-600 hover:bg-hcp-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                            Salvar Atribuições
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const userItems = document.querySelectorAll('.user-item');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            
            // Função para filtrar usuários
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                userItems.forEach(item => {
                    const name = item.dataset.name;
                    const email = item.dataset.email;
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            
            // Selecionar todos
            selectAllBtn.addEventListener('click', function() {
                userItems.forEach(item => {
                    if (item.style.display !== 'none') {
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        checkbox.checked = true;
                    }
                });
            });
            
            // Desmarcar todos
            deselectAllBtn.addEventListener('click', function() {
                userItems.forEach(item => {
                    if (item.style.display !== 'none') {
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        checkbox.checked = false;
                    }
                });
            });
            
            // Permitir clicar em toda a área para selecionar
            userItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        const checkbox = this.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.admin>