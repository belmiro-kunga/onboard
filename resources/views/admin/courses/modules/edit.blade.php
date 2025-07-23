@extends('layouts.admin')

@section('title', 'Editar Seção: ' . $module->title)

@section('content')
<div class="container-fluid">
    <!-- Mensagens de Feedback -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Erro ao salvar:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Editar Seção: {{ $module->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.modules.index', $course) }}">Seções</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.courses.modules.show', [$course, $module]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Formulário -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações da Seção</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.modules.update', [$course, $module]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título da Seção <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $module->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order_index" class="form-label">Ordem <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                                           id="order_index" name="order_index" value="{{ old('order_index', $module->order_index) }}" min="0" required>
                                    @error('order_index')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $module->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="duration_minutes" class="form-label">Duração (minutos)</label>
                                    <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                           id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $module->duration_minutes) }}" min="1">
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="difficulty_level" class="form-label">Dificuldade <span class="text-danger">*</span></label>
                                    <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                            id="difficulty_level" name="difficulty_level" required>
                                        <option value="">Selecione...</option>
                                        <option value="beginner" {{ old('difficulty_level', $module->difficulty_level) === 'beginner' ? 'selected' : '' }}>Iniciante</option>
                                        <option value="intermediate" {{ old('difficulty_level', $module->difficulty_level) === 'intermediate' ? 'selected' : '' }}>Intermediário</option>
                                        <option value="advanced" {{ old('difficulty_level', $module->difficulty_level) === 'advanced' ? 'selected' : '' }}>Avançado</option>
                                    </select>
                                    @error('difficulty_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="points_reward" class="form-label">Pontos de Recompensa</label>
                                    <input type="number" class="form-control @error('points_reward') is-invalid @enderror" 
                                           id="points_reward" name="points_reward" value="{{ old('points_reward', $module->points_reward) }}" min="0">
                                    @error('points_reward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Categoria</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category', $module->category) }}">
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_type" class="form-label">Tipo de Conteúdo</label>
                                    <select class="form-select @error('content_type') is-invalid @enderror" 
                                            id="content_type" name="content_type">
                                        <option value="mixed" {{ old('content_type', $module->content_type) === 'mixed' ? 'selected' : '' }}>Misto</option>
                                        <option value="video" {{ old('content_type', $module->content_type) === 'video' ? 'selected' : '' }}>Vídeo</option>
                                        <option value="text" {{ old('content_type', $module->content_type) === 'text' ? 'selected' : '' }}>Texto</option>
                                        <option value="quiz" {{ old('content_type', $module->content_type) === 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    </select>
                                    @error('content_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                   id="thumbnail" name="thumbnail" accept="image/*">
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($module->thumbnail)
                                <div class="mt-2">
                                    <small class="text-muted">Thumbnail atual:</small><br>
                                    <img src="{{ asset('storage/' . $module->thumbnail) }}" 
                                         alt="Thumbnail" class="img-thumbnail mt-1" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Seção ativa
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.courses.modules.show', [$course, $module]) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar com Informações -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Curso</h5>
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ $course->title }}</h6>
                    <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                    <small class="text-muted">
                        <strong>Total de Seções:</strong> {{ $course->modules()->count() }}<br>
                        <strong>Seções Ativas:</strong> {{ $course->modules()->where('is_active', true)->count() }}
                    </small>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dicas</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            <small>Use títulos descritivos para facilitar a navegação</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-info me-2"></i>
                            <small>Defina a duração estimada para ajudar os usuários</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-sort-numeric-down text-success me-2"></i>
                            <small>Use a ordem para organizar o fluxo de aprendizado</small>
                        </li>
                        <li>
                            <i class="fas fa-image text-primary me-2"></i>
                            <small>Adicione uma thumbnail atrativa para a seção</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection