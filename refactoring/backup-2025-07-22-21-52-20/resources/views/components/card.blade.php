{{-- Componente de Card HCP --}}
@props([
    'variant' => 'default',
    'padding' => 'default',
    'hover' => true,
    'glass' => false,
    'gradient' => false,
])

@php
$baseClasses = 'rounded-hcp-xl transition-all duration-300 ease-in-out';

$variantClasses = [
    'default' => 'bg-white dark:bg-hcp-secondary-800 border border-hcp-secondary-200 dark:border-hcp-secondary-600 shadow-hcp',
    'elevated' => 'bg-white dark:bg-hcp-secondary-800 shadow-hcp-lg border-0',
    'outlined' => 'bg-transparent border-2 border-hcp-secondary-200 dark:border-hcp-secondary-600',
    'filled' => 'bg-hcp-secondary-50 dark:bg-hcp-secondary-900 border-0',
    'glass' => 'hcp-glass border-0',
    'gradient' => 'hcp-gradient text-white border-0',
];

$paddingClasses = [
    'none' => '',
    'sm' => 'p-4',
    'default' => 'p-6',
    'lg' => 'p-8',
    'xl' => 'p-10',
];

$hoverClasses = $hover ? 'hover:shadow-hcp-lg hover:-translate-y-1 hover:scale-[1.02]' : '';

$classes = collect([
    $baseClasses,
    $variantClasses[$variant] ?? $variantClasses['default'],
    $paddingClasses[$padding] ?? $paddingClasses['default'],
    $hoverClasses,
])->filter()->implode(' ');

if ($glass) {
    $classes .= ' hcp-glass';
}

if ($gradient) {
    $classes .= ' hcp-gradient text-white';
}
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>