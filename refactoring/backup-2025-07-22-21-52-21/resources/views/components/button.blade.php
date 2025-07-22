{{-- Componente de Botão HCP --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variantClasses = [
    'primary' => 'bg-hcp-500 hover:bg-hcp-600 text-white shadow-hcp hover:shadow-hcp-md focus:ring-hcp-500 active:bg-hcp-700',
    'secondary' => 'bg-hcp-secondary-100 hover:bg-hcp-secondary-200 text-hcp-secondary-700 dark:bg-hcp-secondary-700 dark:hover:bg-hcp-secondary-600 dark:text-hcp-secondary-200 border border-hcp-secondary-200 dark:border-hcp-secondary-600 focus:ring-hcp-secondary-500',
    'outline' => 'border-2 border-hcp-500 text-hcp-500 hover:bg-hcp-500 hover:text-white focus:ring-hcp-500 dark:border-hcp-400 dark:text-hcp-400 dark:hover:bg-hcp-400 dark:hover:text-hcp-900',
    'ghost' => 'text-hcp-500 hover:bg-hcp-50 dark:text-hcp-400 dark:hover:bg-hcp-secondary-800 focus:ring-hcp-500',
    'danger' => 'bg-hcp-error-500 hover:bg-hcp-error-600 text-white shadow-hcp hover:shadow-hcp-md focus:ring-hcp-error-500 active:bg-hcp-error-700',
    'success' => 'bg-hcp-success-500 hover:bg-hcp-success-600 text-white shadow-hcp hover:shadow-hcp-md focus:ring-hcp-success-500 active:bg-hcp-success-700',
    'warning' => 'bg-hcp-warning-500 hover:bg-hcp-warning-600 text-white shadow-hcp hover:shadow-hcp-md focus:ring-hcp-warning-500 active:bg-hcp-warning-700',
];

$sizeClasses = [
    'xs' => 'px-2.5 py-1.5 text-xs rounded-hcp-sm gap-1',
    'sm' => 'px-3 py-2 text-sm rounded-hcp gap-1.5',
    'md' => 'px-4 py-2.5 text-sm rounded-hcp gap-2',
    'lg' => 'px-6 py-3 text-base rounded-hcp-md gap-2',
    'xl' => 'px-8 py-4 text-lg rounded-hcp-lg gap-3',
];

$hoverEffects = in_array($variant, ['primary', 'danger', 'success', 'warning']) 
    ? 'hover:-translate-y-0.5 active:translate-y-0' 
    : '';

$classes = collect([
    $baseClasses,
    $variantClasses[$variant] ?? $variantClasses['primary'],
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $hoverEffects,
])->filter()->implode(' ');

$tag = $href ? 'a' : 'button';
$typeAttr = $href ? null : $type;
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if($typeAttr) type="{{ $typeAttr }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Carregando...
    @else
        @if($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="w-4 h-4" />
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <x-icon :name="$icon" class="w-4 h-4" />
        @endif
    @endif
</{{ $tag }}>