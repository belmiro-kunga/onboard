@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Player de Vídeo Principal -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body p-0">
                    @if($lesson->video)
                        <div class="ratio ratio-16x9">
                            @if($lesson->video->type === 'youtube')
                                <iframe src="{{ $lesson->video->getEmbedUrl() }}" 
                                        title="{{ $lesson->title }}" 
                                        allowfullscreen></iframe>
                            @elseif($lesson->video->type === 'local')
                                <video controls class="w-100">
                                    <source src="{{ $lesson->video->getVideoUrl() }}" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                            @elseif($lesson->video->type === 'vimeo')
                                <iframe src="{{ $lesson->video->getEmbedUrl() }}" 
                                        title="{{ $lesson->title }}" 
                                        allowfullscreen></iframe>
                            @endif
                        </div>
                    @else
                        <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                            <div class="text-center text-muted">
                                <i class="fas fa-play-circle fa-3x mb-3"></i>
                                <h5>Nenhum vídeo disponível</h5>
                                <p>Esta aula não possui vídeo associado.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informações da Aula -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h4 mb-2">{{ $lesson->title }}</h1>
                            <div class="text-muted">
                                <small>
                                    <i class="fas fa-book me-1"></i> {{ $lesson->module->title }}
                                    @if($lesson->duration_minutes)
                                        <i class="fas fa-clock ms-3 me-1"></i> {{ $lesson->duration_minutes }} min
                                    @endif
                                    @if($lesson->is_optional)
                                        <span class="badge bg-info ms-2">Opcional</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div>
                            @if(!$lesson->isCompletedBy(auth()->user()))
                                <button type="button" class="btn btn-success" onclick="markAsCompleted()">
                                    <i class="fas fa-check"></i> Marcar como Concluída
                                </button>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle"></i> Concluída
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($lesson->description)
                        <div class="mb-3">
                            <h6>Descrição:</h6>
                            <p class="text-muted">{{ $lesson->description }}</p>
                        </div>
                    @endif

                    @if($lesson->objective)
                        <div class="mb-3">
                            <h6>Objetivo:</h6>
                            <p class="text-muted">{{ $lesson->objective }}</p>
                        </div>
                    @endif

                    <!-- Progresso da Aula -->
                    @if($progress)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Seu Progresso:</h6>
                                <span class="fw-bold">{{ $progress->progress_percentage }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $progress->progress_percentage }}%"
                                     aria-valuenow="{{ $progress->progress_percentage }}" 
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if($progress->last_watched_at)
                                <small class="text-muted">
                                    Última visualização: {{ $progress->last_watched_at->diffForHumans() }}
                                </small>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Materiais Complementares -->
            @if($lesson->materials && $lesson->materials->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Materiais Complementares
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($lesson->materials as $material)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <div class="me-3">
                                            <i class="fas {{ $material->type_icon }} fa-2x text-{{ $material->type_color }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $material->title }}</h6>
                                            @if($material->description)
                                                <p class="text-muted small mb-1">{{ $material->description }}</p>
                                            @endif
                                            <small class="text-muted">{{ $material->formatted_type }}</small>
                                        </div>
                                        <div>
                                            @if($material->is_downloadable)
                                                <a href="{{ $material->getDownloadUrl() }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <a href="{{ $material->getUrl() }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quiz da Aula -->
            @if($lesson->quiz)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>
                            {{ $lesson->quiz->title }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($lesson->quiz->description)
                            <p class="text-muted">{{ $lesson->quiz->description }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    @if($lesson->quiz->time_limit_minutes)
                                        {{ $lesson->quiz->time_limit_minutes }} minutos
                                    @else
                                        Sem limite de tempo
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="btn btn-primary">
                                <i class="fas fa-play"></i> Iniciar {{ $lesson->quiz->formatted_type }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Navegação -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Navegação</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($previousLesson)
                            <a href="{{ route('lessons.show', $previousLesson) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-chevron-left me-2"></i>
                                Aula Anterior
                            </a>
                        @endif
                        
                        @if($nextLesson)
                            <a href="{{ route('lessons.show', $nextLesson) }}" class="btn btn-primary">
                                Próxima Aula
                                <i class="fas fa-chevron-right ms-2"></i>
                            </a>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-trophy fa-2x mb-2"></i>
                                <p class="mb-0">Parabéns! Você completou todas as aulas desta seção.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estatísticas de Engajamento -->
            @if($engagementStats)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Estatísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <h6 class="text-primary mb-0">{{ $engagementStats['completed_users'] }}</h6>
                                <small class="text-muted">Concluíram</small>
                            </div>
                            <div class="col-6 mb-2">
                                <h6 class="text-success mb-0">{{ number_format($engagementStats['completion_rate'], 1) }}%</h6>
                                <small class="text-muted">Taxa</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notas Pessoais -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Suas Notas</h5>
                </div>
                <div class="card-body">
                    @if($lesson->userNotes && $lesson->userNotes->count() > 0)
                        @foreach($lesson->userNotes as $note)
                            <div class="mb-2 p-2 border-start border-{{ $note->color }} border-3 bg-light">
                                @if($note->video_timestamp)
                                    <small class="text-muted">{{ $note->formatted_timestamp }}</small>
                                @endif
                                <p class="mb-0 small">{{ $note->content }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">Nenhuma nota ainda. Adicione suas anotações durante o vídeo!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsCompleted() {
    if (confirm('Marcar esta aula como concluída?')) {
        fetch('{{ route("lessons.complete", $lesson) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao marcar aula como concluída.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao marcar aula como concluída.');
        });
    }
}

// Auto-update progress (se houver vídeo)
@if($lesson->video)
let progressInterval;
let currentTime = {{ $videoTimestamp ?? 0 }};

// Simular progresso do vídeo (implementar com player real)
function updateProgress() {
    currentTime += 10; // Incrementa 10 segundos
    
    fetch('{{ route("lessons.progress.update", $lesson) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            progress_percentage: Math.min(100, Math.floor((currentTime / ({{ $lesson->duration_minutes ?? 30 }} * 60)) * 100)),
            watch_time_seconds: currentTime,
            current_time_seconds: currentTime
        })
    });
}

// Atualizar progresso a cada 30 segundos
progressInterval = setInterval(updateProgress, 30000);
@endif
</script>
@endpush
@endsection