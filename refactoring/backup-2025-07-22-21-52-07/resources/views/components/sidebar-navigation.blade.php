@props(['active'])

@php
$navigation = [
    [
        'name' => 'Dashboard',
        'href' => route('dashboard'),
        'icon' => 'home',
        'active' => request()->routeIs('dashboard'),
    ],
    [
        'name' => 'Módulos',
        'href' => route('modules.index'),
        'icon' => 'book-open',
        'active' => request()->routeIs('modules.*'),
    ],
    [
        'name' => 'Quizzes',
        'href' => route('quizzes.index'),
        'icon' => 'help-circle',
        'active' => request()->routeIs('quizzes.*'),
    ],
    [
        'name' => 'Gamificação',
        'href' => route('gamification.dashboard'),
        'icon' => 'award',
        'active' => request()->routeIs('gamification.*'),
    ],
    [
        'name' => 'Certificados',
        'href' => route('certificates.index'),
        'icon' => 'file-text',
        'active' => request()->routeIs('certificates.*'),
    ],
    [
        'name' => 'Notificações',
        'href' => route('notifications.index'),
        'icon' => 'bell',
        'active' => request()->routeIs('notifications.*'),
        'badge' => 3, // Número de notificações não lidas
    ],
];
@endphp

<div 
    x-data="{ 
        isOpen: window.innerWidth >= 1280, 
        isHovered: false,
        isMobile: window.innerWidth < 1280,
        checkScreen() { 
            this.isMobile = window.innerWidth < 1280;
            this.isOpen = !this.isMobile;
        }
    }"
    x-init="
        checkScreen();
        window.addEventListener('resize', () => {
            checkScreen();
        });
    "
    @keydown.window.escape="isOpen = false"
    class="fixed inset-y-0 left-0 z-40 flex flex-col bg-white dark:bg-hcp-secondary-800 border-r border-hcp-secondary-200 dark:border-hcp-secondary-700 transition-all duration-300 ease-in-out"
    :class="{
        'w-64': isOpen && !isMobile,
        'w-20': !isOpen && !isMobile,
        'w-0': !isOpen && isMobile,
        'w-64': isOpen && isMobile,
        'shadow-xl': isOpen && isMobile
    }"
