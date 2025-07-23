<x-layouts.admin title="Editar Módulo: {$module->title}">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Mensagens de Feedback -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg dark:bg-green-900/20 dark:border-green-800 dark:text-green-300" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg dark:bg-red-900/20 dark:border-red-800 dark:text-red-300" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg dark:bg-red-900/20 dark:border-red-800 dark:text-red-300" role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <span class="font-medium">Erro ao salvar o módulo:</span>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Editar Módulo: <span class="text-indigo-600 dark:text-indigo-400">{{ $module->title }}</span>
            </h1>
            <a href="{{ route('admin.modules.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                &larr; Voltar para a lista
            </a>
        </div>
        
        <form action="{{ route('admin.modules.update', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $module->title) }}" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria *</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $module->category) }}" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="order_index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordem *</label>
                    <input type="number" name="order_index" id="order_index" value="{{ old('order_index', $module->order_index) }}" min="0" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('order_index')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="points_reward" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pontos de Recompensa *</label>
                    <input type="number" name="points_reward" id="points_reward" value="{{ old('points_reward', $module->points_reward) }}" min="0" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('points_reward')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="estimated_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duração Estimada (min) *</label>
                    <input type="number" name="estimated_duration" id="estimated_duration" value="{{ old('estimated_duration', $module->estimated_duration) }}" min="1" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('estimated_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="difficulty_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nível de Dificuldade *</label>
                    <select name="difficulty_level" id="difficulty_level" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="basic" {{ old('difficulty_level', $module->difficulty_level) === 'basic' ? 'selected' : '' }}>Básico</option>
                        <option value="intermediate" {{ old('difficulty_level', $module->difficulty_level) === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                        <option value="advanced" {{ old('difficulty_level', $module->difficulty_level) === 'advanced' ? 'selected' : '' }}>Avançado</option>
                    </select>
                    @error('difficulty_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="content_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Conteúdo *</label>
                    <select name="content_type" id="content_type" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="video" {{ old('content_type', $module->content_type) === 'video' ? 'selected' : '' }}>Vídeo</option>
                        <option value="text" {{ old('content_type', $module->content_type) === 'text' ? 'selected' : '' }}>Texto</option>
                        <option value="interactive" {{ old('content_type', $module->content_type) === 'interactive' ? 'selected' : '' }}>Interativo</option>
                        <option value="document" {{ old('content_type', $module->content_type) === 'document' ? 'selected' : '' }}>Documento</option>
                    </select>
                    @error('content_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail</label>
                    <div class="mt-1 flex items-center">
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail" 
                               accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-indigo-300 dark:hover:file:bg-gray-600"
                               onchange="validateThumbnail(this)">
                    </div>
                    
                    @if($module->thumbnail)
                        <div class="mt-3">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thumbnail atual:</span>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $module->thumbnail) }}" 
                                     alt="{{ $module->title }}" 
                                     class="h-auto w-64 rounded border border-gray-200 dark:border-gray-700">
                                <button type="button" 
                                        onclick="document.getElementById('remove-thumbnail').value = '1'; this.previousElementSibling.src = '{{ asset('images/placeholder-thumbnail.jpg') }}'"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        title="Remover thumbnail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="remove_thumbnail" id="remove-thumbnail" value="0">
                    @endif
                    
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Tamanho recomendado: 1280×720px (16:9). Tamanho máximo: 2MB. Formatos: JPG, PNG, WebP.
                    </p>
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <script>
                function validateThumbnail(input) {
                    if (input.files && input.files[0]) {
                        const file = input.files[0];
                        const fileSize = file.size / 1024 / 1024; // in MB
                        const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                        const maxSize = 2; // 2MB
                        
                        // Check file type
                        if (!validTypes.includes(file.type)) {
                            alert('Por favor, selecione uma imagem no formato JPG, PNG ou WebP.');
                            input.value = '';
                            return false;
                        }
                        
                        // Check file size
                        if (fileSize > maxSize) {
                            alert(`O arquivo é muito grande (${fileSize.toFixed(1)}MB). O tamanho máximo permitido é ${maxSize}MB.`);
                            input.value = '';
                            return false;
                        }
                        
                        // Check image dimensions
                        const img = new Image();
                        img.onload = function() {
                            const width = this.width;
                            const height = this.height;
                            const aspectRatio = width / height;
                            const targetAspectRatio = 16/9;
                            const aspectRatioTolerance = 0.1; // 10% tolerance
                            
                            if (Math.abs(aspectRatio - targetAspectRatio) > aspectRatioTolerance) {
                                if (confirm('A imagem não está na proporção 16:9 recomendada. Deseja continuar mesmo assim?')) {
                                    return true;
                                } else {
                                    input.value = '';
                                    return false;
                                }
                            }
                        };
                        img.src = URL.createObjectURL(file);
                    }
                    return true;
                }
                </script>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', $module->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Módulo ativo
                    </label>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição *</label>
                <textarea name="description" id="description" rows="4" required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $module->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="prerequisites" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pré-requisitos (máx. 1000 caracteres)</label>
                <div class="mt-1 relative">
                    <textarea name="prerequisites" id="prerequisites" rows="4" maxlength="1000"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Liste os pré-requisitos necessários para este módulo..."
                    >{{ old('prerequisites', $module->prerequisites) }}</textarea>
                    <div class="flex justify-end mt-1">
                        <span id="prerequisites-char-count" class="text-xs text-gray-500 dark:text-gray-400">0/1000</span>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Descreva os conhecimentos ou habilidades necessárias para iniciar este módulo.
                </p>
                @error('prerequisites')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <script>
                // Character counter for prerequisites field
                document.addEventListener('DOMContentLoaded', function() {
                    const prerequisitesField = document.getElementById('prerequisites');
                    const charCount = document.getElementById('prerequisites-char-count');
                    
                    if (prerequisitesField && charCount) {
                        // Update counter on page load
                        updateCharCount();
                        
                        // Update counter on input
                        prerequisitesField.addEventListener('input', updateCharCount);
                        
                        function updateCharCount() {
                            const currentLength = prerequisitesField.value.length;
                            const maxLength = parseInt(prerequisitesField.getAttribute('maxlength'));
                            charCount.textContent = `${currentLength}/${maxLength}`;
                            
                            // Change color when approaching/exceeding limit
                            if (currentLength > maxLength * 0.9) {
                                charCount.classList.add('text-red-600', 'dark:text-red-400');
                                charCount.classList.remove('text-gray-500', 'dark:text-gray-400');
                            } else {
                                charCount.classList.remove('text-red-600', 'dark:text-red-400');
                                charCount.classList.add('text-gray-500', 'dark:text-gray-400');
                            }
                        }
                    }
                });
            </script>
            
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.modules.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Atualizar Módulo
                </button>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script>
        // Adicionar validação do formulário
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(event) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    event.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                }
            });
        });
    </script>
    @endpush
</x-layouts.admin>
