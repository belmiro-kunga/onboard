@props([
    'title',
    'value',
    'icon',
    'trend' => null, // 'up' or 'down'
    'trendValue' => null,
    'color' => 'hcp',
    'gradient' => 'from-hcp-500 to-hcp-600',
])

@php
    $colors = [
        'hcp' => [
            'bg' => 'bg-hcp-100 dark:bg-hcp-900/30',
            'text' => 'text-hcp-600 dark:text-hcp-400',
            'border' => 'border-hcp-200 dark:border-hcp-800',
            'hover' => 'hover:bg-hcp-50 dark:hover:bg-hcp-900/50',
            'gradient' => 'from-hcp-500 to-hcp-600',
        ],
        'success' => [
            'bg' => 'bg-green-100 dark:bg-green-900/30',
            'text' => 'text-green-600 dark:text-green-400',
            'border' => 'border-green-200 dark:border-green-800',
            'hover' => 'hover:bg-green-50 dark:hover:bg-green-900/50',
            'gradient' => 'from-green-500 to-green-600',
        ],
        'warning' => [
            'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
            'text' => 'text-yellow-600 dark:text-yellow-400',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'hover' => 'hover:bg-yellow-50 dark:hover:bg-yellow-900/50',
            'gradient' => 'from-yellow-500 to-yellow-600',
        ],
        'danger' => [
            'bg' => 'bg-red-100 dark:bg-red-900/30',
            'text' => 'text-red-600 dark:text-red-400',
            'border' => 'border-red-200 dark:border-red-800',
            'hover' => 'hover:bg-red-50 dark:hover:bg-red-900/50',
            'gradient' => 'from-red-500 to-red-600',
        ],
    ][$color];
    
    $gradient = $gradient ?? $colors['gradient'];
@endphp

<div 
    {{ $attributes->merge([
        'class' => "relative p-6 rounded-2xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl {$colors['bg']} {$colors['border']} border border-opacity-50 dark:border-opacity-30 {$colors['hover']} overflow-hidden group"
    ]) }}
    x-data="{ showTooltip: false }"
>
    <!-- Efeito de gradiente sutil no hover -->
    <div class="absolute inset-0 bg-gradient-to-br from-white/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
    
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                {{ $title }}
            </h3>
            @if($trend && $trendValue)
                <div class="flex items-center space-x-1">
                    <span class="text-xs font-medium {{ $trend === 'up' ? 'text-green-500' : 'text-red-500' }}">
                        {{ $trend === 'up' ? '↑' : '↓' }} {{ $trendValue }}%
                    </span>
                </div>
            @endif
        </div>
        
        <div class="flex items-center justify-between">
            <div class="text-3xl font-bold {{ $colors['text'] }}">
                {{ $value }}
            </div>
            
            @if($icon)
                <div class="p-3 rounded-xl bg-gradient-to-br {{ $gradient }} text-white shadow-md">
                    <x-icon :name="$icon" class="w-6 h-6" />
                </div>
            @endif
        </div>
        
        @if(isset($slot) && trim($slot) !== '')
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                {{ $slot }}
            </div>
        @endif
    </div>
    
    <!-- Efeito de brilho no hover -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        <div class="absolute -inset-1 bg-gradient-to-r from-white/30 to-transparent transform -skew-y-6 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
    </div>
</div>