>
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
        <div x-show="isOpen || isMobile" class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-hcp-500 to-hcp-600 flex items-center justify-center text-white font-bold">
                HCP
            </div>
            <span class="text-xl font-bold text-hcp-secondary-900 dark:text-white">Onboard</span>
        </div>
        
        <button 
            @click="isOpen = !isOpen"
            class="p-1 rounded-md text-hcp-secondary-500 hover:text-hcp-secondary-700 dark:text-hcp-secondary-400 dark:hover:text-hcp-secondary-200 focus:outline-none focus:ring-2 focus:ring-hcp-500"
            :aria-label="isOpen ? 'Recolher menu' : 'Expandir menu'"
        >
            <x-icon 
                :name="isOpen ? 'chevron-left' : 'menu'" 
                class="w-6 h-6 transition-transform duration-200"
                :class="{'rotate-180': !isOpen}"
            />
        </button>
    </div>

    <!-- Navegação -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        @foreach($navigation as $item)
            <a 
                href="{{ $item['href'] }}" 
                @class([
                    'group flex items-center px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-all duration-200',
                    'bg-hcp-50 text-hcp-700 dark:bg-hcp-900/50 dark:text-white' => $item['active'],
                    'text-hcp-secondary-600 hover:bg-hcp-50 hover:text-hcp-900 dark:text-hcp-secondary-300 dark:hover:bg-hcp-secondary-700/50 dark:hover:text-white' => !$item['active']
                ])
                @mouseenter="!isMobile && (isHovered = true)"
                @mouseleave="!isMobile && (isHovered = false)"
            >
                <x-icon 
                    :name="$item['icon']" 
                    class="flex-shrink-0 w-5 h-5 mr-3"
                    :class="$item['active'] ? 'text-hcp-600 dark:text-hcp-400' : 'text-hcp-secondary-400 group-hover:text-hcp-600 dark:text-hcp-secondary-500 dark:group-hover:text-hcp-400'"
                />
                <span x-show="isOpen || isMobile" class="truncate">
                    {{ $item['name'] }}
                </span>
                
                @if(isset($item['badge']) && $item['badge'] > 0)
                    <span 
                        class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-hcp-error-500 rounded-full"
                        :class="{'opacity-0': !isOpen && !isMobile, 'opacity-100': isOpen || isMobile}"
                    >
                        {{ $item['badge'] }}
                    </span>
                @endif
                
                @if($item['active'])
                    <span class="absolute right-0 w-1 h-6 bg-hcp-500 rounded-l-full"></span>
                @endif
            </a>
        @endforeach
    </nav>

    <!-- Rodapé do menu -->
    <div 
        class="p-4 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700"
        x-data="{ showProfileMenu: false }"
        @click.away="showProfileMenu = false"
    >
        <button 
            @click="showProfileMenu = !showProfileMenu"
            class="flex items-center w-full text-left focus:outline-none group"
        >
            <div class="relative">
                <img 
                    src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                    alt="{{ auth()->user()->name }}" 
                    class="w-10 h-10 rounded-full border-2 border-white dark:border-hcp-secondary-700 shadow-sm group-hover:border-hcp-400 transition-colors"
                >
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-hcp-success-500 rounded-full border-2 border-white dark:border-hcp-secondary-800"></span>
            </div>
            
            <div class="ml-3 overflow-hidden" x-show="isOpen || isMobile">
                <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            
            <x-icon 
                name="chevron-down" 
                class="ml-auto w-4 h-4 text-hcp-secondary-400 group-hover:text-hcp-secondary-600 dark:text-hcp-secondary-500 dark:group-hover:text-hcp-secondary-300 transition-transform duration-200"
                :class="{'rotate-180': showProfileMenu}"
                x-show="isOpen || isMobile"
            />
        </button>
        
        <!-- Menu de perfil -->
        <div 
            x-show="showProfileMenu && (isOpen || isMobile)"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="mt-2 w-full bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg overflow-hidden border border-hcp-secondary-200 dark:border-hcp-secondary-700"
        >
            <div class="py-1">
                <a 
                    href="{{ route('profile.show') }}" 
                    class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700"
                >
                    <x-icon name="user" class="w-4 h-4 mr-2" />
                    Perfil
                </a>
                <a 
                    href="#" 
                    class="flex items-center px-4 py-2 text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700"
                >
                    <x-icon name="settings" class="w-4 h-4 mr-2" />
                    Configurações
                </a>
                <div class="border-t border-hcp-secondary-100 dark:border-hcp-secondary-700 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit"
                        class="flex items-center w-full px-4 py-2 text-sm text-left text-hcp-error-600 dark:text-hcp-error-400 hover:bg-hcp-error-50 dark:hover:bg-hcp-error-900/20"
                    >
                        <x-icon name="log-out" class="w-4 h-4 mr-2" />
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Overlay para mobile -->
<div 
    x-show="isOpen && isMobile"
    @click="isOpen = false"
    class="fixed inset-0 z-30 bg-black bg-opacity-50 transition-opacity duration-300"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
    x-cloak
></div>

@push('scripts')
<script>
    // Fechar menu ao clicar em um link no mobile
    document.querySelectorAll('.mobile-menu-link').forEach(link => {
        link.addEventListener('click', () => {
            const sidebar = document.querySelector('[x-data]');
            if (sidebar && window.innerWidth < 1280) {
                sidebar.__x.$data.isOpen = false;
            }
        });
    });
    
    // Fechar menu ao pressionar ESC
    document.addEventListener('keydown', (e) => {
        const sidebar = document.querySelector('[x-data]');
        if (e.key === 'Escape' && sidebar && window.innerWidth < 1280) {
            sidebar.__x.$data.isOpen = false;
        }
    });
</script>
@endpush
