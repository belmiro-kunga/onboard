{{-- Componente de Toggle de Tema HCP --}}
@props([
    'size' => 'md',
    'variant' => 'button',
    'showLabel' => false,
])

@php
$sizeClasses = [
    'sm' => 'w-8 h-8 text-sm',
    'md' => 'w-10 h-10 text-base',
    'lg' => 'w-12 h-12 text-lg',
];

$buttonClasses = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if($variant === 'button')
    <button 
        type="button"
        data-theme-toggle
        class="
            {{ $buttonClasses }}
            inline-flex items-center justify-center
            rounded-hcp-lg
            bg-hcp-secondary-100 hover:bg-hcp-secondary-200
            dark:bg-hcp-secondary-700 dark:hover:bg-hcp-secondary-600
            text-hcp-secondary-700 dark:text-hcp-secondary-200
            border border-hcp-secondary-200 dark:border-hcp-secondary-600
            transition-all duration-200 ease-in-out
            hover:scale-105 active:scale-95
            focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2
            group
        "
        title="Alternar tema (Ctrl+Shift+T)"
        aria-label="Alternar entre modo claro e escuro"
    >
        {{-- Ícone do Sol (modo claro) --}}
        <svg 
            data-theme-icon="sun"
            class="w-5 h-5 transition-all duration-200 group-hover:rotate-12"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
            />
        </svg>

        {{-- Ícone da Lua (modo escuro) --}}
        <svg 
            data-theme-icon="moon"
            class="w-5 h-5 transition-all duration-200 group-hover:-rotate-12 hidden"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
            />
        </svg>

        @if($showLabel)
            <span class="ml-2 text-sm font-medium">
                <span class="dark:hidden">Modo Escuro</span>
                <span class="hidden dark:inline">Modo Claro</span>
            </span>
        @endif
    </button>

@elseif($variant === 'switch')
    <div class="flex items-center space-x-3">
        @if($showLabel)
            <span class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-200">
                <span class="dark:hidden">Claro</span>
                <span class="hidden dark:inline">Escuro</span>
            </span>
        @endif

        <button
            type="button"
            data-theme-toggle
            class="
                relative inline-flex h-6 w-11 items-center rounded-full
                bg-hcp-secondary-200 dark:bg-hcp-secondary-600
                transition-colors duration-200 ease-in-out
                focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2
                group
            "
            role="switch"
            aria-label="Alternar tema"
        >
            <span class="
                inline-block h-4 w-4 transform rounded-full
                bg-white dark:bg-hcp-secondary-200
                transition-transform duration-200 ease-in-out
                translate-x-1 dark:translate-x-6
                shadow-sm
                group-hover:scale-110
            "></span>
        </button>

        @if($showLabel)
            <span class="text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-200">
                <span class="hidden dark:inline">Claro</span>
                <span class="dark:hidden">Escuro</span>
            </span>
        @endif
    </div>

@elseif($variant === 'dropdown')
    <div class="relative" x-data="{ open: false }">
        <button
            @click="open = !open"
            @click.away="open = false"
            class="
                inline-flex items-center px-3 py-2 text-sm font-medium
                text-hcp-secondary-700 dark:text-hcp-secondary-200
                bg-hcp-secondary-100 dark:bg-hcp-secondary-700
                border border-hcp-secondary-200 dark:border-hcp-secondary-600
                rounded-hcp hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600
                focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2
                transition-colors duration-200
            "
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
            </svg>
            Tema
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="
                absolute right-0 mt-2 w-48 origin-top-right
                bg-white dark:bg-hcp-secondary-800
                border border-hcp-secondary-200 dark:border-hcp-secondary-600
                rounded-hcp-lg shadow-hcp-lg
                ring-1 ring-black ring-opacity-5
                focus:outline-none z-50
            "
        >
            <div class="py-1">
                <button
                    @click="window.themeToggle.setTheme('light'); open = false"
                    class="
                        flex items-center w-full px-4 py-2 text-sm
                        text-hcp-secondary-700 dark:text-hcp-secondary-200
                        hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700
                        transition-colors duration-150
                    "
                >
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Modo Claro
                </button>

                <button
                    @click="window.themeToggle.setTheme('dark'); open = false"
                    class="
                        flex items-center w-full px-4 py-2 text-sm
                        text-hcp-secondary-700 dark:text-hcp-secondary-200
                        hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700
                        transition-colors duration-150
                    "
                >
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    Modo Escuro
                </button>

                <button
                    @click="window.themeToggle.resetToSystem(); open = false"
                    class="
                        flex items-center w-full px-4 py-2 text-sm
                        text-hcp-secondary-700 dark:text-hcp-secondary-200
                        hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700
                        transition-colors duration-150
                    "
                >
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Sistema
                </button>
            </div>
        </div>
    </div>
@endif