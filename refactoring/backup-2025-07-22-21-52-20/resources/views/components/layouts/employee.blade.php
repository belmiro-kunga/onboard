<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0ea5e9">
    
    <title>{{ $title ?? 'Dashboard' }} - Sistema HCP</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="h-full bg-hcp-secondary-50 dark:bg-hcp-secondary-900 font-sans antialiased">
    <!-- Employee Navigation -->
    <x-employee-nav :current-route="request()->route()->getName()" />
    
    <!-- Main Content -->
    <main class="employee-nav-content">
        <!-- Desktop Header - Apenas para desktop -->
        <div class="hidden md:block bg-white dark:bg-hcp-secondary-800 shadow-hcp border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-hcp-gradient rounded-hcp flex items-center justify-center">
                                <span class="text-white font-bold text-sm">HCP</span>
                            </div>
                            <span class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                                {{ $title ?? 'Dashboard' }}
                            </span>
                        </a>
                    </div>

                    <!-- Desktop actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notificações -->
                        <button class="relative p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors rounded-hcp hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-hcp-error-500 rounded-full animate-pulse"></span>
                            @endif
                        </button>

                        <!-- Theme toggle -->
                        <x-theme-toggle />

                        <!-- Avatar e nome -->
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Bem-vindo, {{ explode(' ', auth()->user()->name)[0] }}!
                            </span>
                            <div class="relative" x-data="{ profileMenuOpen: false }" x-cloak>
                                <button @click="profileMenuOpen = !profileMenuOpen" 
                                        onclick="toggleProfileMenu(this)"
                                        class="relative focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:ring-offset-2 rounded-full">
                                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-8 h-8 rounded-full border-2 border-hcp-500 cursor-pointer hover:border-hcp-400 transition-colors">
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-hcp-success-500 rounded-full border-2 border-white dark:border-hcp-secondary-800"></div>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="profileMenuOpen" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.away="profileMenuOpen = false"
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600 z-50">
                                    
                                    <!-- Header do menu -->
                                    <div class="px-4 py-3 border-b border-hcp-secondary-200 dark:border-hcp-secondary-600">
                                        <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ auth()->user()->email }}</p>
                                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-1">{{ auth()->user()->department }}</p>
                                    </div>
                                    
                                    <!-- Opções do menu -->
                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}" 
                                           class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                            <x-icon name="home" size="sm" class="mr-3" />
                                            Dashboard
                                        </a>
                                        <a href="{{ route('gamification.dashboard') }}" 
                                           class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                            <x-icon name="star" size="sm" class="mr-3" />
                                            Gamificação
                                        </a>
                                        <a href="{{ route('notifications.index') }}" 
                                           class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                                            <x-icon name="bell" size="sm" class="mr-3" />
                                            Notificações
                                        </a>
                                        <div class="border-t border-hcp-secondary-200 dark:border-hcp-secondary-600 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center w-full px-4 py-2 text-sm text-hcp-error-600 dark:text-hcp-error-400 hover:bg-hcp-error-50 dark:hover:bg-hcp-error-900/20 transition-colors">
                                                <x-icon name="log-out" size="sm" class="mr-3" />
                                                Sair
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Header (Optional) -->
        @if(isset($header))
            <header class="bg-white dark:bg-hcp-secondary-800 shadow-sm border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        <!-- Page Content -->
        <div class="min-h-screen">
            {{ $slot }}
        </div>
    </main>
    
    <!-- Toast Notifications -->
    <x-toast-notification />
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-hcp-secondary-800 rounded-lg p-6 flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-hcp-500"></div>
            <span class="text-hcp-secondary-900 dark:text-white font-medium">Carregando...</span>
        </div>
    </div>
    
    <!-- Scripts -->
    @stack('scripts')
    
    <!-- Global JavaScript -->
    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
        
        // Initialize theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });
        
        // Loading overlay functions
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }
        
        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }
        
        // Auto-hide loading on page load
        window.addEventListener('load', function() {
            hideLoading();
        });
        
        // Show loading on form submissions
        document.addEventListener('submit', function(e) {
            if (!e.target.hasAttribute('data-no-loading')) {
                showLoading();
            }
        });
        
        // Show loading on navigation links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            if (link && !link.hasAttribute('data-no-loading') && link.href.startsWith(window.location.origin)) {
                showLoading();
            }
        });
        
        // Fallback JavaScript para dropdown do avatar
        function toggleProfileMenu(button) {
            const dropdown = button.nextElementSibling;
            if (dropdown) {
                const isVisible = dropdown.style.display !== 'none';
                dropdown.style.display = isVisible ? 'none' : 'block';
                
                // Fechar outros dropdowns
                document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.style.display = 'none';
                    }
                });
            }
        }
        
        // Fechar dropdowns ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[x-data]')) {
                document.querySelectorAll('[x-show]').forEach(menu => {
                    if (menu.style.display === 'block') {
                        menu.style.display = 'none';
                    }
                });
            }
        });
        
        // Verificar se Alpine.js está funcionando
        setTimeout(() => {
            if (typeof Alpine === 'undefined') {
                console.log('Alpine.js não carregou, usando fallback JavaScript');
                // Adicionar data-dropdown-menu aos dropdowns
                document.querySelectorAll('[x-show]').forEach(menu => {
                    menu.setAttribute('data-dropdown-menu', 'true');
                });
            }
        }, 1000);
    </script>
</body>
</html>