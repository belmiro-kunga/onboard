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
            <div class="lg:col-span-2">
                <label for="title" class="block text-sm font-semibold text-white mb-2">Título do Curso *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400" placeholder="Ex: Onboarding da Empresa">
                @error('title')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="lg:col-span-2">
                <label for="short_description" class="block text-sm font-semibold text-white mb-2">Descrição Curta</label>
                <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400" placeholder="Resumo do curso em uma linha">
                @error('short_description')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-semibold text-white mb-2">Tipo do Curso *</label>
                <select name="type" id="type" required class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Selecione o tipo</option>
                    <option value="mandatory" {{ old('type') === 'mandatory' ? 'selected' : '' }}>Obrigatório</option>
                    <option value="optional" {{ old('type') === 'optional' ? 'selected' : '' }}>Opcional</option>
                    <option value="certification" {{ old('type') === 'certification' ? 'selected' : '' }}>Certificação</option>
                </select>
                @error('type')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="difficulty_level" class="block text-sm font-semibold text-white mb-2">Nível de Dificuldade *</label>
                <select name="difficulty_level" id="difficulty_level" required class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Selecione a dificuldade</option>
                    <option value="beginner" {{ old('difficulty_level') === 'beginner' ? 'selected' : '' }}>Iniciante</option>
                    <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                    <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>Avançado</option>
                </select>
                @error('difficulty_level')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="duration_hours" class="block text-sm font-semibold text-white mb-2">Duração Estimada (horas)</label>
                <input type="number" name="duration_hours" id="duration_hours" value="{{ old('duration_hours') }}" min="0" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400" placeholder="Ex: 8">
                @error('duration_hours')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="order_index" class="block text-sm font-semibold text-white mb-2">Ordem de Exibição</label>
                <input type="number" name="order_index" id="order_index" value="{{ old('order_index', 0) }}" min="0" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400" placeholder="0">
                @error('order_index')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label for="description" class="block text-sm font-semibold text-white mb-2">Descrição Completa *</label>
            <textarea name="description" id="description" rows="6" required class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400" placeholder="Descreva detalhadamente o conteúdo e objetivos do curso...">{{ old('description') }}</textarea>
            @error('description')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="tags" class="block text-sm font-semibold text-white mb-2">Tags</label>
            <input type="text" name="tags" id="tags" value="{{ old('tags') }}" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400" placeholder="Ex: onboarding, rh, cultura, separadas por vírgula">
            <p class="mt-1 text-xs text-slate-400">Separe as tags com vírgulas</p>
            @error('tags')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>
    </div>
</div> 