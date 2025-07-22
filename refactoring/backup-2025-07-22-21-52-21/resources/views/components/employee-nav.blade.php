{{-- Navegação do Funcionário - Menu Lateral e Superior --}}
@props(['currentRoute' => null])

@php
    $currentRoute = $currentRoute ?? request()->route()->getName();
    
    $menuItems = [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'home',
            'description' => 'Visão geral do seu progresso'
        ],
        [
            'name' => 'Módulos',
            'route' => 'modules.index',
            'icon' => 'play',
            'description' => 'Conteúdos de aprendizado'
        ],
        [
            'name' => 'Quizzes',
            'route' => 'quizzes.index',
            'icon' => 'help-circle',
            'description' => 'Testes e avaliações'
        ],
        [
            'name' => 'Progresso',
            'route' => 'progress.index',
            'icon' => 'trending-up',
            'description' => 'Acompanhe sua evolução'
        ],
        [
            'name' => 'Gamificação',
            'route' => 'gamification.dashboard',
            'icon' => 'star',
            'description' => 'Pontos e conquistas'
        ],
        [
            'name' => 'Certificados',
            'route' => 'certificates.index',
            'icon' => 'award',
            'description' => 'Seus certificados'
        ],
        [
            'name' => 'Simulados',
            'route' => 'simulados.index',
            'icon' => 'file-text',
            'description' => 'Testes práticos'
        ],
        [
            'name' => 'Notificações',
            'route' => 'notifications.index',
            'icon' => 'bell',
            'description' => 'Mensagens e avisos'
        ]
    ];
@endphp

<!-- Desktop Sidebar Navigation -->
<div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 lg:z-50 lg:bg-white lg:dark:bg-hcp-secondary-800 lg:border-r lg:border-hcp-secondary-200 lg:dark:border-hcp-secondary-700">
    <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0 px-4 mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-hcp-gradient rounded-hcp-lg flex items-center justify-center shadow-hcp-md">
                    <span class="text-white font-bold text-lg">HCP</span>
                </div>
                <div>
                    <span class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                        Dashboard
                    </span>
                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                        Sistema de Onboarding
                    </p>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-2 space-y-1">
            @foreach($menuItems as $item)
                @php
                    $isActive = $currentRoute === $item['route'] || 
                               (isset($item['activeRoutes']) && in_array($currentRoute, $item['activeRoutes']));
                @endphp
                
                <a href="{{ route($item['route']) }}" 
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-hcp-lg transition-all duration-200 hover:scale-[1.02] {{ $isActive 
                       ? 'bg-hcp-gradient text-white shadow-hcp-md' 
                       : 'text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 hover:text-hcp-secondary-900 dark:hover:text-white' }}">
                    
                    <div class="flex items-center justify-center w-8 h-8 mr-3 {{ $isActive ? 'text-white' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400 group-hover:text-hcp-secondary-700 dark:group-hover:text-hcp-secondary-300' }}">
                        <x-icon :name="$item['icon']" size="sm" />
                    </div>
                    
                    <div class="flex-1">
                        <div class="font-medium">{{ $item['name'] }}</div>
                        <div class="text-xs {{ $isActive ? 'text-white/80' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}">
                            {{ $item['description'] }}
                        </div>
                    </div>
                    
                    @if($isActive)
                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
            @endforeach
        </nav>

        <!-- User Profile Section -->
        <div class="flex-shrink-0 px-4 py-4 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
            <div class="flex items-center space-x-3">
                <img src="{{ auth()->user()->avatar_url }}" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-10 h-10 rounded-full border-2 border-hcp-500">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 truncate">
                        {{ auth()->user()->department }}
                    </p>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="p-1 text-hcp-secondary-500 dark:text-hcp-secondary-400 hover:text-hcp-secondary-700 dark:hover:text-hcp-secondary-300 transition-colors">
                        <x-icon name="more-vertical" size="sm" />
                    </button>
                    
                    <div x-show="open" 
                         x-transition
                         @click.away="open = false"
                         class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600 py-1">
                        
                        <form method="POST" action="{{ route('logout') }}">
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

<!-- Mobile Top Navigation -->
<div class="lg:hidden bg-white dark:bg-hcp-secondary-800 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 sticky top-0 z-40">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Mobile Menu Button -->
        <button x-data @click="$dispatch('toggle-mobile-menu')" 
                class="p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-secondary-900 dark:hover:text-white transition-colors rounded-hcp">
            <x-icon name="menu" size="sm" />
        </button>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-hcp-gradient rounded-hcp flex items-center justify-center">
                <span class="text-white font-bold text-sm">HCP</span>
            </div>
            <span class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                Dashboard
            </span>
        </a>

        <!-- User Avatar -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2">
                <img src="{{ auth()->user()->avatar_url }}" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-8 h-8 rounded-full border-2 border-hcp-500">
            </button>
            
            <div x-show="open" 
                 x-transition
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600 py-1">
                
                <div class="px-4 py-3 border-b border-hcp-secondary-200 dark:border-hcp-secondary-600">
                    <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">{{ auth()->user()->email }}</p>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="py-1">
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

