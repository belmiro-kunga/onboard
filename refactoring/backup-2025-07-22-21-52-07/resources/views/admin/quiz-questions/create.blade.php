<x-layouts.admin title="Criar Questão - {{ $quiz->title }}">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                       class="mr-4 p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                            Nova Questão
                        </h1>
                        <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Quiz: {{ $quiz->title }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulário Simples -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm">
                <form method="POST" action="{{ route('admin.quiz-questions.store', $quiz) }}" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Tipo de Questão - Seleção Simples -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Tipo de Questão
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="question-type-option">
                                <input type="radio" name="question_type" value="multiple_choice" class="sr-only" checked>
                                <div class="p-4 border-2 border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg text-center cursor-pointer hover:border-hcp-primary-500 transition-colors">
                                    <div class="text-2xl mb-2">☑️</div>
                                    <div class="font-medium text-hcp-secondary-900 dark:text-white">Múltipla Escolha</div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">Várias opções corretas</div>
                                </div>
                            </label>
                            
                            <label class="question-type-option">
                                <input type="radio" name="question_type" value="single_choice" class="sr-only">
                                <div class="p-4 border-2 border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg text-center cursor-pointer hover:border-hcp-primary-500 transition-colors">
                                    <div class="text-2xl mb-2">⭕</div>
                                    <div class="font-medium text-hcp-secondary-900 dark:text-white">Escolha Única</div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">Uma opção correta</div>
                                </div>
                            </label>
                            
                            <label class="question-type-option">
                                <input type="radio" name="question_type" value="drag_drop" class="sr-only">
                                <div class="p-4 border-2 border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg text-center cursor-pointer hover:border-hcp-primary-500 transition-colors">
                                    <div class="text-2xl mb-2">🔄</div>
                                    <div class="font-medium text-hcp-secondary-900 dark:text-white">Arrastar e Soltar</div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">Ordenar itens</div>
                                </div>
                            </label>
                            
                            <label class="question-type-option">
                                <input type="radio" name="question_type" value="true_false" class="sr-only">
                                <div class="p-4 border-2 border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg text-center cursor-pointer hover:border-hcp-primary-500 transition-colors">
                                    <div class="text-2xl mb-2">✅❌</div>
                                    <div class="font-medium text-hcp-secondary-900 dark:text-white">Verdadeiro/Falso</div>
                                    <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">Sim ou não</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Pergunta -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Pergunta
                        </h3>
                        
                        <textarea name="question" rows="4" required
                                  class="w-full px-4 py-3 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white resize-none"
                                  placeholder="Digite a pergunta aqui..."></textarea>
                        @error('question')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opções (Múltipla Escolha/Escolha Única) -->
                    <div id="options-section" class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Opções de Resposta
                        </h3>
                        
                        <div id="options-container" class="space-y-3">
                            <div class="option-item flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-hcp-primary-100 dark:bg-hcp-primary-900 text-hcp-primary-600 dark:text-hcp-primary-400 rounded-full flex items-center justify-center font-medium text-sm">
                                    A
                                </div>
                                <input type="text" name="options[]" 
                                       class="flex-1 px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                       placeholder="Digite a opção A">
                                <label class="flex items-center">
                                    <input type="checkbox" name="correct_answer[]" value="0" 
                                           class="w-4 h-4 text-hcp-primary-600 border-hcp-secondary-300 focus:ring-hcp-primary-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700 rounded">
                                    <span class="ml-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Correta</span>
                                </label>
                            </div>
                            
                            <div class="option-item flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-hcp-primary-100 dark:bg-hcp-primary-900 text-hcp-primary-600 dark:text-hcp-primary-400 rounded-full flex items-center justify-center font-medium text-sm">
                                    B
                                </div>
                                <input type="text" name="options[]" 
                                       class="flex-1 px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                       placeholder="Digite a opção B">
                                <label class="flex items-center">
                                    <input type="checkbox" name="correct_answer[]" value="1" 
                                           class="w-4 h-4 text-hcp-primary-600 border-hcp-secondary-300 focus:ring-hcp-primary-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700 rounded">
                                    <span class="ml-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Correta</span>
                                </label>
                            </div>
                        </div>
                        
                        <button type="button" onclick="addOption()" 
                                class="mt-4 inline-flex items-center px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 bg-white dark:bg-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Adicionar Opção
                        </button>
                    </div>

                    <!-- Arrastar e Soltar -->
                    <div id="drag-drop-section" class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6" style="display: none;">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Itens para Arrastar
                        </h3>
                        
                        <div id="drag-items-container" class="space-y-3">
                            <div class="drag-item-input flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center font-medium text-sm">
                                    1
                                </div>
                                <input type="text" name="drag_items[]" 
                                       class="flex-1 px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                       placeholder="Item para arrastar">
                            </div>
                            
                            <div class="drag-item-input flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center font-medium text-sm">
                                    2
                                </div>
                                <input type="text" name="drag_items[]" 
                                       class="flex-1 px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                       placeholder="Item para arrastar">
                            </div>
                        </div>
                        
                        <button type="button" onclick="addDragItem()" 
                                class="mt-4 inline-flex items-center px-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 bg-white dark:bg-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-primary-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Adicionar Item
                        </button>
                        
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-hcp-secondary-900 dark:text-white mb-3">
                                Ordem Correta
                            </h4>
                            <div id="correct-order-container" class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">1º lugar:</span>
                                    <select name="correct_order[]" class="px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                        <option value="0">Item 1</option>
                                        <option value="1">Item 2</option>
                                    </select>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">2º lugar:</span>
                                    <select name="correct_order[]" class="px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                        <option value="1">Item 2</option>
                                        <option value="0">Item 1</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verdadeiro/Falso -->
                    <div id="true-false-section" class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6" style="display: none;">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Resposta Correta
                        </h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors">
                                <input type="radio" name="correct_answer[]" value="true" 
                                       class="w-4 h-4 text-hcp-primary-600 border-hcp-secondary-300 focus:ring-hcp-primary-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700">
                                <span class="ml-3 text-hcp-secondary-900 dark:text-white font-medium">✅ Verdadeiro</span>
                            </label>
                            
                            <label class="flex items-center p-4 border border-hcp-secondary-200 dark:border-hcp-secondary-600 rounded-lg hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors">
                                <input type="radio" name="correct_answer[]" value="false" 
                                       class="w-4 h-4 text-hcp-primary-600 border-hcp-secondary-300 focus:ring-hcp-primary-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700">
                                <span class="ml-3 text-hcp-secondary-900 dark:text-white font-medium">❌ Falso</span>
                            </label>
                        </div>
                    </div>

                    <!-- Configurações Simples -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Configurações
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Pontos
                                </label>
                                <input type="number" name="points" value="1" min="1" max="10" required
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Ordem
                                </label>
                                <input type="number" name="order_index" value="{{ $nextOrder }}" min="1" required
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked
                                       class="w-4 h-4 text-hcp-primary-600 border-hcp-secondary-300 focus:ring-hcp-primary-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700 rounded">
                                <label class="ml-2 block text-sm text-hcp-secondary-900 dark:text-white">
                                    Questão ativa
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Explicação (Opcional) -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Explicação (Opcional)
                        </h3>
                        
                        <textarea name="explanation" rows="3"
                                  class="w-full px-4 py-3 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white resize-none"
                                  placeholder="Explicação da resposta correta (será mostrada após o quiz)..."></textarea>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Criar Questão
                        </button>
                        <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-hcp-secondary-600 hover:bg-hcp-secondary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let optionCount = 2;
        let dragItemCount = 2;

        // Seleção de tipo de questão
        document.querySelectorAll('input[name="question_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateQuestionType();
            });
        });

        function updateQuestionType() {
            const type = document.querySelector('input[name="question_type"]:checked').value;
            
            // Esconder todas as seções
            document.getElementById('options-section').style.display = 'none';
            document.getElementById('drag-drop-section').style.display = 'none';
            document.getElementById('true-false-section').style.display = 'none';
            
            // Mostrar seção apropriada
            if (type === 'multiple_choice' || type === 'single_choice') {
                document.getElementById('options-section').style.display = 'block';
                updateCorrectAnswerType(type);
            } else if (type === 'drag_drop') {
                document.getElementById('drag-drop-section').style.display = 'block';
            } else if (type === 'true_false') {
                document.getElementById('true-false-section').style.display = 'block';
            }
            
            // Atualizar seleção visual
            updateTypeSelection();
        }

        function updateTypeSelection() {
            document.querySelectorAll('.question-type-option input').forEach(radio => {
                const div = radio.parentElement.querySelector('div');
                if (radio.checked) {
                    div.classList.add('border-hcp-primary-500', 'bg-hcp-primary-50', 'dark:bg-hcp-primary-900/20');
                } else {
                    div.classList.remove('border-hcp-primary-500', 'bg-hcp-primary-50', 'dark:bg-hcp-primary-900/20');
                }
            });
        }

        function updateCorrectAnswerType(type) {
            const checkboxes = document.querySelectorAll('input[name="correct_answer[]"]');
            checkboxes.forEach(checkbox => {
                if (type === 'single_choice') {
                    checkbox.type = 'radio';
                    checkbox.name = 'correct_answer[]';
                } else {
                    checkbox.type = 'checkbox';
                    checkbox.name = 'correct_answer[]';
                }
            });
        }

        function addOption() {
            optionCount++;
            const container = document.getElementById('options-container');
            const newOption = document.createElement('div');
            newOption.className = 'option-item flex items-center space-x-3';
            
            const letter = String.fromCharCode(64 + optionCount);
            newOption.innerHTML = `
                <div class="flex-shrink-0 w-8 h-8 bg-hcp-primary-100 dark:bg-hcp-primary-900 text-hcp-primary-600 dark:text-hcp-primary-400 rounded-full flex items-center justify-center font-medium text-sm">
                    ${letter}
                </div>
                <input type="text" name="options[]" 
                       class="flex-1 px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                       placeholder="Digite a opção ${letter}">
                <label class="flex items-center">
                    <input type="checkbox" name="correct_answer[]" value="${optionCount - 1}" 
                           class="w-4 h-4 text-hcp-primary-600 border-hcp-secondary-300 focus:ring-hcp-primary-500 dark:border-hcp-secondary-600 dark:bg-hcp-secondary-700 rounded">
                    <span class="ml-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">Correta</span>
                </label>
                <button type="button" onclick="removeOption(this)" 
                        class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newOption);
        }

        function removeOption(button) {
            button.parentElement.remove();
            optionCount--;
        }

        function addDragItem() {
            dragItemCount++;
            const container = document.getElementById('drag-items-container');
            const newItem = document.createElement('div');
            newItem.className = 'drag-item-input flex items-center space-x-3';
            newItem.innerHTML = `
                <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center font-medium text-sm">
                    ${dragItemCount}
                </div>
                <input type="text" name="drag_items[]" 
                       class="flex-1 px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                       placeholder="Item para arrastar">
                <button type="button" onclick="removeDragItem(this)" 
                        class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newItem);
            updateCorrectOrder();
        }

        function removeDragItem(button) {
            button.parentElement.remove();
            dragItemCount--;
            updateCorrectOrder();
        }

        function updateCorrectOrder() {
            const container = document.getElementById('correct-order-container');
            container.innerHTML = '';
            
            for (let i = 0; i < dragItemCount; i++) {
                const div = document.createElement('div');
                div.className = 'flex items-center space-x-3';
                div.innerHTML = `
                    <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">${i + 1}º lugar:</span>
                    <select name="correct_order[]" class="px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                        ${Array.from({length: dragItemCount}, (_, j) => 
                            `<option value="${j}" ${i === j ? 'selected' : ''}>Item ${j + 1}</option>`
                        ).join('')}
                    </select>
                `;
                container.appendChild(div);
            }
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            updateQuestionType();
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .question-type-option input:checked + div {
            border-color: #3b82f6;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .question-type-option div:hover {
            border-color: #3b82f6;
        }
    </style>
    @endpush
</x-layouts.admin> 