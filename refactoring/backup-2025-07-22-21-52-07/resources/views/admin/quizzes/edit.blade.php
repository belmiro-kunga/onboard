<x-layouts.admin title="Editar Quiz - {{ $quiz->title }}">
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
                            Editar Quiz
                        </h1>
                        <p class="mt-2 text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            {{ $quiz->title }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-sm">
                <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informações Básicas -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Informações Básicas
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Título do Quiz *
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}" required
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                       placeholder="Ex: Fundamentos de Recursos Humanos">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Descrição *
                                </label>
                                <textarea id="description" name="description" rows="3" required
                                          class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                          placeholder="Descreva o objetivo e conteúdo do quiz...">{{ old('description', $quiz->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="instructions" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Instruções
                                </label>
                                <textarea id="instructions" name="instructions" rows="2"
                                          class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white"
                                          placeholder="Instruções específicas para os participantes...">{{ old('instructions', $quiz->instructions) }}</textarea>
                                @error('instructions')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Categoria *
                                </label>
                                <select id="category" name="category" required
                                        class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                    <option value="">Selecione uma categoria</option>
                                    <option value="hr" {{ old('category', $quiz->category) === 'hr' ? 'selected' : '' }}>Recursos Humanos</option>
                                    <option value="it" {{ old('category', $quiz->category) === 'it' ? 'selected' : '' }}>Tecnologia da Informação</option>
                                    <option value="security" {{ old('category', $quiz->category) === 'security' ? 'selected' : '' }}>Segurança</option>
                                    <option value="processes" {{ old('category', $quiz->category) === 'processes' ? 'selected' : '' }}>Processos</option>
                                    <option value="culture" {{ old('category', $quiz->category) === 'culture' ? 'selected' : '' }}>Cultura Organizacional</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="difficulty_level" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Nível de Dificuldade *
                                </label>
                                <select id="difficulty_level" name="difficulty_level" required
                                        class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                    <option value="">Selecione o nível</option>
                                    <option value="basic" {{ old('difficulty_level', $quiz->difficulty_level) === 'basic' ? 'selected' : '' }}>Básico</option>
                                    <option value="intermediate" {{ old('difficulty_level', $quiz->difficulty_level) === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                                    <option value="advanced" {{ old('difficulty_level', $quiz->difficulty_level) === 'advanced' ? 'selected' : '' }}>Avançado</option>
                                </select>
                                @error('difficulty_level')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="module_id" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Módulo Relacionado
                                </label>
                                <select id="module_id" name="module_id"
                                        class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                    <option value="">Nenhum módulo específico</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" {{ old('module_id', $quiz->module_id) == $module->id ? 'selected' : '' }}>
                                            {{ $module->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('module_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configurações de Pontuação -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Configurações de Pontuação
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="passing_score" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Pontuação Mínima (%) *
                                </label>
                                <input type="number" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" 
                                       min="1" max="100" required
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                @error('passing_score')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_attempts" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Máximo de Tentativas *
                                </label>
                                <input type="number" id="max_attempts" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" 
                                       min="1" max="10" required
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                @error('max_attempts')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="points_reward" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Pontos de Recompensa *
                                </label>
                                <input type="number" id="points_reward" name="points_reward" value="{{ old('points_reward', $quiz->points_reward) }}" 
                                       min="0" max="1000" required
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                @error('points_reward')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configurações de Tempo -->
                    <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Configurações de Tempo
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="time_limit" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                                    Limite de Tempo (minutos)
                                </label>
                                <input type="number" id="time_limit" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" 
                                       min="1" max="300" placeholder="Deixe vazio para sem limite"
                                       class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg focus:ring-2 focus:ring-hcp-primary-500 focus:border-transparent bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white">
                                <p class="mt-1 text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                    Deixe vazio se não houver limite de tempo
                                </p>
                                @error('time_limit')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configurações Avançadas -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white mb-4">
                            Configurações Avançadas
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="randomize_questions" name="randomize_questions" value="1" 
                                       {{ old('randomize_questions', $quiz->randomize_questions) ? 'checked' : '' }}
                                       class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded">
                                <label for="randomize_questions" class="ml-2 block text-sm text-hcp-secondary-900 dark:text-white">
                                    Randomizar ordem das questões
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" 
                                       {{ old('show_results_immediately', $quiz->show_results_immediately) ? 'checked' : '' }}
                                       class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded">
                                <label for="show_results_immediately" class="ml-2 block text-sm text-hcp-secondary-900 dark:text-white">
                                    Mostrar resultados imediatamente após conclusão
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="allow_review" name="allow_review" value="1" 
                                       {{ old('allow_review', $quiz->allow_review) ? 'checked' : '' }}
                                       class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded">
                                <label for="allow_review" class="ml-2 block text-sm text-hcp-secondary-900 dark:text-white">
                                    Permitir revisão das respostas
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="generate_certificate" name="generate_certificate" value="1" 
                                       {{ old('generate_certificate', $quiz->generate_certificate) ? 'checked' : '' }}
                                       class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded">
                                <label for="generate_certificate" class="ml-2 block text-sm text-hcp-secondary-900 dark:text-white">
                                    Gerar certificado digital para aprovados
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $quiz->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-hcp-primary-600 focus:ring-hcp-primary-500 border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-hcp-secondary-900 dark:text-white">
                                    Quiz ativo (visível para os usuários)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-hcp-primary-600 hover:bg-hcp-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Atualizar Quiz
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
</x-layouts.admin> 