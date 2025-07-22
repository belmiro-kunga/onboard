{{-- Componente de Skeleton Loading HCP --}}
@props([
    'type' => 'text',
    'lines' => 1,
    'width' => 'full',
    'height' => 'default',
    'rounded' => true,
    'animated' => true,
])

@php
$widthClasses = [
    'full' => 'w-full',
    '1/2' => 'w-1/2',
    '1/3' => 'w-1/3',
    '1/4' => 'w-1/4',
    '2/3' => 'w-2/3',
    '3/4' => 'w-3/4',
    'auto' => 'w-auto',
];

$heightClasses = [
    'xs' => 'h-2',
    'sm' => 'h-3',
    'default' => 'h-4',
    'lg' => 'h-6',
    'xl' => 'h-8',
    '2xl' => 'h-12',
    'auto' => 'h-auto',
];

$baseClasses = collect([
    'bg-hcp-secondary-200 dark:bg-hcp-secondary-700',
    $widthClasses[$width] ?? $widthClasses['full'],
    $heightClasses[$height] ?? $heightClasses['default'],
    $rounded ? 'rounded-hcp' : '',
    $animated ? 'animate-pulse' : '',
])->filter()->implode(' ');
@endphp

@if($type === 'text')
    <div class="space-y-3">
        @for($i = 0; $i < $lines; $i++)
            <div class="{{ $baseClasses }} {{ $i === $lines - 1 ? $widthClasses['2/3'] : '' }}"></div>
        @endfor
    </div>
@elseif($type === 'avatar')
    <div class="{{ $baseClasses }} rounded-full"></div>
@elseif($type === 'button')
    <div class="{{ $baseClasses }} rounded-hcp-lg"></div>
@elseif($type === 'card')
    <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-xl p-6 shadow-hcp">
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full animate-pulse"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                    <div class="h-3 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-2/3 animate-pulse"></div>
                </div>
            </div>
            <div class="space-y-2">
                <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-5/6 animate-pulse"></div>
                <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-4/6 animate-pulse"></div>
            </div>
        </div>
    </div>
@elseif($type === 'table')
    <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-xl shadow-hcp overflow-hidden">
        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-600">
            <div class="h-6 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-1/3 animate-pulse"></div>
        </div>
        <div class="p-6 space-y-4">
            @for($i = 0; $i < 5; $i++)
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full animate-pulse"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                        <div class="h-3 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-1/2 animate-pulse"></div>
                    </div>
                    <div class="w-20 h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                </div>
            @endfor
        </div>
    </div>
@elseif($type === 'list')
    <div class="space-y-4">
        @for($i = 0; $i < $lines; $i++)
            <div class="flex items-center space-x-4">
                <div class="w-8 h-8 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full animate-pulse"></div>
                <div class="flex-1">
                    <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                </div>
            </div>
        @endfor
    </div>
@elseif($type === 'grid')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for($i = 0; $i < 6; $i++)
            <div class="bg-white dark:bg-hcp-secondary-800 rounded-hcp-xl p-6 shadow-hcp">
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-2xl animate-pulse"></div>
                    <div class="space-y-2">
                        <div class="h-5 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded animate-pulse"></div>
                        <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-4/5 animate-pulse"></div>
                        <div class="h-4 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded w-3/5 animate-pulse"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@else
    <div {{ $attributes->merge(['class' => $baseClasses]) }}></div>
@endif 