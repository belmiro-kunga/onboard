<x-layouts.admin title="Criar Curso">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.courses.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white p-3 rounded-xl transition-all duration-300 hover:scale-105">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Criar Novo Curso</h1>
                            <p class="text-purple-100 mt-1">Configure um novo curso de aprendizado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Informações Básicas -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500/10 to-blue-500/10 border-b border-slate-700/50 p-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Informações Básicas
                        </h3>
                        <p class="text-slate-400 mt-1">Dados principais do curso</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Título -->
                            <div class="lg:col-span-2">
                                <label for="title" class="block text-sm font-semibold text-white mb-2">
                                    Título do Curso *
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                       class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"
                                       placeholder="Ex: Onboarding da Empresa">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descrição Curta -->
                            <div class="lg:col-span-2">
                                <label for="short_description" class="block text-sm font-semibold text-white mb-2">
                                    Descrição Curta
                                </label>
                                <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}"
                                       class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"
                                       placeholder="Resumo do curso em uma linha">
                                @error('short_description')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="type" class="block text-sm font-semibold text-white mb-2">
                                    Tipo do Curso *
                                </label>
                                <select name="type" id="type" required
                                        class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                    <option value="">Selecione o tipo</option>
                                    <option value="mandatory" {{ old('type') === 'mandatory' ? 'selected' : '' }}>Obrigatório</option>
                                    <option value="optional" {{ old('type') === 'optional' ? 'selected' : '' }}>Opcional</option>
                                    <option value="certification" {{ old('type') === 'certification' ? 'selected' : '' }}>Certificação</option>
                                </select>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dificuldade -->
                            <div>
                                <label for="difficulty_level" class="block text-sm font-semibold text-white mb-2">
                                    Nível de Dificuldade *
                                </label>
                                <select name="difficulty_level" id="difficulty_level" required
                                        class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                    <option value="">Selecione a dificuldade</option>
                                    <option value="beginner" {{ old('difficulty_level') === 'beginner' ? 'selected' : '' }}>Iniciante</option>
                                    <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                                    <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>Avançado</option>
                                </select>
                                @error('difficulty_level')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duração -->
                            <div>
                                <label for="duration_hours" class="block text-sm font-semibold text-white mb-2">
                                    Duração Estimada (horas)
                                </label>
                                <input type="number" name="duration_hours" id="duration_hours" value="{{ old('duration_hours') }}" min="0"
                                       class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"
                                       placeholder="Ex: 8">
                                @error('duration_hours')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label for="order_index" class="block text-sm font-semibold text-white mb-2">
                                    Ordem de Exibição
                                </label>
                                <input type="number" name="order_index" id="order_index" value="{{ old('order_index', 0) }}" min="0"
                                       class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"
                                       placeholder="0">
                                @error('order_index')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descrição Completa -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-white mb-2">
                                Descrição Completa *
                            </label>
                            <textarea name="description" id="description" rows="6" required
                                      class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"
                                      placeholder="Descreva detalhadamente o conteúdo e objetivos do curso...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-semibold text-white mb-2">
                                Tags
                            </label>
                            <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400"
                                   placeholder="Ex: onboarding, rh, cultura, separadas por vírgula">
                            <p class="mt-1 text-xs text-slate-400">Separe as tags com vírgulas</p>
                            @error('tags')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Imagem e Configurações -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500/10 to-teal-500/10 border-b border-slate-700/50 p-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Imagem e Configurações
                        </h3>
                        <p class="text-slate-400 mt-1">Imagem de capa e configurações avançadas</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Upload de Imagem -->
                        <div>
                            <label for="thumbnail" class="block text-sm font-semibold text-white mb-2">
                                Imagem de Capa
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-600 border-dashed rounded-xl hover:border-purple-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-slate-400">
                                        <label for="thumbnail" class="relative cursor-pointer bg-slate-800 rounded-md font-medium text-purple-400 hover:text-purple-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Enviar uma imagem</span>
                                            <input id="thumbnail" name="thumbnail" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">ou arraste e solte</p>
                                    </div>
                                    <p class="text-xs text-slate-400">PNG, JPG, GIF até 2MB</p>
                                </div>
                            </div>
                            @error('thumbnail')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-white mb-2">
                                Status do Curso
                            </label>
                            <div class="flex items-center p-4 bg-slate-800/50 rounded-xl border border-slate-700/50">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-slate-500 rounded bg-slate-700">
                                <label for="is_active" class="ml-3 flex items-center">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                        <span class="text-white font-medium">Curso Ativo</span>
                                    </div>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">Cursos inativos não ficam visíveis para os usuários</p>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-between items-center pt-6">
                    <a href="{{ route('admin.courses.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-slate-600 text-slate-300 bg-slate-800/50 hover:bg-slate-700/50 rounded-xl font-medium transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                    
                    <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-purple-500/25">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Criar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview da imagem
        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Criar preview da imagem
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'mt-4 max-w-xs rounded-lg shadow-lg';
                    
                    // Remover preview anterior se existir
                    const existingPreview = document.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    // Adicionar novo preview
                    preview.className += ' image-preview';
                    e.target.parentElement.parentElement.parentElement.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });

        // Validação em tempo real
        document.getElementById('title').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.remove('border-red-500');
                this.classList.add('border-slate-600');
            }
        });

        // Auto-resize do textarea
        document.getElementById('description').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
</x-layouts.admin>