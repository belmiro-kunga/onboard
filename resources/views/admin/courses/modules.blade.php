<x-layouts.admin title="Gerenciar Módulos - {$course->title}">
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Módulos do Curso: {{ $course->title }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gerencie os módulos deste curso. Arraste e solte para reordenar.
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.courses.show', $course) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Voltar para o Curso
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Lista de Módulos do Curso -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Módulos Atuais ({{ $modules->count() }})
                        </h3>
                    </div>
                    
                    @if($modules->isNotEmpty())
                        <ul id="sortable-modules" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($modules as $module)
                                <li class="module-item bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700" data-id="{{ $module->id }}">
                                    <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="handle mr-3 text-gray-400 hover:text-gray-500 cursor-move">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                                </svg>
                                            </span>
                                            <div>
                                                <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                                    {{ $module->title }}
                                                </div>
                                                <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="mr-2">
                                                        {{ $module->formatted_difficulty ?? ucfirst($module->difficulty_level) }}
                                                    </span>
                                                    <span class="mx-1">•</span>
                                                    <span>
                                                        {{ $module->formatted_duration ?? $module->estimated_duration . ' min' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex">
                                            <a href="{{ route('admin.modules.edit', $module) }}" 
                                               class="mr-3 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.courses.modules.remove', ['course' => $course->id, 'module' => $module->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                                        onclick="return confirm('Tem certeza que deseja remover este módulo do curso?')">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-4 py-5 sm:p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum módulo adicionado</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Adicione módulos ao curso usando o formulário ao lado.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Adicionar Módulo -->
            <div>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Adicionar Módulo
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('admin.courses.modules.add', $course) }}" method="POST">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="module_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Selecione um módulo
                                    </label>
                                    <select id="module_id" name="module_id" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                            required>
                                        <option value="">Selecione um módulo</option>
                                        @foreach($availableModules as $module)
                                            <option value="{{ $module->id }}">
                                                {{ $module->title }} ({{ $module->formatted_difficulty ?? $module->difficulty_level }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('module_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="order_index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Ordem no Curso (opcional)
                                    </label>
                                    <input type="number" name="order_index" id="order_index" 
                                           class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                           min="1" 
                                           value="{{ $modules->count() + 1 }}">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Deixe em branco para adicionar ao final.
                                    </p>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" 
                                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Adicionar ao Curso
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-6">
                            <a href="{{ route('admin.modules.create') }}" 
                               class="w-full flex justify-center py-2 px-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Criar Novo Módulo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortable = document.getElementById('sortable-modules');
            
            if (sortable) {
                new Sortable(sortable, {
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function(evt) {
                        const modules = Array.from(sortable.querySelectorAll('.module-item')).map((item, index) => ({
                            id: item.dataset.id,
                            order: index + 1
                        }));

                        // Enviar a nova ordem para o servidor
                        fetch('{{ route("admin.courses.course.modules.reorder", $course) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ modules })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                console.error('Erro ao reordenar módulos');
                                // Recarregar a página para restaurar a ordem correta
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            window.location.reload();
                        });
                    }
                });
            }
        });
    </script>
    <style>
        .sortable-ghost {
            opacity: 0.5;
            background: #c7d2fe;
        }
        .handle {
            cursor: move;
            cursor: -webkit-grabbing;
        }
    </style>
    @endpush
</x-layouts.admin>
