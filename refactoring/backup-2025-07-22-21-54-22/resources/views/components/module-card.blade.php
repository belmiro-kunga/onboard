@props([
    'module',
    'progress' => 0,
    'showProgress' => true,
    'showAction' => true,
    'size' => 'default', // 'default' or 'compact'
])

@php
    $progress = min(100, max(0, (int)$progress));
    $isCompleted = $progress >= 100;
    $isInProgress = $progress > 0 && $progress < 100;
    $isLocked = $module['is_locked'] ?? false;
    $status = $isCompleted ? 'completed' : ($isInProgress ? 'in_progress' : 'not_started');
    
    $statusColors = [
        'completed' => 'bg-hcp-success-100 dark:bg-hcp-success-900/30 text-hcp-success-800 dark:text-hcp-success-200 border-hcp-success-200 dark:border-hcp-success-800/50',
        'in_progress' => 'bg-hcp-warning-100 dark:bg-hcp-warning-900/30 text-hcp-warning-800 dark:text-hcp-warning-200 border-hcp-warning-200 dark:border-hcp-warning-800/50',
        'not_started' => 'bg-hcp-secondary-100 dark:bg-hcp-secondary-800/30 text-hcp-secondary-800 dark:text-hcp-secondary-200 border-hcp-secondary-200 dark:border-hcp-secondary-700/50',
    ][$status];
    
    $statusIcons = [
        'completed' => 'check-circle',
        'in_progress' => 'refresh-cw',
        'not_started' => 'book-open',
    ][$status];
    
    $statusLabels = [
        'completed' => 'Concluído',
        'in_progress' => 'Em andamento',
        'not_started' => 'Não iniciado',
    ][$status];
    
    $sizeClasses = [
        'default' => 'p-6',
        'compact' => 'p-4',
    ][$size];
    
    $contentClasses = [
        'default' => 'space-y-4',
        'compact' => 'space-y-2',
    ][$size];
    
    $titleSize = [
        'default' => 'text-xl',
        'compact' => 'text-lg',
    ][$size];
    
    $iconSize = [
        'default' => 'w-12 h-12',
        'compact' => 'w-10 h-10',
    ][$size];
    
    $badgeSize = [
        'default' => 'px-3 py-1 text-xs',
        'compact' => 'px-2 py-0.5 text-xs',
    ][$size];
    
    $actionSize = [
        'default' => 'px-4 py-2 text-sm',
        'compact' => 'px-3 py-1.5 text-xs',
    ][$size];
    
    $iconGradient = [
        'completed' => 'from-hcp-success-500 to-hcp-success-600',
        'in_progress' => 'from-hcp-warning-500 to-hcp-warning-600',
        'not_started' => 'from-hcp-secondary-500 to-hcp-secondary-600',
    ][$status];
    
    $isClickable = !$isLocked && $module['is_available'] ?? true;
@endphp

<div 
    {{ $attributes->merge([
        'class' => "relative overflow-hidden rounded-2xl border bg-white dark:bg-hcp-secondary-800/50 dark:border-hcp-secondary-700/50 transition-all duration-300 hover:shadow-lg group {$sizeClasses}"
    ]) }}
    @if($isClickable) 
        role="button"
        tabindex="0"
        @click="window.location.href='{{ route('modules.show', $module['id']) }}'"
        @keydown.enter="window.location.href='{{ route('modules.show', $module['id']) }}'"
        @keydown.space.prevent="window.location.href='{{ route('modules.show', $module['id']) }}'"
    @endif
