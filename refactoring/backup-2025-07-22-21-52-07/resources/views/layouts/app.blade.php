<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Meta tags para SEO e redes sociais -->
    <meta name="description" content="{{ $description ?? 'Sistema de Onboarding Interativo da Hemera Capital Partners' }}">
    <meta name="keywords" content="onboarding, treinamento, HCP, Hemera Capital Partners">
    <meta name="author" content="Hemera Capital Partners">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? 'Sistema de Onboarding HCP' }}">
    <meta property="og:description" content="{{ $description ?? 'Sistema de Onboarding Interativo da Hemera Capital Partners' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Sistema de Onboarding HCP' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Sistema de Onboarding Interativo da Hemera Capital Partners' }}">
    <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
    
    <!-- PWA Meta Tags -->
    <meta name="application-name" content="HCP Onboarding">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="HCP Onboarding">
    <meta name="mobile-web-app-capable" content="yes">
    
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Carregando...</p>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div id="app" class="h-full opacity-0 transition-opacity duration-500">
        @if(isset($header))
            <!-- Header -->
            <header class="bg-white dark:bg-hcp-secondary-800 shadow-hcp border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-hcp-gradient rounded-hcp flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">HCP</span>
                                </div>
                                <span class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                                    Onboarding
                                </span>
                            </a>
                        </div>

                        <!-- Header content -->
                        <div class="flex items-center space-x-4">
                            {{ $header }}
                            
                            @auth
                                <!-- Notification Bell -->
                                <x-notification-bell :unreadCount="auth()->user()->unreadNotificationsCount()" />
                                <!-- Profile Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="focus:outline-none">
                                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                             alt="{{ auth()->user()->name }}"
                                             class="w-10 h-10 rounded-full border-2 border-hcp-500 transition-transform duration-200 hover:scale-105">
                                    </button>
                                    <div x-show="open" @click.away="open = false"
                                         class="absolute right-0 mt-2 w-64 bg-white dark:bg-hcp-secondary-800 border border-hcp-secondary-200 dark:border-hcp-secondary-700 rounded-xl shadow-2xl z-50"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95">
                                        <!-- Topo do menu -->
                                        <div class="flex items-center space-x-3 px-4 py-3 border-b border-hcp-secondary-100 dark:border-hcp-secondary-700">
                                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                 alt="{{ auth()->user()->name }}"
                                                 class="w-12 h-12 rounded-full border-2 border-hcp-500">
                                            <div>
                                                <div class="font-semibold text-hcp-secondary-900 dark:text-white">{{ auth()->user()->name }}</div>
                                                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-300">{{ auth()->user()->email }}</div>
                                            </div>
                                            <span class="ml-auto w-3 h-3 rounded-full bg-green-500 border-2 border-white dark:border-hcp-secondary-800" title="Online"></span>
                                        </div>
                                        <!-- Opções -->
                                        <a href="{{ route('profile.index') }}"
                                           class="block px-4 py-2 text-hcp-secondary-900 dark:text-white hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                            👤 Meu Perfil
                                        </a>
                                        <a href="{{ route('certificates.index') }}"
                                           class="block px-4 py-2 text-hcp-secondary-900 dark:text-white hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                            🎓 Meus Certificados
                                        </a>
                                        <a href="{{ route('progress.index') }}"
                                           class="block px-4 py-2 text-hcp-secondary-900 dark:text-white hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                            📊 Meu Progresso
                                        </a>
                                        <a href="{{ route('profile.edit') }}"
                                           class="block px-4 py-2 text-hcp-secondary-900 dark:text-white hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                            ⚙️ Configurações
                                        </a>
                                        <div class="border-t border-hcp-secondary-100 dark:border-hcp-secondary-700 my-2"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700">
                                                🚪 Sair
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                            <!-- Theme toggle -->
                            <x-theme-toggle size="sm" />
                        </div>
                    </div>
                </div>
            </header>
        @endif

        <!-- Main content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        @if(isset($footer))
            <!-- Footer -->
            <footer class="bg-white dark:bg-hcp-secondary-800 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                {{ $footer }}
            </footer>
        @endif
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

        // Configurações globais
        window.HCP = {
            csrfToken: '{{ csrf_token() }}',
            locale: '{{ app()->getLocale() }}',
            user: @auth {!! auth()->user()->toJson() !!} @else null @endauth,
            routes: {
                dashboard: '{{ route('dashboard') }}',
                // Adicionar outras rotas conforme necessário
            }
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

    <!-- Adicionar Alpine.js se não estiver presente -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>