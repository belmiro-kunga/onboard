{{-- Sistema de Avaliação de Módulos --}}
@props([
    'module',
    'userRating' => null,
    'showStats' => true,
    'showComments' => true,
    'compact' => false
])

@php
$moduleStats = $module->getRatingStats();
$averageRating = $moduleStats['average_rating'];
$totalRatings = $moduleStats['total_ratings'];
$ratingDistribution = $moduleStats['rating_distribution'];
@endphp

<div class="module-rating-container {{ $compact ? 'compact' : '' }}" data-module-id="{{ $module->id }}">
    @if(!$compact)
    <!-- Header com Estatísticas -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-1">
                Avaliações do Módulo
            </h3>
            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                Compartilhe sua experiência e ajude outros colaboradores
            </p>
        </div>
        
        @if($showStats && $totalRatings > 0)
        <div class="text-right">
            <div class="flex items-center space-x-2 mb-1">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <x-icon name="star" size="sm" class="{{ $i <= $averageRating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" />
                    @endfor
                </div>
                <span class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                    {{ number_format($averageRating, 1) }}
                </span>
            </div>
            <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                {{ $totalRatings }} {{ $totalRatings === 1 ? 'avaliação' : 'avaliações' }}
            </p>
        </div>
        @endif
    </div>
    @endif

    <!-- Formulário de Avaliação do Usuário -->
    @if(!$userRating)
    <div class="bg-hcp-secondary-50 dark:bg-hcp-secondary-800 rounded-hcp-lg p-6 mb-6">
        <h4 class="font-medium text-hcp-secondary-900 dark:text-white mb-4">
            {{ $compact ? 'Avaliar' : 'Como foi sua experiência com este módulo?' }}
        </h4>
        
        <form id="rating-form-{{ $module->id }}" class="space-y-4">
            @csrf
            <input type="hidden" name="module_id" value="{{ $module->id }}">
            
            <!-- Rating Stars -->
            <div class="flex items-center space-x-1">
                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mr-3">
                    Sua avaliação:
                </span>
                <div class="flex items-center space-x-1 rating-stars" data-rating="0">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" 
                            class="rating-star w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2 rounded"
                            data-rating="{{ $i }}"
                            title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}">
                        <x-icon name="star" size="sm" class="w-full h-full" />
                    </button>
                    @endfor
                </div>
                <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 ml-3 rating-description">
                    Clique para avaliar
                </span>
            </div>
            
            <!-- Comentário -->
            <div>
                <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                    Comentário (opcional)
                </label>
                <textarea 
                    name="comment"
                    rows="{{ $compact ? '2' : '3' }}"
                    class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white placeholder-hcp-secondary-500 focus:ring-2 focus:ring-hcp-500 focus:border-transparent resize-none"
                    placeholder="Compartilhe detalhes sobre sua experiência com este módulo..."></textarea>
            </div>
            
            <!-- Feedback Estruturado -->
            @if(!$compact)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        Clareza do conteúdo
                    </label>
                    <select name="feedback_data[clarity]" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white focus:ring-2 focus:ring-hcp-500 focus:border-transparent">
                        <option value="">Selecione...</option>
                        <option value="5">Muito claro</option>
                        <option value="4">Claro</option>
                        <option value="3">Razoável</option>
                        <option value="2">Confuso</option>
                        <option value="1">Muito confuso</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        Relevância para o trabalho
                    </label>
                    <select name="feedback_data[relevance]" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white focus:ring-2 focus:ring-hcp-500 focus:border-transparent">
                        <option value="">Selecione...</option>
                        <option value="5">Muito relevante</option>
                        <option value="4">Relevante</option>
                        <option value="3">Moderadamente relevante</option>
                        <option value="2">Pouco relevante</option>
                        <option value="1">Irrelevante</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        Duração do módulo
                    </label>
                    <select name="feedback_data[duration]" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white focus:ring-2 focus:ring-hcp-500 focus:border-transparent">
                        <option value="">Selecione...</option>
                        <option value="perfect">Perfeita</option>
                        <option value="too_short">Muito curta</option>
                        <option value="too_long">Muito longa</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        Dificuldade
                    </label>
                    <select name="feedback_data[difficulty]" class="w-full px-3 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white focus:ring-2 focus:ring-hcp-500 focus:border-transparent">
                        <option value="">Selecione...</option>
                        <option value="very_easy">Muito fácil</option>
                        <option value="easy">Fácil</option>
                        <option value="appropriate">Adequada</option>
                        <option value="hard">Difícil</option>
                        <option value="very_hard">Muito difícil</option>
                    </select>
                </div>
            </div>
            @endif
            
            <!-- Opções -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="is_anonymous" value="1" class="rounded border-hcp-secondary-300 text-hcp-500 focus:ring-hcp-500 focus:ring-offset-0">
                    <span class="ml-2 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Avaliar anonimamente
                    </span>
                </label>
                
                <button type="submit" 
                        class="bg-hcp-500 hover:bg-hcp-400 text-white px-6 py-2 rounded-hcp font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    {{ $compact ? 'Enviar' : 'Enviar Avaliação' }}
                </button>
            </div>
        </form>
    </div>
    @else
    <!-- Avaliação Existente do Usuário -->
    <div class="bg-hcp-success-50 dark:bg-hcp-success-900/20 border border-hcp-success-200 dark:border-hcp-success-800 rounded-hcp-lg p-4 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-sm font-medium text-hcp-success-700 dark:text-hcp-success-300">
                        Sua avaliação:
                    </span>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <x-icon name="star" size="sm" class="{{ $i <= $userRating->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" />
                        @endfor
                    </div>
                    <span class="text-sm text-hcp-success-600 dark:text-hcp-success-400">
                        ({{ $userRating->rating_description }})
                    </span>
                </div>
                
                @if($userRating->comment)
                <p class="text-sm text-hcp-success-700 dark:text-hcp-success-300 mb-2">
                    "{{ $userRating->comment }}"
                </p>
                @endif
                
                <p class="text-xs text-hcp-success-600 dark:text-hcp-success-400">
                    Avaliado em {{ $userRating->created_at->format('d/m/Y') }}
                </p>
            </div>
            
            <button class="text-hcp-success-600 dark:text-hcp-success-400 hover:text-hcp-success-500 dark:hover:text-hcp-success-300 text-sm font-medium transition-colors duration-200 edit-rating-btn">
                Editar
            </button>
        </div>
    </div>
    @endif

    @if($showStats && $totalRatings > 0 && !$compact)
    <!-- Distribuição de Ratings -->
    <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-lg p-6 mb-6">
        <h4 class="font-medium text-hcp-secondary-900 dark:text-white mb-4">
            Distribuição das Avaliações
        </h4>
        
        <div class="space-y-3">
            @for($rating = 5; $rating >= 1; $rating--)
            @php
                $count = $ratingDistribution[$rating] ?? 0;
                $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
            @endphp
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-1 w-16">
                    <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ $rating }}</span>
                    <x-icon name="star" size="xs" class="text-yellow-400 fill-current" />
                </div>
                
                <div class="flex-1 bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-2">
                    <div class="bg-hcp-500 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $percentage }}%"></div>
                </div>
                
                <div class="w-12 text-right">
                    <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ $count }}</span>
                </div>
            </div>
            @endfor
        </div>
    </div>
    @endif

    @if($showComments && !$compact)
    <!-- Comentários Recentes -->
    <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-medium text-hcp-secondary-900 dark:text-white">
                Comentários Recentes
            </h4>
            
            <div class="flex items-center space-x-2">
                <select class="text-sm border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white px-3 py-1 focus:ring-2 focus:ring-hcp-500 focus:border-transparent" id="comments-filter-{{ $module->id }}">
                    <option value="all">Todos</option>
                    <option value="5">5 estrelas</option>
                    <option value="4">4 estrelas</option>
                    <option value="3">3 estrelas</option>
                    <option value="2">2 estrelas</option>
                    <option value="1">1 estrela</option>
                </select>
            </div>
        </div>
        
        <div id="comments-container-{{ $module->id }}" class="space-y-4">
            <!-- Comentários serão carregados via JavaScript -->
        </div>
        
        <div class="text-center mt-6">
            <button class="text-hcp-500 hover:text-hcp-400 text-sm font-medium transition-colors duration-200" 
                    id="load-more-comments-{{ $module->id }}">
                Ver mais comentários
            </button>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeModuleRating({{ $module->id }});
});
</script>
@endpush