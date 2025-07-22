<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    
    <title>@yield('title', 'Dashboard - ' . config('app.name'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- PWA Meta Tags -->
    <meta name="application-name" content="HCP Onboarding">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HCP Onboarding">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- iOS PWA -->
    <meta name="apple-mobile-web-app-status-bar" content="#0f172a">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Animações personalizadas */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Barra de rolagem personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Transições suaves */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Efeito de vidro */
        .backdrop-blur {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full bg-hcp-secondary-50 dark:bg-hcp-secondary-900 text-hcp-secondary-900 dark:text-hcp-secondary-100 antialiased transition-colors duration-200">
    <!-- Loader inicial -->
    <div id="initial-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-hcp-secondary-900 transition-opacity duration-500">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-hcp-500 to-hcp-600 animate-pulse">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Carregando...</p>
        </div>
    </div>

    <!-- Aplicação -->
    <div id="app" class="min-h-screen flex flex-col md:flex-row transition-all duration-300">
        <!-- Sidebar Navigation -->
        <x-sidebar-navigation />
        
        <!-- Conteúdo Principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation Bar -->
            <header class="bg-white dark:bg-hcp-secondary-800 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 h-16 flex items-center px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between w-full">
                    <!-- Botão para abrir o menu em mobile -->
                    <button @click="isOpen = true" class="md:hidden text-hcp-secondary-500 hover:text-hcp-600 dark:text-hcp-secondary-400 dark:hover:text-hcp-300">
                        <x-icon name="menu" class="w-6 h-6" />
                    </button>
                    
                    <!-- Breadcrumbs -->
                    <nav class="hidden md:flex items-center space-x-2 text-sm" aria-label="Navegação">
                        @if(isset($breadcrumbs))
                            @foreach($breadcrumbs as $breadcrumb)
                                @if(!$loop->last)
                                    <a href="{{ $breadcrumb['url'] }}" class="text-hcp-600 dark:text-hcp-400 hover:text-hcp-700 dark:hover:text-hcp-300 transition-colors">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                    <span class="text-hcp-secondary-400 dark:text-hcp-secondary-500">/</span>
                                @else
                                    <span class="text-hcp-secondary-700 dark:text-hcp-secondary-300 font-medium">
                                        {{ $breadcrumb['label'] }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                    </nav>
                    
                    <!-- Direita: Notificações, Perfil, etc. -->
                    <div class="flex items-center space-x-4">
                        <!-- Botão de notificações -->
                        <button class="relative p-2 text-hcp-secondary-500 hover:text-hcp-600 dark:text-hcp-secondary-400 dark:hover:text-hcp-300 rounded-full hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors">
                            <x-icon name="bell" class="w-5 h-5" />
                            <span class="absolute top-1 right-1 w-2 h-2 bg-hcp-error-500 rounded-full"></span>
                        </button>
                        
                        <!-- Botão de tema -->
                        <x-theme-toggle size="sm" />
                        
                        <!-- Menu de perfil -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none group">
                                <div class="relative">
                                    <img 
                                        src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                                        alt="{{ auth()->user()->name }}" 
                                        class="w-9 h-9 rounded-full border-2 border-white dark:border-hcp-secondary-800 shadow-sm group-hover:border-hcp-400 transition-colors"
                                    >
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-hcp-success-500 rounded-full border-2 border-white dark:border-hcp-secondary-800"></span>
                                </div>
                                <span class="hidden md:inline-flex items-center text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                    {{ auth()->user()->name }}
                                    <x-icon name="chevron-down" class="ml-1 w-4 h-4" />
                                </span>
                            </button>
                            
                            <!-- Dropdown do perfil -->
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-hcp-secondary-800 border border-hcp-secondary-200 dark:border-hcp-secondary-700 z-50 overflow-hidden"
                            >
                                <div class="px-4 py-3 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                                    <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a 
                                        href="{{ route('profile.show') }}" 
                                        class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors"
                                    >
                                        <x-icon name="user" class="w-4 h-4 mr-3" />
                                        Meu Perfil
                                    </a>
                                    <a 
                                        href="#" 
                                        class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 transition-colors"
                                    >
                                        <x-icon name="settings" class="w-4 h-4 mr-3" />
                                        Configurações
                                    </a>
                                    <div class="border-t border-hcp-secondary-200 dark:border-hcp-secondary-700 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button 
                                            type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-left text-hcp-error-600 dark:text-hcp-error-400 hover:bg-hcp-error-50 dark:hover:bg-hcp-error-900/20 transition-colors"
                                        >
                                            <x-icon name="log-out" class="w-4 h-4 mr-3" />
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Conteúdo da Página -->
            <main class="flex-1 overflow-y-auto focus:outline-none" tabindex="0">
                <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                    @if(isset($header))
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">{{ $header }}</h1>
                            @if(isset($subheader))
                                <p class="mt-1 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ $subheader }}</p>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Conteúdo -->
                    {{ $slot }}
                </div>
                
                <!-- Rodapé -->
                <footer class="mt-12 py-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-800">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                            <div class="mb-4 md:mb-0">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                            </div>
                            <div class="flex space-x-6
                            ">
                                <a href="#" class="hover:text-hcp-600 dark:hover:text-hcp-400 transition-colors">Termos</a>
                                <a href="#" class="hover:text-hcp-600 dark:hover:text-hcp-400 transition-colors">Privacidade</a>
                                <a href="#" class="hover:text-hcp-600 dark:hover:text-hcp-400 transition-colors">Suporte</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2 w-full max-w-xs"></div>

    <!-- Scripts -->
    @stack('scripts')
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        // Esconder o loader quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('initial-loader');
            const app = document.getElementById('app');
            
            // Simular carregamento (remova em produção)
            setTimeout(() => {
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
                
                // Mostrar o conteúdo com fade in
                app.style.opacity = '1';
                
                // Remover o loader do DOM após a animação
                setTimeout(() => {
                    loader.remove();
                }, 500);
            }, 800);
            
            // Atualizar a saudação baseada no horário
            function updateGreeting() {
                const hour = new Date().getHours();
                let greeting = '';
                
                if (hour < 12) {
                    greeting = 'Bom dia';
                } else if (hour < 18) {
                    greeting = 'Boa tarde';
                } else {
                    greeting = 'Boa noite';
                }
                
                const elements = document.querySelectorAll('[data-greeting]');
                elements.forEach(el => {
                    el.textContent = greeting;
                });
            }
            
            updateGreeting();
            setInterval(updateGreeting, 60000); // Atualizar a cada minuto
            
            // Adicionar classe ao body quando o menu estiver aberto em mobile
            document.addEventListener('alpine:init', () => {
                Alpine.data('sidebar', () => ({
                    open: false,
                    isMobile: window.innerWidth < 768,
                    init() {
                        this.$watch('open', value => {
                            if (value) {
                                document.body.classList.add('overflow-hidden');
                            } else {
                                document.body.classList.remove('overflow-hidden');
                            }
                        });
                        
                        window.addEventListener('resize', () => {
                            this.isMobile = window.innerWidth < 768;
                            if (!this.isMobile) {
                                this.open = false;
                                document.body.classList.remove('overflow-hidden');
                            }
                        });
                    }
                }));
            });
        });
        
        // Função para mostrar notificações toast
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const types = {
                success: {
                    bg: 'bg-green-100 dark:bg-green-900/80',
                    text: 'text-green-800 dark:text-green-200',
                    icon: 'check-circle',
                },
                error: {
                    bg: 'bg-red-100 dark:bg-red-900/80',
                    text: 'text-red-800 dark:text-red-200',
                    icon: 'alert-circle',
                },
                warning: {
                    bg: 'bg-yellow-100 dark:bg-yellow-900/80',
                    text: 'text-yellow-800 dark:text-yellow-200',
                    icon: 'alert-triangle',
                },
                info: {
                    bg: 'bg-blue-100 dark:bg-blue-900/80',
                    text: 'text-blue-800 dark:text-blue-200',
                    icon: 'info',
                }
            };
            
            const config = types[type] || types.info;
            
            toast.className = `${config.bg} ${config.text} px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3 transform transition-all duration-300 translate-y-2 opacity-0`;
            toast.innerHTML = `
                <x-icon name="${config.icon}" class="w-5 h-5 mt-0.5 flex-shrink-0" />
                <span class="flex-1">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-${type}-500 hover:text-${type}-700 dark:text-${type}-300 dark:hover:text-${type}-100">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger reflow
            void toast.offsetWidth;
            
            // Animar entrada
            toast.classList.remove('translate-y-2', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            // Remover após 5 segundos
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }
        
        // Exemplo de uso:
        // showToast('Operação realizada com sucesso!', 'success');
        // showToast('Ocorreu um erro ao salvar.', 'error');
        // showToast('Atenção: dados incompletos.', 'warning');
        // showToast('Nova atualização disponível!', 'info');
        
        // Expor a função globalmente
        window.showToast = showToast;
    </script>
    
    @livewireScripts
</body>
</html>
