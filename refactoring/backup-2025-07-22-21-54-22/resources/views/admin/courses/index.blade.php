<x-layouts.admin title="Gerenciar Cursos">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Gerenciar Cursos</h1>
                            <p class="text-purple-100 mt-1">Administre os cursos disponíveis na plataforma</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.courses.create') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Novo Curso</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Estatísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
                <!-- Total de Cursos -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Total de Cursos</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-blue-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cursos Ativos -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Cursos Ativos</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['active'] }}</p>
                        </div>
                        <div class="bg-green-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cursos Inativos -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Cursos Inativos</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['inactive'] }}</p>
                        </div>
                        <div class="bg-red-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cursos Obrigatórios -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Obrigatórios</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['mandatory'] }}</p>
                        </div>
                        <div class="bg-red-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.464-.833-3.234 0L3.34 16.5C2.57 17.333 3.532 19 5.072 19z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cursos Opcionais -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Opcionais</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['optional'] }}</p>
                        </div>
                        <div class="bg-blue-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cursos de Certificação -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Certificações</p>
                            <p class="text-white text-3xl font-bold">{{ $stats['certification'] }}</p>
                        </div>
                        <div class="bg-yellow-500/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6 mb-8">
                <form action="{{ route('admin.courses.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 flex-grow">
                        <!-- Busca -->
                        <div class="relative flex-grow">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar cursos..." class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 pl-10 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 placeholder-slate-400">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Filtro por Tipo -->
                        <div class="w-full sm:w-auto">
                            <select name="type" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <option value="">Todos os Tipos</option>
                                <option value="mandatory" {{ request('type') === 'mandatory' ? 'selected' : '' }}>Obrigatórios</option>
                                <option value="optional" {{ request('type') === 'optional' ? 'selected' : '' }}>Opcionais</option>
                                <option value="certification" {{ request('type') === 'certification' ? 'selected' : '' }}>Certificações</option>
                            </select>
                        </div>

                        <!-- Filtro por Status -->
                        <div class="w-full sm:w-auto">
                            <select name="status" class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <option value="">Todos os Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-300">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Lista de Cursos -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                <div class="p-6 border-b border-slate-700/50">
                    <h3 class="text-xl font-bold text-white">Lista de Cursos</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-700/30 border-b border-slate-700/50">
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Curso</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Módulos</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @forelse($courses as $course)
                                <tr class="hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden bg-slate-700">
                                                @if($course->thumbnail)
                                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-12 w-12 object-cover">
                                                @else
                                                    <div class="h-12 w-12 flex items-center justify-center bg-purple-500/20">
                                                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-white">{{ $course->title }}</div>
                                                <div class="text-xs text-slate-400">{{ Str::limit($course->short_description ?? $course->description, 60) }}</div>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300">
                                                        {{ $course->difficulty_label }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                                                        {{ $course->duration_hours }} horas
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($course->type === 'mandatory')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                                Obrigatório
                                            </span>
                                        @elseif($course->type === 'optional')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                                                Opcional
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-300">
                                                Certificação
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">{{ $course->modules()->count() }}</div>
                                        <div class="text-xs text-slate-400">{{ $course->modules()->where('is_active', true)->count() }} ativos</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($course->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                                <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form id="toggle-course-{{ $course->id }}" action="{{ route('admin.courses.toggle-active', $course) }}" method="POST" class="inline" onsubmit="return showToggleCourseModal(this);">
                                                @csrf
                                                <label class="inline-flex relative items-center cursor-pointer">
                                                    <input type="checkbox"
                                                        {{ $course->is_active ? 'checked' : '' }}
                                                        onclick="event.preventDefault(); showToggleCourseModal(this.form);"
                                                        class="sr-only peer"
                                                        aria-label="Ativar/Desativar curso">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 dark:bg-gray-700 rounded-full peer peer-checked:bg-green-500 transition-all duration-300"></div>
                                                    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow-md transition-all duration-300 peer-checked:translate-x-5"></div>
                                                </label>
                                            </form>
                                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este curso? Esta ação não pode ser desfeita.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-lg font-medium">Nenhum curso encontrado</p>
                                            <p class="text-sm mt-1">Tente ajustar os filtros ou crie um novo curso</p>
                                            <a href="{{ route('admin.courses.create') }}" class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-xl font-medium transition-all duration-300">
                                                Criar Curso
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="p-6 border-t border-slate-700/50">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

@push('scripts')
<script>
    function showToggleCourseModal(form) {
        const modal = document.getElementById('toggleCourseModal');
        modal.classList.remove('hidden');
        modal.dataset.formId = form.id;
        return false;
    }
    function hideToggleCourseModal() {
        document.getElementById('toggleCourseModal').classList.add('hidden');
    }
    function confirmToggleCourse() {
        const modal = document.getElementById('toggleCourseModal');
        const formId = modal.dataset.formId;
        if (formId) {
            document.getElementById(formId).submit();
        }
        hideToggleCourseModal();
    }
</script>
@endpush

<!-- Modal de confirmação -->
<div id="toggleCourseModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 max-w-md w-full text-center">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Confirmação</h2>
        <p class="mb-6 text-gray-700 dark:text-gray-300">Tem certeza que deseja bloquear/desbloquear este curso?</p>
        <div class="flex justify-center gap-4">
            <button onclick="confirmToggleCourse()" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Sim</button>
            <button onclick="hideToggleCourseModal()" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancelar</button>
        </div>
    </div>
</div>