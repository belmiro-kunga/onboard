@extends('layouts.admin')

@section('title', 'Módulo: ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('admin.modules.index') }}" class="hover:underline">Módulos</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-hcp-primary-600 dark:text-hcp-primary-400">{{ $module->title }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white mb-1">{{ $module->title }}</h1>
            <div class="flex flex-wrap gap-2 items-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $module->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $module->is_active ? 'Ativo' : 'Inativo' }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ ucfirst($module->category ?? 'N/A') }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    {{ $difficultyLabels[$module->difficulty_level] ?? ucfirst($module->difficulty_level) ?? 'N/A' }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    Ordem: {{ $module->order_index }}
                </span>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.modules.edit', $module) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('admin.modules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md shadow-sm hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Coluna principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Card de informações principais -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 text-hcp-secondary-900 dark:text-white">Informações do Módulo</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Título</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $module->title }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Categoria</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $module->category ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Ordem</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $module->order_index }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Pontos de Recompensa</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $module->points_reward ?? 0 }} pontos</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Duração Estimada</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $module->estimated_duration ?? 0 }} minutos</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Dificuldade</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $difficultyLabels[$module->difficulty_level] ?? ucfirst($module->difficulty_level) ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Tipo de Conteúdo</dt>
                        <dd class="text-gray-900 dark:text-white">{{ ucfirst($module->content_type ?? 'N/A') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Curso</dt>
                        <dd class="text-gray-900 dark:text-white">
                            @if($module->course)
                                <a href="{{ route('admin.courses.show', $module->course) }}" class="text-indigo-600 hover:underline">
                                    {{ $module->course->title }}
                                </a>
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <dt class="font-medium text-gray-700 dark:text-gray-300">Descrição</dt>
                    <dd class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded p-4 mt-1">{!! nl2br(e($module->description)) !!}</dd>
                </div>
                @if($module->prerequisites)
                    <div class="mt-6">
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Pré-requisitos</dt>
                        <dd class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded p-4 mt-1">{!! nl2br(e($module->prerequisites)) !!}</dd>
                    </div>
                @endif
                @if($module->thumbnail)
                    <div class="mt-6">
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Thumbnail</dt>
                        <img src="{{ asset('storage/' . $module->thumbnail) }}" alt="{{ $module->title }}" class="rounded shadow mt-2 max-w-xs">
                    </div>
                @endif
            </div>

            <!-- Conteúdos do Módulo -->
            @if($module->contents && $module->contents->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4 text-hcp-secondary-900 dark:text-white">Conteúdos do Módulo</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th>Ordem</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Duração</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($module->contents as $content)
                                    <tr>
                                        <td>{{ $content->order_index }}</td>
                                        <td>{{ $content->title }}</td>
                                        <td>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800">
                                                {{ ucfirst($content->content_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($content->duration)
                                                {{ $content->duration }} min
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $content->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $content->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Estatísticas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-hcp-secondary-900 dark:text-white">Estatísticas</h2>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-300">Total Usuários</span>
                        <span class="font-bold text-hcp-primary-600 dark:text-hcp-primary-400">{{ $totalUsers }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-300">Completaram</span>
                        <span class="font-bold text-green-600">{{ $completedUsers }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-300">Taxa de Conclusão</span>
                        <span class="font-bold text-purple-600">{{ number_format($completionRate, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-300">Tempo Médio</span>
                        <span class="font-bold text-blue-600">{{ $averageTime }} min</span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-hcp-secondary-900 dark:text-white">Ações Rápidas</h2>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('admin.modules.edit', $module) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700">
                        <i class="fas fa-edit mr-2"></i> Editar Módulo
                    </a>
                    @if($module->course)
                        <a href="{{ route('admin.courses.modules.index', $module->course) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-md shadow-sm hover:bg-blue-200">
                            <i class="fas fa-list mr-2"></i> Ver Módulos do Curso
                        </a>
                    @endif
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-md shadow-sm hover:bg-yellow-200" onclick="toggleActive({{ $module->id }})">
                        <i class="fas fa-{{ $module->is_active ? 'toggle-off' : 'toggle-on' }} mr-2"></i>
                        {{ $module->is_active ? 'Desativar' : 'Ativar' }}
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-md shadow-sm hover:bg-red-200" onclick="deleteModule({{ $module->id }})">
                        <i class="fas fa-trash mr-2"></i> Excluir Módulo
                    </button>
                </div>
            </div>

            <!-- Informações Técnicas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-hcp-secondary-900 dark:text-white">Informações Técnicas</h2>
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    <div><strong>ID:</strong> {{ $module->id }}</div>
                    <div><strong>Criado em:</strong> {{ $module->created_at->format('d/m/Y H:i') }}</div>
                    <div><strong>Atualizado em:</strong> {{ $module->updated_at->format('d/m/Y H:i') }}</div>
                    @if($module->deleted_at)
                        <div><strong>Excluído em:</strong> {{ $module->deleted_at->format('d/m/Y H:i') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleActive(moduleId) {
    if (confirm('Deseja alterar o status deste módulo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.modules.index') }}/${moduleId}/toggle-active`;
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
function deleteModule(moduleId) {
    if (confirm('Tem certeza que deseja excluir este módulo? Esta ação não pode ser desfeita.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.modules.index') }}/${moduleId}`;
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush