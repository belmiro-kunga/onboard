<x-layouts.admin title="Criar Novo Módulo">
    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Novo Módulo</h1>
        <form action="{{ route('admin.modules.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria *</label>
                    <input type="text" name="category" id="category" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="order_index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordem *</label>
                    <input type="number" name="order_index" id="order_index" value="0" min="0" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="points_reward" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pontos de Recompensa *</label>
                    <input type="number" name="points_reward" id="points_reward" value="0" min="0" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="estimated_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duração Estimada (min) *</label>
                    <input type="number" name="estimated_duration" id="estimated_duration" value="30" min="1" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="difficulty_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nível de Dificuldade *</label>
                    <select name="difficulty_level" id="difficulty_level" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="basic">Básico</option>
                        <option value="intermediate">Intermediário</option>
                        <option value="advanced">Avançado</option>
                    </select>
                </div>
                <div>
                    <label for="content_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Conteúdo *</label>
                    <select name="content_type" id="content_type" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="video">Vídeo</option>
                        <option value="text">Texto</option>
                        <option value="interactive">Interativo</option>
                        <option value="document">Documento</option>
                    </select>
                </div>
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                </div>
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ativo?</label>
                    <input type="checkbox" name="is_active" id="is_active" checked class="mt-2">
                </div>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição *</label>
                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div>
                <label for="prerequisites" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pré-requisitos (opcional)</label>
                <select name="prerequisites[]" id="prerequisites" multiple class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($allModules as $mod)
                        <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Salvar</button>
                <a href="{{ route('admin.modules.index') }}" class="ml-4 text-gray-600 dark:text-gray-300 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.admin> 