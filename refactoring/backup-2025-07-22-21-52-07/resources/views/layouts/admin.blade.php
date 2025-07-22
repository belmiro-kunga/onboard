<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    
    <title>{{ $title ?? 'Painel Administrativo - HCP' }}</title>
    
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
    <div id="app" class="h-full opacity-0 transition-opacity duration-500 flex">
        <!-- Sidebar (menu lateral) -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white dark:bg-hcp-secondary-800 border-r border-hcp-secondary-200 dark:border-hcp-secondary-700">
                <!-- Logo -->
                <div class="flex items-center h-16 px-4 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-red-600 to-red-700 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">A</span>
                        </div>
                        <span class="text-xl font-semibold text-hcp-secondary-900 dark:text-white">
                            Admin HCP
                        </span>
                    </a>
                </div>
                
                <!-- Menu de navegação -->
                <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4">
                    <nav class="flex-1 px-2 space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        
                        <!-- Gerenciamento de Usuários -->
                        <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Usuários
                        </a>
                        
                        <!-- Gerenciamento de Módulos -->
                        <div x-data="{ open: {{ request()->routeIs('admin.modules.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" type="button" class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.modules.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.modules.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Módulos
                                <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="open" class="mt-1 pl-4 space-y-1">
                                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Listar Módulos
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Criar Módulo
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Conteúdos
                                </a>
                            </div>
                        </div>
                        
                        <!-- Gerenciamento de Quizzes -->
                        <div x-data="{ open: {{ request()->routeIs('admin.quizzes.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" type="button" class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.quizzes.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.quizzes.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Quizzes
                                <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="open" class="mt-1 pl-4 space-y-1">
                                <a href="{{ route('admin.quizzes.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Listar Quizzes
                                </a>
                                <a href="{{ route('admin.quizzes.create') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Criar Quiz
                                </a>
                            </div>
                        </div>
                        
                        <!-- Gerenciamento de Simulados -->
                        <div x-data="{ open: {{ request()->routeIs('admin.simulados.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" type="button" class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.simulados.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.simulados.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Simulados
                                <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="open" class="mt-1 pl-4 space-y-1">
                                <a href="{{ route('admin.simulados.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Listar Simulados
                                </a>
                                <a href="{{ route('admin.simulados.create') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700">
                                    Criar Simulado
                                </a>
                            </div>
                        </div>
                        
                        <!-- Gerenciamento de Certificados -->
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.certificates.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.certificates.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Certificados
                        </a>
                        
                        <!-- Relatórios -->
                        <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.reports.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Relatórios
                        </a>
                        
                        <!-- Configurações -->
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.settings.*') ? 'text-hcp-primary-500 dark:text-hcp-primary-400' : 'text-hcp-secondary-500 dark:text-hcp-secondary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Configurações
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Conteúdo principal -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Header administrativo -->
            <header class="bg-white dark:bg-hcp-secondary-800 shadow-hcp border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Menu mobile toggle -->
                        <div class="flex md:hidden">
                            <button type="button" class="p-2 rounded-md text-hcp-secondary-500 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-hcp-500" id="mobile-menu-button">
                                <span class="sr-only">Abrir menu</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Título da página (visível apenas em mobile) -->
                        <div class="md:hidden">
                            <h1 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white">Admin HCP</h1>
                        </div>

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

            <!-- Menu mobile (visível apenas em mobile) -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.users.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                        Usuários
                    </a>
                    <a href="{{ route('admin.quizzes.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.quizzes.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                        Quizzes
                    </a>
                    <a href="{{ route('admin.simulados.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.simulados.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                        Simulados
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.reports.*') ? 'bg-hcp-primary-100 text-hcp-primary-700 dark:bg-hcp-primary-900/30 dark:text-hcp-primary-300' : 'text-hcp-secondary-600 hover:bg-hcp-secondary-100 dark:text-hcp-secondary-400 dark:hover:bg-hcp-secondary-700' }}">
                        Relatórios
                    </a>
                </div>
            </div>

            <!-- Main content -->
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
                {{ $slot }}
            </main>
        </div>
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