>
    @if($isLocked)
        <div class="absolute inset-0 z-10 flex items-center justify-center bg-black/30 backdrop-blur-sm rounded-2xl">
            <div class="bg-white dark:bg-hcp-secondary-800 p-3 rounded-full shadow-lg">
                <x-icon name="lock" class="w-6 h-6 text-hcp-secondary-500" />
            </div>
        </div>
    @endif
    
    <!-- Badge de status -->
    <div class="absolute top-4 right-4">
        <span class="inline-flex items-center {{ $badgeSize }} rounded-full font-medium {{ $statusColors }} border">
            <x-icon name="{{ $statusIcons }}" class="w-3 h-3 mr-1.5" />
            <span>{{ $statusLabels }}</span>
        </span>
    </div>
    
    <div class="{{ $contentClasses }}">
        <div class="flex items-start space-x-4">
            <!-- Ícone do módulo -->
            <div class="flex-shrink-0">
                <div class="{{ $iconSize }} rounded-xl bg-gradient-to-br {{ $iconGradient }} flex items-center justify-center text-white shadow-md">
                    <x-icon name="{{ $module['icon'] ?? 'book' }}" class="w-2/3 h-2/3" />
                </div>
            </div>
            
            <!-- Conteúdo do módulo -->
            <div class="flex-1 min-w-0">
                <h3 class="font-bold {{ $titleSize }} text-hcp-secondary-900 dark:text-white group-hover:text-hcp-600 dark:group-hover:text-hcp-400 transition-colors">
                    {{ $module['title'] }}
                </h3>
                
                @if(($module['description'] ?? null) && $size === 'default')
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 line-clamp-2">
                        {{ $module['description'] }}
                    </p>
                @endif
                
                @if(($module['duration'] ?? null) && $size === 'default')
                    <div class="mt-2 flex items-center text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                        <x-icon name="clock" class="w-3.5 h-3.5 mr-1.5" />
                        <span>{{ $module['duration'] }} min</span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Barra de progresso -->
        @if($showProgress)
            <div class="pt-4">
                <div class="flex items-center justify-between text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mb-1">
                    <span>Progresso</span>
                    <span class="font-medium">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                    <div 
                        class="h-2 rounded-full transition-all duration-1000 ease-out"
                        :class="{
                            'bg-gradient-to-r from-hcp-success-500 to-hcp-success-600': {{ $isCompleted ? 'true' : 'false' }},
                            'bg-gradient-to-r from-hcp-warning-500 to-hcp-warning-600': {{ $isInProgress ? 'true' : 'false' }},
                            'bg-gradient-to-r from-hcp-secondary-400 to-hcp-secondary-500': {{ !$isCompleted && !$isInProgress ? 'true' : 'false' }}
                        }"
                        style="width: {{ $progress }}%"
                    ></div>
                </div>
            </div>
        @endif
        
        <!-- Ação -->
        @if($showAction)
            <div class="pt-3">
                @if($isLocked)
                    <button 
                        disabled
                        class="w-full {{ $actionSize }} inline-flex items-center justify-center rounded-lg bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-500 dark:text-hcp-secondary-400 font-medium cursor-not-allowed"
                    >
                        <x-icon name="lock" class="w-3.5 h-3.5 mr-2" />
                        Bloqueado
                    </button>
                @elseif($isCompleted)
                    <a 
                        href="{{ route('modules.show', $module['id']) }}"
                        class="block w-full {{ $actionSize }} text-center rounded-lg bg-hcp-success-50 dark:bg-hcp-success-900/30 text-hcp-success-700 dark:text-hcp-success-300 font-medium hover:bg-hcp-success-100 dark:hover:bg-hcp-success-900/50 transition-colors border border-hcp-success-200 dark:border-hcp-success-800/50"
                    >
                        Ver novamente
                    </a>
                @elseif($isInProgress)
                    <a 
                        href="{{ route('modules.show', $module['id']) }}"
                        class="block w-full {{ $actionSize }} text-center rounded-lg bg-hcp-warning-50 dark:bg-hcp-warning-900/30 text-hcp-warning-700 dark:text-hcp-warning-300 font-medium hover:bg-hcp-warning-100 dark:hover:bg-hcp-warning-900/50 transition-colors border border-hcp-warning-200 dark:border-hcp-warning-800/50"
                    >
                        Continuar
                    </a>
                @else
                    <a 
                        href="{{ route('modules.show', $module['id']) }}"
                        class="block w-full {{ $actionSize }} text-center rounded-lg bg-hcp-50 dark:bg-hcp-900/30 text-hcp-700 dark:text-hcp-300 font-medium hover:bg-hcp-100 dark:hover:bg-hcp-900/50 transition-colors border border-hcp-200 dark:border-hcp-800/50"
                    >
                        Começar
                    </a>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Efeito de brilho no hover -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        <div class="absolute -inset-1 bg-gradient-to-r from-white/30 to-transparent transform -skew-y-6 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
    </div>
    
    <!-- Efeito de destaque no canto superior direito -->
    <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 rounded-full bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
</div>
