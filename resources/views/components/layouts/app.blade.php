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
<body class="h-full bg-gray-50 dark:bg-gray-900 font-sans antialiased" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: false 
      }"
      x-init="
          // Carregar estado do localStorage
          if (localStorage.getItem('sidebarCollapsed') === 'true') {
              sidebarCollapsed = true;
          }
          
          // Salvar estado quando mudar
          $watch('sidebarCollapsed', value => {
              localStorage.setItem('sidebarCollapsed', value);
              console.log('Sidebar collapsed:', value);
          });
      ">
    
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-gray-800 shadow-lg transform transition-all duration-300 ease-in-out" 
         :class="{
             'w-64': !sidebarCollapsed,
             'w-16': sidebarCollapsed,
             'translate-x-0': sidebarOpen || !sidebarCollapsed,
             '-translate-x-full': !sidebarOpen && sidebarCollapsed
         }">
        
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-sm">HCP</span>
                </div>
                <span class="text-xl font-semibold text-gray-900 dark:text-white transition-all duration-300"
                      :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                    Sistema HCP
                </span>
            </a>
            
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" 
                    class="lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-6 px-3">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Dashboard
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Dashboard
                    </div>
                </a>

                <!-- Módulos -->
                <a href="{{ route('modules.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('modules.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Módulos
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Módulos
                    </div>
                </a>

                <!-- Quizzes -->
                <a href="{{ route('quizzes.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('quizzes.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Quizzes
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Quizzes
                    </div>
                </a>

                <!-- Simulados -->
                <a href="{{ route('simulados.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('simulados.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Simulados
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Simulados
                    </div>
                </a>

                <!-- Gamificação -->
                <a href="{{ route('gamification.dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('gamification.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Gamificação
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Gamificação
                    </div>
                </a>

                <!-- Certificados -->
                <a href="{{ route('certificates.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('certificates.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Certificados
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Certificados
                    </div>
                </a>

                <!-- Progresso -->
                <a href="{{ route('progress.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('progress.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Meu Progresso
                    </span>
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Meu Progresso
                    </div>
                </a>

                <!-- Notificações -->
                <a href="{{ route('notifications.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative {{ request()->routeIs('notifications.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mr-0' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Notificações
                    </span>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                            {{ auth()->user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                    <!-- Tooltip -->
                    <div x-show="sidebarCollapsed" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                        Notificações
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </div>
                </a>
            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700">
            @auth
                <div class="flex items-center space-x-3">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=0ea5e9&background=f0f9ff' }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-10 h-10 rounded-full border-2 border-blue-500 flex-shrink-0">
                    <div class="flex-1 min-w-0 transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-1 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                <!-- Tooltip for user when collapsed -->
                <div x-show="sidebarCollapsed" 
                     class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded shadow-lg opacity-0 hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap">
                    {{ auth()->user()->name }}
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm font-medium transition-all duration-300" :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                        Fazer Login
                    </span>
                </a>
            @endauth
        </div>
    </div>

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" 
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Main Content -->
    <div class="transition-all duration-300 ease-in-out" 
         :class="{
             'lg:pl-64': !sidebarCollapsed,
             'lg:pl-16': sidebarCollapsed
         }">
        <!-- Top Navigation Bar -->
        <div class="sticky top-0 z-30 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Desktop sidebar toggle -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="hidden lg:flex p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Page Title -->
                <div class="flex-1 lg:flex-none">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white lg:hidden">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                </div>

                <!-- Right side actions -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>

                    <!-- Notifications (mobile) -->
                    <button class="lg:hidden relative p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>