<!-- Mobile Sidebar Overlay -->
<div x-data="{ mobileMenuOpen: false }" 
     @toggle-mobile-menu.window="mobileMenuOpen = !mobileMenuOpen"
     class="lg:hidden">
    
    <!-- Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black bg-opacity-50"
         @click="mobileMenuOpen = false"></div>

    <!-- Mobile Sidebar -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-hcp-secondary-800 border-r border-hcp-secondary-200 dark:border-hcp-secondary-700 overflow-y-auto">
        
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-hcp-gradient rounded-hcp flex items-center justify-center">
                        <span class="text-white font-bold text-sm">HCP</span>
                    </div>
                    <span class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">
                        Menu
                    </span>
                </div>
                <button @click="mobileMenuOpen = false" 
                        class="p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-secondary-900 dark:hover:text-white transition-colors rounded-hcp">
                    <x-icon name="x" size="sm" />
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-2">
                @foreach($menuItems as $item)
                    @php
                        $isActive = $currentRoute === $item['route'] || 
                                   (isset($item['activeRoutes']) && in_array($currentRoute, $item['activeRoutes']));
                    @endphp
                    
                    <a href="{{ route($item['route']) }}" 
                       @click="mobileMenuOpen = false"
                       class="group flex items-center px-3 py-3 text-sm font-medium rounded-hcp-lg transition-all duration-200 {{ $isActive 
                           ? 'bg-hcp-gradient text-white shadow-hcp-md' 
                           : 'text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 hover:text-hcp-secondary-900 dark:hover:text-white' }}">
                        
                        <div class="flex items-center justify-center w-8 h-8 mr-3 {{ $isActive ? 'text-white' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400 group-hover:text-hcp-secondary-700 dark:group-hover:text-hcp-secondary-300' }}">
                            <x-icon :name="$item['icon']" size="sm" />
                        </div>
                        
                        <div class="flex-1">
                            <div class="font-medium">{{ $item['name'] }}</div>
                            <div class="text-xs {{ $isActive ? 'text-white/80' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}">
                                {{ $item['description'] }}
                            </div>
                        </div>
                        
                        @if($isActive)
                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        @endif
                    </a>
                @endforeach
            </nav>

            <!-- User Profile -->
            <div class="px-4 py-4 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-10 h-10 rounded-full border-2 border-hcp-500">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 truncate">
                            {{ auth()->user()->department }}
                        </p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-3 py-2 text-sm text-hcp-error-600 dark:text-hcp-error-400 hover:bg-hcp-error-50 dark:hover:bg-hcp-error-900/20 transition-colors rounded-hcp">
                        <x-icon name="log-out" size="sm" class="mr-3" />
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Navigation for Mobile -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-white dark:bg-hcp-secondary-800 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700 safe-area-bottom">
    <div class="flex items-center justify-around py-2">
        @php
            $bottomNavItems = array_slice($menuItems, 0, 5); // Mostrar apenas os 5 primeiros itens
        @endphp
        
        @foreach($bottomNavItems as $item)
            @php
                $isActive = $currentRoute === $item['route'];
            @endphp
            
            <a href="{{ route($item['route']) }}" 
               class="flex flex-col items-center justify-center px-3 py-2 min-w-0 flex-1 text-xs font-medium transition-colors {{ $isActive 
                   ? 'text-hcp-500 dark:text-hcp-400' 
                   : 'text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-secondary-900 dark:hover:text-white' }}">
                
                <div class="flex items-center justify-center w-6 h-6 mb-1">
                    <x-icon :name="$item['icon']" size="sm" />
                </div>
                
                <span class="truncate">{{ $item['name'] }}</span>
                
                @if($isActive)
                    <div class="w-1 h-1 bg-hcp-500 dark:bg-hcp-400 rounded-full mt-1"></div>
                @endif
            </a>
        @endforeach
    </div>
</div>

<!-- Content Padding for Fixed Navigation -->
<style>
    .employee-nav-content {
        padding-left: 0;
        padding-bottom: 80px; /* Space for mobile bottom nav */
    }
    
    @media (min-width: 1024px) {
        .employee-nav-content {
            padding-left: 16rem; /* 64 * 0.25rem = 16rem */
            padding-bottom: 0;
        }
    }
</style>