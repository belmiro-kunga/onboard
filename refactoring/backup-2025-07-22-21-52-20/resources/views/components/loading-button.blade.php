{{-- Componente de Botão com Loading State HCP --}}
@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'loadingText' => 'Carregando...',
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-hcp-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variantClasses = [
    'primary' => 'bg-hcp-gradient hover:shadow-hcp-lg text-white focus:ring-hcp-500',
    'secondary' => 'bg-hcp-secondary-100 hover:bg-hcp-secondary-200 dark:bg-hcp-secondary-700 dark:hover:bg-hcp-secondary-600 text-hcp-secondary-700 dark:text-hcp-secondary-200 focus:ring-hcp-500',
    'outline' => 'border-2 border-hcp-500 text-hcp-500 hover:bg-hcp-500 hover:text-white focus:ring-hcp-500',
    'ghost' => 'text-hcp-secondary-700 dark:text-hcp-secondary-200 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 focus:ring-hcp-500',
    'danger' => 'bg-hcp-error-500 hover:bg-hcp-error-600 text-white focus:ring-hcp-error-500',
    'success' => 'bg-hcp-success-500 hover:bg-hcp-success-600 text-white focus:ring-hcp-success-500',
];

$sizeClasses = [
    'xs' => 'px-2 py-1 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];

$classes = collect([
    $baseClasses,
    $variantClasses[$variant] ?? $variantClasses['primary'],
    $sizeClasses[$size] ?? $sizeClasses['md'],
])->filter()->implode(' ');
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($loading || $disabled) disabled @endif
>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ $loadingText }}
    @else
        {{ $slot }}
    @endif
</button> 