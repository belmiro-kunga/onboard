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
        <div>
            <label for="thumbnail" class="block text-sm font-semibold text-white mb-2">Imagem de Capa</label>
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
            @error('thumbnail')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-white mb-2">Status do Curso</label>
            <div class="flex items-center p-4 bg-slate-800/50 rounded-xl border border-slate-700/50">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-slate-500 rounded bg-slate-700">
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