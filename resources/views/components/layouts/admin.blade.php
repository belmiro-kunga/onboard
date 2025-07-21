@props(['title' => 'Painel Administrativo - HCP'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    
    <title>{{ $title }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Meta tags -->
    <meta name="description" content="Painel Administrativo do Sistema de Onboarding HCP">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scripts adicionais no head -->
    @stack('head-scripts')
    
    <!-- Estilos adicionais -->
    @stack('styles')
</head>
<body class="h-full bg-hcp-bg-primary text-hcp-text-primary antialiased">
    <!-- Loader inicial -->
    <div id="initial-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-hcp-secondary-900 transition-opacity duration-500">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-hcp-500 animate-pulse">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Carregando painel administrativo...</p>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div id="app" class="h-full opacity-0 transition-opacity duration-500">
        <!-- Header administrativo -->
        <header class="bg-white dark:bg-hcp-secondary-800 shadow-hcp border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo e título -->
                    <div class="flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-red-600 to-red-700 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">A</span>
                            </div>
                            <span class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                                Admin HCP
                            </span>
                        </a>
                    </div>

                    <!-- Menu administrativo -->
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('admin.dashboard') }}" class="text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" class="text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white font-medium">
                            Quizzes
                        </a>
                        <a href="{{ route('admin.simulados.index') }}" class="text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white font-medium">
                            Simulados
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white font-medium">
                            Usuários
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white font-medium">
                            Relatórios
                        </a>
                    </nav>

                    <!-- User menu -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- Notification Bell -->
                            <x-notification-bell :unreadCount="auth()->user()->unreadNotificationsCount()" />
                            
                            <!-- User dropdown -->
                            <div class="relative">
                                <button type="button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500" id="user-menu-button">
                                    <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                                    <span class="text-hcp-secondary-700 dark:text-hcp-secondary-300">{{ auth()->user()->name }}</span>
                                    <svg class="h-4 w-4 text-hcp-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-hcp-secondary-800 ring-1 ring-black ring-opacity-5 focus:outline-none" id="user-menu-dropdown">
                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                            Voltar ao Sistema
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                                Sair
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                        
                        <!-- Theme toggle -->
                        <x-theme-toggle size="sm" />
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>

    <!-- Toast notifications container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Modal container -->
    <div id="modal-container"></div>

    <!-- Scripts -->
    <script>
        // Remover loader inicial quando página carregar
        window.addEventListener('load', function() {
            const loader = document.getElementById('initial-loader');
            const app = document.getElementById('app');
            
            setTimeout(() => {
                loader.style.opacity = '0';
                app.style.opacity = '1';
                
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }, 300);
        });

        // User dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            
            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userMenuDropdown.classList.toggle('hidden');
                });
                
                // Fechar dropdown quando clicar fora
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }
        });

        // Configurações globais
        window.HCP = {
            csrfToken: '{{ csrf_token() }}',
            locale: '{{ app()->getLocale() }}',
            user: @auth {!! auth()->user()->toJson() !!} @else null @endauth,
            routes: {
                dashboard: '{{ route('dashboard') }}',
                adminDashboard: '{{ route('admin.dashboard') }}',
            }
        };
    </script>

    <!-- CSRF Token Handler Global -->
    <script>
        // Global CSRF Token Management
        window.CSRFHandler = {
            token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            
            // Update CSRF token in meta tag and all forms
            updateToken: function(newToken) {
                this.token = newToken;
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                
                // Update all forms with new token
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = newToken;
                });
            },
            
            // Refresh CSRF token from server
            refreshToken: function() {
                return fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.csrf_token) {
                        this.updateToken(data.csrf_token);
                        return data.csrf_token;
                    }
                    throw new Error('Token not received');
                })
                .catch(error => {
                    console.error('Failed to refresh CSRF token:', error);
                    // Fallback: reload page
                    window.location.reload();
                });
            },
            
            // Handle CSRF errors automatically
            handleCSRFError: function(response, retryCallback) {
                if (response.status === 419) {
                    return this.refreshToken().then(() => {
                        if (typeof retryCallback === 'function') {
                            return retryCallback();
                        }
                    });
                }
                return Promise.reject(response);
            }
        };

        // Global fetch wrapper with CSRF handling
        window.safeFetch = function(url, options = {}) {
            options.headers = options.headers || {};
            options.headers['X-CSRF-TOKEN'] = window.CSRFHandler.token;
            options.headers['X-Requested-With'] = 'XMLHttpRequest';
            
            return fetch(url, options)
                .then(response => {
                    if (response.status === 419) {
                        // CSRF token expired, refresh and retry
                        return window.CSRFHandler.refreshToken().then(() => {
                            options.headers['X-CSRF-TOKEN'] = window.CSRFHandler.token;
                            return fetch(url, options);
                        });
                    }
                    return response;
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    throw error;
                });
        };

        // Auto-refresh CSRF token every 30 minutes
        setInterval(() => {
            window.CSRFHandler.refreshToken();
        }, 30 * 60 * 1000);

        // Handle form submissions with CSRF protection
        document.addEventListener('DOMContentLoaded', function() {
            // Intercept all form submissions
            document.addEventListener('submit', function(e) {
                const form = e.target;
                const tokenInput = form.querySelector('input[name="_token"]');
                
                if (tokenInput) {
                    // Always use the latest token
                    tokenInput.value = window.CSRFHandler.token;
                }
            });

            // Handle AJAX form submissions
            document.addEventListener('click', function(e) {
                if (e.target.matches('button[type="submit"], input[type="submit"]')) {
                    const form = e.target.closest('form');
                    if (form) {
                        const tokenInput = form.querySelector('input[name="_token"]');
                        if (tokenInput) {
                            tokenInput.value = window.CSRFHandler.token;
                        }
                    }
                }
            });

            // Refresh token when page becomes visible again (user returns from another tab)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    window.CSRFHandler.refreshToken();
                }
            });

            // Refresh token before page unload
            window.addEventListener('beforeunload', function() {
                if (navigator.sendBeacon) {
                    navigator.sendBeacon('/csrf-token');
                }
            });

            // Show warning when session is about to expire (45 minutes)
            setTimeout(function() {
                if (typeof showToast === 'function') {
                    showToast('warning', 'Sua sessão expirará em breve. Salve seu trabalho.');
                }
                // Auto-refresh token
                window.CSRFHandler.refreshToken();
            }, 45 * 60 * 1000);
        });

        // Global error handler for AJAX requests
        window.handleAjaxError = function(error, retryCallback) {
            if (error.status === 419) {
                return window.CSRFHandler.handleCSRFError(error, retryCallback);
            }
            
            // Show user-friendly error message
            if (typeof showToast === 'function') {
                showToast('error', 'Ocorreu um erro. Por favor, tente novamente.');
            } else {
                alert('Ocorreu um erro. Por favor, tente novamente.');
            }
            
            return Promise.reject(error);
        };
    </script>

    <!-- Scripts adicionais -->
    @stack('scripts')

    <!-- Notificações flash -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('success', '{{ session('success') }}');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('error', '{{ session('error') }}');
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('warning', '{{ session('warning') }}');
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('info', '{{ session('info') }}');
            });
        </script>
    @endif
</body>
</html> 