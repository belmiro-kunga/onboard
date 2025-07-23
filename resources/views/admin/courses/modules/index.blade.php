@extends('layouts.admin')

@section('title', 'Seções do Curso: ' . $course->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Seções do Curso</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active">Seções</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nova Seção
            </a>
        </div>
    </div>

    <!-- Course Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ $course->description }}</p>
                </div>
                <div class="col-md-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary mb-0">{{ $stats['total_modules'] }}</h6>
                                <small class="text-muted">Total Módulos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-0">{{ $stats['active_modules'] }}</h6>
                            <small class="text-muted">Ativos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total de Aulas</h6>
                            <h4 class="mb-0">{{ $stats['total_lessons'] }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-play-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Duração Total</h6>
                            <h4 class="mb-0">{{ floor($stats['total_duration'] / 60) }}h {{ $stats['total_duration'] % 60 }}min</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Taxa de Conclusão</h6>
                            <h4 class="mb-0">{{ number_format($stats['avg_completion_rate'], 1) }}%</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Módulos Ativos</h6>
                            <h4 class="mb-0">{{ $stats['active_modules'] }}/{{ $stats['total_modules'] }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-toggle-on fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Título do módulo..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dificuldade</label>
                    <select name="difficulty" class="form-select">
                        <option value="">Todas</option>
                        <option value="beginner" {{ request('difficulty') === 'beginner' ? 'selected' : '' }}>Iniciante</option>
                        <option value="intermediate" {{ request('difficulty') === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                        <option value="advanced" {{ request('difficulty') === 'advanced' ? 'selected' : '' }}>Avançado</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.courses.modules.index', $course) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modules List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Módulos</h5>
            <div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="reorderBtn">
                    <i class="fas fa-sort"></i> Reordenar
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($modules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="modulesTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Título</th>
                                <th>Aulas</th>
                                <th>Duração</th>
                                <th>Dificuldade</th>
                                <th>Status</th>
                                <th>Conclusão</th>
                                <th width="200">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="sortableModules">
                            @foreach($modules as $module)
                                <tr data-id="{{ $module->id }}" data-order="{{ $module->order_index }}">
                                    <td>
                                        <span class="drag-handle" style="cursor: move; display: none;">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                        </span>
                                        {{ $module->order_index }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($module->thumbnail)
                                                <img src="{{ asset('storage/' . $module->thumbnail) }}" 
                                                     class="rounded me-2" width="40" height="40" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $module->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($module->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $module->lessons_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($module->duration_minutes)
                                            {{ floor($module->duration_minutes / 60) }}h {{ $module->duration_minutes % 60 }}min
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @if($module->is_active)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $completionRate = $module->getCompletionRate();
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $completionRate }}%"
                                                 aria-valuenow="{{ $completionRate }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($completionRate, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.courses.modules.show', [$course, $module]) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.courses.modules.edit', [$course, $module]) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="duplicateModule({{ $module->id }})" title="Duplicar">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-{{ $module->is_active ? 'warning' : 'success' }}" 
                                                    onclick="toggleActive({{ $module->id }})" 
                                                    title="{{ $module->is_active ? 'Desativar' : 'Ativar' }}">
                                                <i class="fas fa-{{ $module->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteModule({{ $module->id }})" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $modules->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum módulo encontrado</h5>
                    <p class="text-muted">Comece criando o primeiro módulo para este curso.</p>
                    <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Primeiro Módulo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let sortable;
let isReorderMode = false;

document.getElementById('reorderBtn').addEventListener('click', function() {
    toggleReorderMode();
});

function toggleReorderMode() {
    isReorderMode = !isReorderMode;
    const btn = document.getElementById('reorderBtn');
    const dragHandles = document.querySelectorAll('.drag-handle');
    
    if (isReorderMode) {
        btn.innerHTML = '<i class="fas fa-save"></i> Salvar Ordem';
        btn.className = 'btn btn-sm btn-success';
        
        dragHandles.forEach(handle => handle.style.display = 'inline');
        
        sortable = Sortable.create(document.getElementById('sortableModules'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                // Atualizar ordem visual
                updateOrderNumbers();
            }
        });
    } else {
        saveOrder();
    }
}

function updateOrderNumbers() {
    const rows = document.querySelectorAll('#sortableModules tr');
    rows.forEach((row, index) => {
        const orderCell = row.querySelector('td:first-child');
        const orderText = orderCell.childNodes[orderCell.childNodes.length - 1];
        if (orderText.nodeType === Node.TEXT_NODE) {
            orderText.textContent = ' ' + (index + 1);
        }
    });
}

function saveOrder() {
    const rows = document.querySelectorAll('#sortableModules tr');
    const modules = [];
    
    rows.forEach((row, index) => {
        modules.push({
            id: parseInt(row.dataset.id),
            order_index: index + 1
        });
    });
    
    fetch('{{ route("admin.courses.modules.reorder", $course) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ modules: modules })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Ordem dos módulos atualizada com sucesso!', 'success');
            exitReorderMode();
        } else {
            showAlert('Erro ao atualizar ordem dos módulos.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Erro ao atualizar ordem dos módulos.', 'danger');
    });
}

function exitReorderMode() {
    isReorderMode = false;
    const btn = document.getElementById('reorderBtn');
    const dragHandles = document.querySelectorAll('.drag-handle');
    
    btn.innerHTML = '<i class="fas fa-sort"></i> Reordenar';
    btn.className = 'btn btn-sm btn-outline-secondary';
    
    dragHandles.forEach(handle => handle.style.display = 'none');
    
    if (sortable) {
        sortable.destroy();
        sortable = null;
    }
}

function duplicateModule(moduleId) {
    if (confirm('Deseja duplicar este módulo?')) {
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

function toggleActive(moduleId) {
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

function deleteModule(moduleId) {
    if (confirm('Tem certeza que deseja excluir este módulo? Esta ação não pode ser desfeita.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.courses.modules.index', $course) }}/${moduleId}`;
        
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

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush
@endsection