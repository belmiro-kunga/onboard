<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{ $title ?? 'Painel Administrativo - HCP' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="description" content="Painel Administrativo do Sistema de Onboarding HCP">
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Admin Menu Animations -->
    <link rel="stylesheet" href="{{ asset('css/admin-menu-click-animations.css') }}">
    
    <!-- Admin Toggle Switch -->
    <link rel="stylesheet" href="{{ asset('css/admin-toggle-switch.css') }}">
    
    <!-- Admin Sidebar - Estilos Otimizados -->
    <style>
        /* Botão de toggle - design moderno e funcional */
        .sidebar-toggle-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6) !important;
            border: 2px solid #6366f1 !important;
            color: white !important;
            width: 44px !important;
            height: 44px !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
            position: relative !important;
            z-index: 100 !important;
        }
        
        .sidebar-toggle-btn:hover {
            background: linear-gradient(135deg, #8b5cf6, #6366f1) !important;
            transform: scale(1.05) !important;
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4) !important;
        }
        
        .sidebar-toggle-btn:active {
            transform: scale(0.95) !important;
        }
        
        /* Transições suaves para o sidebar */
        aside {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        /* Tooltips para menu colapsado - otimizados */
        .w-16 nav a {
            position: relative !important;
        }
        
        .w-16 nav a:hover::after {
            content: attr(title) !important;
            position: absolute !important;
            left: 70px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: rgba(31, 41, 55, 0.95) !important;
            color: white !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            white-space: nowrap !important;
            z-index: 70 !important;
            pointer-events: none !important;
            backdrop-filter: blur(8px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
        }
        
        .w-16 nav a:hover::before {
            content: '' !important;
            position: absolute !important;
            left: 65px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            border: 5px solid transparent !important;
            border-right-color: rgba(31, 41, 55, 0.95) !important;
            z-index: 70 !important;
            pointer-events: none !important;
        }
        
        /* Responsividade otimizada */
        @media (max-width: 768px) {
            .sidebar-toggle-container {
                display: none !important;
            }
        }
        
        /* Indicador visual de estado do botão */
        .sidebar-toggle-btn svg {
            transition: transform 0.3s ease !important;
        }
        
        .sidebar-toggle-btn:hover svg {
            transform: rotate(180deg) !important;
        }
    </style>
    
    @stack('head-scripts')
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full bg-hcp-bg-primary text-hcp-text-primary antialiased">
<div x-data="{ sidebarOpen: false, sidebarExpanded: true }" class="flex h-full min-h-screen">
    <!-- Overlay para mobile -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black bg-opacity-40 md:hidden" @click="sidebarOpen = false"></div>
    <!-- Sidebar -->
    <aside :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarExpanded ? 'w-64' : 'w-16'
    ]" class="fixed z-50 inset-y-0 left-0 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 md:static md:translate-x-0">
        <!-- Botão de recolher/expandir (sempre visível, visualmente destacado) -->
        <div class="sidebar-toggle-container flex items-center justify-end h-16 px-2 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 bg-white dark:bg-gray-900 sticky top-0 z-50">
            <button @click="sidebarExpanded = !sidebarExpanded" 
                    aria-label="Alternar menu" 
                    data-tooltip="Colapsar/Expandir Menu"
                    class="sidebar-toggle-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                    :class="sidebarExpanded ? 'expanded' : 'collapsed'">
                <template x-if="sidebarExpanded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M19 19l-7-7 7-7" />
                    </svg>
                </template>
                <template x-if="!sidebarExpanded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
                    </svg>
                </template>
            </button>
        </div>
        <!-- Botão de fechar (mobile) -->
        <button @click="sidebarOpen = false" class="md:hidden absolute top-4 right-4 p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <!-- Logo -->
        <div class="flex items-center h-16 px-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-red-600 to-red-700 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">A</span>
                </div>
                <span x-show="sidebarExpanded" class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">Admin HCP</span>
            </a>
        </div>
        <!-- Menu -->
        <nav class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 transition-all rounded-md mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700 text-white shadow-lg scale-105 ring-2 ring-indigo-400 animate-glow' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}" :class="sidebarExpanded ? 'justify-start' : 'justify-center'" :title="sidebarExpanded ? '' : 'Dashboard'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span x-show="sidebarExpanded" class="ml-3">Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 transition-all rounded-md mb-2 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-700 text-white shadow-lg scale-105 ring-2 ring-indigo-400 animate-glow' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}" :class="sidebarExpanded ? 'justify-start' : 'justify-center'" :title="sidebarExpanded ? '' : 'Usuários'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span x-show="sidebarExpanded" class="ml-3">Usuários</span>
            </a>
            <a href="{{ route('admin.modules.index') }}" class="flex items-center px-4 py-3 transition-all rounded-md mb-2 {{ request()->routeIs('admin.modules.*') ? 'bg-indigo-700 text-white shadow-lg scale-105 ring-2 ring-indigo-400 animate-glow' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}" :class="sidebarExpanded ? 'justify-start' : 'justify-center'" :title="sidebarExpanded ? '' : 'Módulos'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span x-show="sidebarExpanded" class="ml-3">Módulos</span>
            </a>
            <!-- Adicione outros menus aqui -->
        </nav>
    </aside>
    <!-- Conteúdo principal -->
    <div :class="sidebarExpanded ? 'md:ml-64' : 'md:ml-16'" class="flex-1 flex flex-col min-h-screen transition-all duration-300">
        <!-- Topbar -->
        <header class="flex items-center h-16 px-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <!-- Botão hamburger (mobile) -->
            <button @click="sidebarOpen = true" class="md:hidden p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <h1 class="ml-4 text-xl font-bold">{{ $title ?? 'Painel Admin' }}</h1>
        </header>
        <!-- Conteúdo dinâmico -->
        <main class="flex-1 p-4">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</div>
<!-- Admin Menu Click Animations Script -->
<script src="{{ asset('js/admin-menu-click-animations.js') }}"></script>

<!-- Toggle Switch Script -->
<script src="{{ asset('js/admin-toggle-switch.js') }}"></script>

<!-- Admin Sidebar - Script Limpo e Otimizado -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atalho de teclado Ctrl+B para toggle do sidebar
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            const toggleBtn = document.querySelector('.sidebar-toggle-btn');
            if (toggleBtn) {
                toggleBtn.click();
            }
        }
    });
    
    // Persistência de estado no localStorage
    const saveState = (expanded) => {
        localStorage.setItem('admin_sidebar_expanded', expanded);
    };
    
    const loadState = () => {
        const saved = localStorage.getItem('admin_sidebar_expanded');
        return saved !== null ? saved === 'true' : true;
    };
    
    // Aplicar estado salvo quando carregar
    setTimeout(() => {
        try {
            const savedState = loadState();
            const alpineData = document.querySelector('[x-data]').__x.$data;
            if (alpineData && savedState !== alpineData.sidebarExpanded) {
                alpineData.sidebarExpanded = savedState;
            }
        } catch (e) {
            console.log('Estado do sidebar será aplicado quando Alpine.js estiver pronto');
        }
    }, 200);
    
    // Observar mudanças no sidebar para salvar estado
    const sidebar = document.querySelector('aside');
    if (sidebar) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const isExpanded = sidebar.classList.contains('w-64');
                    saveState(isExpanded);
                }
            });
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>

@stack('scripts')
</body>
</html> 