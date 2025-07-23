@extends('layouts.admin')

@section('title', 'Seção: ' . $module->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $module->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.modules.index', $course) }}">Seções</a></li>
                    <li class="breadcrumb-item active">{{ $module->title }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.courses.modules.edit', [$course, $module]) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar Seção
            </a>
            <a href="{{ route('admin.courses.modules.index', $course) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações da Seção -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informações da Seção</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Título:</label>
                                <p class="mb-0">{{ $module->title }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ordem:</label>
                                <p class="mb-0">{{ $module->order_index }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Duração:</label>
                                <p class="mb-0">{{ $module->duration_minutes ?? 0 }} minutos</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <span class="badge bg-{{ $module->is_active ? 'success' : 'secondary' }}">
                                    {{ $module->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dificuldade:</label>
                                @php
                                    $difficultyColors = [
                                        'beginner' => 'success',
                                        'intermediate' => 'warning', 
                                        'advanced' => 'danger'
                                    ];
                                    $difficultyLabels = [
                                        'beginner' => 'Iniciante',
                                        'intermediate' => 'Intermediário',
                                        'advanced' => 'Avançado'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $difficultyColors[$module->difficulty_level] ?? 'secondary' }}">
                                    {{ $difficultyLabels[$module->difficulty_level] ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição:</label>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($module->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aulas da Seção -->
            @if($module->lessons && $module->lessons->count() > 0)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Aulas da Seção</h5>
                        <a href="{{ route('admin.lessons.create', ['module_id' => $module->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nova Aula
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Título</th>
                                        <th>Duração</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($module->lessons as $lesson)
                                        <tr>
                                            <td>{{ $lesson->order_index }}</td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $lesson->title }}</h6>
                                                    @if($lesson->description)
                                                        <small class="text-muted">{{ Str::limit($lesson->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($lesson->duration_minutes)
                                                    {{ $lesson->duration_minutes }} min
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($lesson->video)
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-play"></i> {{ ucfirst($lesson->video->type) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Sem vídeo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $lesson->is_active ? 'success' : 'secondary' }}">
                                                    {{ $lesson->is_active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                                @if($lesson->is_optional)
                                                    <span class="badge bg-info">Opcional</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.lessons.show', $lesson) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" 
                                                       class="btn btn-sm btn-outline-secondary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-play-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhuma aula encontrada</h5>
                        <p class="text-muted">Comece adicionando a primeira aula a esta seção.</p>
                        <a href="{{ route('admin.lessons.create', ['module_id' => $module->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Criar Primeira Aula
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar com Estatísticas -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Estatísticas</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $stats['total_lessons'] ?? 0 }}</h4>
                                <small class="text-muted">Total Aulas</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-0">{{ $stats['active_lessons'] ?? 0 }}</h4>
                            <small class="text-muted">Aulas Ativas</small>
                        </div>
                        <div class="col-12">
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $stats['completion_rate'] ?? 0 }}%"
                                     aria-valuenow="{{ $stats['completion_rate'] ?? 0 }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($stats['completion_rate'] ?? 0, 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">Taxa de Conclusão</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.courses.modules.edit', [$course, $module]) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Seção
                        </a>
                        
                        <a href="{{ route('admin.lessons.create', ['module_id' => $module->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Nova Aula
                        </a>

                        <button type="button" class="btn btn-outline-{{ $module->is_active ? 'warning' : 'success' }}" 
                                onclick="toggleActive({{ $module->id }})">
                            <i class="fas fa-{{ $module->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                            {{ $module->is_active ? 'Desativar' : 'Ativar' }}
                        </button>

                        <button type="button" class="btn btn-outline-info" 
                                onclick="duplicateModule({{ $module->id }})">
                            <i class="fas fa-copy"></i> Duplicar Seção
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleActive(moduleId) {
    if (confirm('Deseja alterar o status desta seção?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.courses.modules.index', $course) }}/${moduleId}/toggle-active`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function duplicateModule(moduleId) {
    if (confirm('Deseja duplicar esta seção?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.courses.modules.index', $course) }}/${moduleId}/duplicate`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection