<x-layouts.admin title="Nova Atribuição">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Nova Atribuição</h1>
                            <p class="text-indigo-100 mt-1">Atribua cursos, quizzes ou módulos aos usuários</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.assignments.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Voltar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                <div class="p-6 border-b border-slate-700/50">
                    <h3 class="text-xl font-bold text-white">Criar Nova Atribuição</h3>
                    <p class="text-slate-400 mt-1">Selecione os usuários e o item que deseja atribuir</p>
                </div>

                <form action="{{ route('admin.assignments.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Seleção de Usuários -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-3">
                            Usuários *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto bg-slate-700/30 rounded-xl p-4">
                            @foreach($users as $user)
                                <label class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-600/30 transition-colors cursor-pointer">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                           {{ $selectedUser && $selectedUser->id === $user->id ? 'checked' : '' }}
                                           class="rounded border-slate-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <div class="flex items-center space-x-2">
                                        <div class="h-8 w-8 rounded-full bg-indigo-500/20 flex items-center justify-center">
                                            <span class="text-xs font-medium text-indigo-300">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $user->department }}</div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('user_ids')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Item -->
                    <div>
                        <label for="assignable_type" class="block text-sm font-medium text-slate-300 mb-2">
                            Tipo de Item *
                        </label>
                        <select name="assignable_type" id="assignable_type" required
                                class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                            <option value="">Selecione o tipo</option>
                            <option value="course">Curso</option>
                            <option value="quiz">Quiz</option>
                            <option value="module">Módulo</option>
                        </select>
                        @error('assignable_type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Seleção do Item -->
                    <div id="item-selection" style="display: none;">
                        <!-- Cursos -->
                        <div id="courses-section" style="display: none;">
                            <label for="course_id" class="block text-sm font-medium text-slate-300 mb-2">
                                Curso *
                            </label>
                            <select name="course_id" id="course_id"
                                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                                <option value="">Selecione um curso</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quizzes -->
                        <div id="quizzes-section" style="display: none;">
                            <label for="quiz_id" class="block text-sm font-medium text-slate-300 mb-2">
                                Quiz *
                            </label>
                            <select name="quiz_id" id="quiz_id"
                                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                                <option value="">Selecione um quiz</option>
                                @foreach($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Módulos -->
                        <div id="modules-section" style="display: none;">
                            <label for="module_id" class="block text-sm font-medium text-slate-300 mb-2">
                                Módulo *
                            </label>
                            <select name="module_id" id="module_id"
                                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                                <option value="">Selecione um módulo</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Prazo -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-slate-300 mb-2">
                            Prazo (opcional)
                        </label>
                        <input type="date" name="due_date" id="due_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Obrigatório -->
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_mandatory" value="1"
                                   class="rounded border-slate-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-slate-300">Esta atribuição é obrigatória</span>
                        </label>
                    </div>

                    <!-- Observações -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                            Observações (opcional)
                        </label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Adicione observações sobre esta atribuição..."
                                  class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-slate-700/50">
                        <a href="{{ route('admin.assignments.index') }}" 
                           class="px-6 py-3 border border-slate-600 text-slate-300 rounded-xl hover:bg-slate-700/50 transition-all duration-300">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all duration-300">
                            Criar Atribuição
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('assignable_type');
            const itemSelection = document.getElementById('item-selection');
            const coursesSection = document.getElementById('courses-section');
            const quizzesSection = document.getElementById('quizzes-section');
            const modulesSection = document.getElementById('modules-section');

            typeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                
                // Esconder todas as seções
                coursesSection.style.display = 'none';
                quizzesSection.style.display = 'none';
                modulesSection.style.display = 'none';
                
                // Limpar valores
                document.getElementById('course_id').value = '';
                document.getElementById('quiz_id').value = '';
                document.getElementById('module_id').value = '';
                
                if (selectedType) {
                    itemSelection.style.display = 'block';
                    
                    // Mostrar seção apropriada
                    switch(selectedType) {
                        case 'course':
                            coursesSection.style.display = 'block';
                            break;
                        case 'quiz':
                            quizzesSection.style.display = 'block';
                            break;
                        case 'module':
                            modulesSection.style.display = 'block';
                            break;
                    }
                } else {
                    itemSelection.style.display = 'none';
                }
            });

            // Atualizar campo assignable_id baseado na seleção
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const type = typeSelect.value;
                let assignableId = '';
                
                switch(type) {
                    case 'course':
                        assignableId = document.getElementById('course_id').value;
                        break;
                    case 'quiz':
                        assignableId = document.getElementById('quiz_id').value;
                        break;
                    case 'module':
                        assignableId = document.getElementById('module_id').value;
                        break;
                }
                
                if (!assignableId) {
                    e.preventDefault();
                    alert('Por favor, selecione um item para atribuir.');
                    return;
                }
                
                // Criar campo hidden com o ID do item
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'assignable_id';
                hiddenInput.value = assignableId;
                form.appendChild(hiddenInput);
            });
        });
    </script>
    @endpush
</x-layouts.admin>