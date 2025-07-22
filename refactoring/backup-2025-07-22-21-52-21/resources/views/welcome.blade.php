<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0ea5e9">
    
    <title>{{ $title ?? 'Bem-vindo à Hemera Capital Partners' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Meta tags para SEO otimizado -->
    <meta name="description" content="Sistema de onboarding interativo da Hemera Capital Partners. Transforme sua experiência de treinamento com vídeos interativos, gamificação e acompanhamento personalizado.">
    <meta name="keywords" content="onboarding, treinamento corporativo, HCP, Hemera Capital Partners, gamificação, vídeos interativos, desenvolvimento profissional">
    <meta name="author" content="Hemera Capital Partners">
    
    <!-- Open Graph otimizado -->
    <meta property="og:title" content="{{ $title ?? 'Bem-vindo à Hemera Capital Partners' }}">
    <meta property="og:description" content="Sistema de onboarding interativo da Hemera Capital Partners. Transforme sua experiência de treinamento com vídeos interativos, gamificação e acompanhamento personalizado.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/hcp-onboarding-preview.jpg') }}">
    <meta property="og:site_name" content="Hemera Capital Partners">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Bem-vindo à Hemera Capital Partners' }}">
    <meta name="twitter:description" content="Sistema de onboarding interativo da Hemera Capital Partners">
    <meta name="twitter:image" content="{{ asset('images/hcp-onboarding-preview.jpg') }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('images/hcp-building.jpg') }}" as="image">
    <link rel="preload" href="{{ asset('images/hcp-logo.png') }}" as="image">
    
    <!-- YouTube API -->
    <script src="https://www.youtube.com/iframe_api"></script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- YouTube Background Script -->
    <script src="{{ asset('js/youtube-background.js') }}" defer></script>
    
    <!-- Estilos específicos da página com otimizações -->
    <style>
        /* CSS Custom Properties para melhor performance */
        :root {
            --hcp-primary: #0ea5e9;
            --hcp-secondary: #64748b;
            --hcp-success: #10b981;
            --hcp-accent: #8b5cf6;
            --hcp-warning: #f59e0b;
            --hcp-error: #ef4444;
            --hcp-dark: #0f172a;
            --hcp-light: #f8fafc;
        }
        
        /* Hero section com vídeo de background */
        .hero-video-bg {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }
        
        .hero-video-bg video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        
        .hero-video-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.7) 0%, 
                rgba(30, 41, 59, 0.5) 50%, 
                rgba(15, 23, 42, 0.7) 100%
            );
            z-index: 2;
        }
        
        /* Fallback para navegadores que não suportam vídeo */
        .hero-video-bg .bg-gradient-to-br {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        /* Otimização para mobile */
        @media (max-width: 768px) {
            .hero-video-bg video {
                object-position: center center;
            }
        }
        
        /* Hero section com imagem real do edifício HCP (fallback) */
        .hero-bg {
            background-image: url('{{ asset("images/hcp-building.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            min-height: 100vh;
        }
        
        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.8) 0%, 
                rgba(30, 41, 59, 0.6) 50%, 
                rgba(15, 23, 42, 0.8) 100%
            );
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 10;
        }
        
        /* Card com glassmorphism otimizado */
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 20;
            transform: translate3d(0, 0, 0);
            will-change: transform;
        }
        
        [data-theme="dark"] .welcome-card {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }
        
        /* Elementos flutuantes com animações otimizadas */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
            will-change: transform;
        }
        
        .floating-element:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .floating-element:nth-child(2) { top: 20%; right: 10%; animation-delay: 2s; }
        .floating-element:nth-child(3) { bottom: 20%; left: 15%; animation-delay: 4s; }
        .floating-element:nth-child(4) { bottom: 10%; right: 20%; animation-delay: 1s; }
        
        @keyframes float {
            0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
            50% { transform: translate3d(0, -20px, 0) rotate(5deg); }
        }
        
        /* Parallax otimizado */
        .parallax-bg {
            transform: translate3d(0, 0, 0);
            will-change: transform;
        }
        
        /* Gradiente de texto otimizado */
        .gradient-text {
            background: linear-gradient(135deg, var(--hcp-primary) 0%, var(--hcp-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Botões com efeitos otimizados */
        .btn-glow:hover {
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.5);
            transform: translateY(-2px) scale(1.02);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Animações de entrada otimizadas */
        .card-entrance {
            animation: cardEntrance 1s ease-out;
        }
        
        @keyframes cardEntrance {
            0% {
                opacity: 0;
                transform: translate3d(0, 50px, 0) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
        }
        
        /* Efeito ripple otimizado */
        .ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 600ms linear;
            background-color: rgba(255, 255, 255, 0.6);
            pointer-events: none;
        }
        
        [data-theme="dark"] .ripple {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Skeleton loading para melhor UX */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Melhorias para navegação inferior */
        body {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        
        @media (max-width: 768px) {
            body {
                padding-bottom: calc(70px + env(safe-area-inset-bottom, 0px));
            }
        }
        
        /* Smooth scroll otimizado */
        html {
            scroll-behavior: smooth;
        }
        
        /* Otimizações para touch */
        button, .btn-hcp {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        
        /* Hierarquia de z-index otimizada */
        .hero-bg { z-index: 1; }
        .floating-elements { z-index: 2; }
        .welcome-card { z-index: 20; }
        .scroll-indicator { z-index: 25; }
        nav { z-index: 50 !important; }
        .modal { z-index: 100; }
        .toast { z-index: 200; }
        
        /* Seções com sombras otimizadas */
        section {
            position: relative;
            z-index: 15;
            background: white;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        [data-theme="dark"] section {
            background: var(--hcp-dark);
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        
        .card-hcp {
            position: relative;
            z-index: 16;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hcp:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Animações suaves otimizadas */
        @keyframes bounce-gentle {
            0%, 20%, 50%, 80%, 100% {
                transform: translate3d(0, 0, 0);
            }
            40% {
                transform: translate3d(0, -10px, 0);
            }
            60% {
                transform: translate3d(0, -5px, 0);
            }
        }
        
        .animate-bounce-gentle {
            animation: bounce-gentle 2s infinite;
        }
        
        /* Social proof section */
        .social-proof {
            background: linear-gradient(135deg, var(--hcp-primary) 0%, var(--hcp-accent) 100%);
            color: white;
            padding: 3rem 0;
        }
        
        .social-proof .stat {
            text-align: center;
        }
        
        .social-proof .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .social-proof .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }
        
        /* Testimonials section */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* CTA section melhorado */
        .cta-section {
            background: linear-gradient(135deg, var(--hcp-primary) 0%, var(--hcp-accent) 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .btn-white {
            background: white;
            color: var(--hcp-primary);
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline-white {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-white:hover {
            background: white;
            color: var(--hcp-primary);
            transform: translateY(-2px);
        }
        
        /* Acessibilidade melhorada */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Focus states para acessibilidade */
        button:focus,
        a:focus,
        input:focus,
        select:focus {
            outline: 2px solid var(--hcp-primary);
            outline-offset: 2px;
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .welcome-card {
                border: 2px solid var(--hcp-primary);
            }
            
            .btn-hcp {
                border: 2px solid currentColor;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Modal transparente com vídeo de background visível */
        #requestAccessModal {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        #requestAccessModal > div {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Efeito de vidro nos inputs */
        #requestAccessModal input,
        #requestAccessModal select {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }
        
        #requestAccessModal input:focus,
        #requestAccessModal select:focus {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
    </style>
</head>
<body class="h-full antialiased">
    <!-- Hero Section with Video Background -->
    <div class="hero-video-bg min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- YouTube Video Background -->
        <div class="absolute inset-0 w-full h-full">
            <div id="youtube-player" class="w-full h-full"></div>
            <!-- Fallback para navegadores que não suportam YouTube -->
            <div class="w-full h-full bg-gradient-to-br from-hcp-primary-500 via-hcp-primary-600 to-hcp-accent-500" id="video-fallback"></div>
        </div>
        
        <!-- Video overlay for better text readability -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        
        <!-- Conteúdo principal -->
        <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Logo HCP -->
            <div class="mb-8 animate-fade-in-down">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-hcp-gradient rounded-2xl mb-4 shadow-hcp-lg mx-auto">
                    <span class="text-white font-bold text-2xl">HCP</span>
                </div>
            </div>
            
            <!-- Título principal -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 animate-fade-in-up animate-delay-200">
                <span class="gradient-text">Welcome to</span><br>
                <span class="text-white">Hemera Capital Partners</span>
            </h1>
            
            <!-- Subtítulo -->
            <p class="text-lg sm:text-xl text-gray-200 mb-4 max-w-2xl mx-auto animate-fade-in-up animate-delay-300">
                Explore nosso sistema de onboarding interativo projetado para guiá-lo através de nossos processos, recursos e dinâmicas de equipe.
            </p>
            
            <!-- Call to action -->
            <p class="text-base text-gray-300 mb-8 animate-fade-in-up animate-delay-400">
                Seja você um novo colaborador ou buscando acesso, estamos aqui para apoiar sua jornada.
            </p>
            
            <!-- Frase de impacto -->
            <div class="mb-10 animate-fade-in-up animate-delay-500">
                <p class="text-xl sm:text-2xl font-medium text-white italic">
                    "Sua jornada para o sucesso começa aqui."
                </p>
            </div>
            
            <!-- Botões de ação -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8 animate-fade-in-up animate-delay-600">
                <a href="{{ route('login') }}" 
                   class="btn-hcp btn-hcp-primary btn-glow px-8 py-4 text-lg font-semibold rounded-hcp-xl shadow-hcp-lg hover:shadow-hcp-xl transform hover:-translate-y-1 transition-all duration-300">
                    <x-icon name="arrow-right" size="sm" />
                    Entrar
                </a>
                
                <button type="button" 
                        onclick="openRequestAccessModal()"
                        class="btn-hcp btn-hcp-secondary px-8 py-4 text-lg font-semibold rounded-hcp-xl shadow-hcp hover:shadow-hcp-lg transform hover:-translate-y-1 transition-all duration-300">
                    <x-icon name="user" size="sm" />
                    Solicitar Acesso
                </button>
            </div>
            
            <!-- Estatísticas ou destaques -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-8 border-t border-white/20 animate-fade-in-up animate-delay-700 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white mb-1">100+</div>
                    <div class="text-sm text-gray-200">Módulos Interativos</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white mb-1">95%</div>
                    <div class="text-sm text-gray-200">Taxa de Conclusão</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white mb-1">24/7</div>
                    <div class="text-sm text-gray-200">Suporte Disponível</div>
                </div>
            </div>    
        </div>
    </div>
    
    <!-- Seção de recursos/benefícios -->
    <section class="py-20 bg-white dark:bg-hcp-secondary-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-hcp-secondary-900 dark:text-white mb-4">
                    Por que escolher nosso sistema?
                </h2>
                <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-300 max-w-2xl mx-auto">
                    Uma experiência de onboarding moderna e interativa projetada para o seu sucesso.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Recurso 1 -->
                <div class="card-hcp text-center p-8 hover-lift">
                    <div class="w-16 h-16 bg-hcp-gradient rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-icon name="play" size="lg" class="text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        Vídeos Interativos
                    </h3>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-300">
                        Aprenda através de conteúdo em vídeo com marcadores interativos e quizzes integrados.
                    </p>
                </div>
                
                <!-- Recurso 2 -->
                <div class="card-hcp text-center p-8 hover-lift">
                    <div class="w-16 h-16 bg-hcp-success-gradient rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-icon name="star" size="lg" class="text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        Gamificação
                    </h3>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-300">
                        Ganhe pontos, medalhas e suba de nível enquanto completa seu treinamento.
                    </p>
                </div>
                
                <!-- Recurso 3 -->
                <div class="card-hcp text-center p-8 hover-lift">
                    <div class="w-16 h-16 bg-hcp-accent-gradient rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-icon name="users" size="lg" class="text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-4">
                        Acompanhamento Personalizado
                    </h3>
                    <p class="text-hcp-secondary-600 dark:text-hcp-secondary-300">
                        Receba feedback personalizado e acompanhe seu progresso em tempo real.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Seção de Social Proof -->
    <section class="social-proof">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Confiado por centenas de profissionais
                </h2>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Junte-se aos colaboradores que já transformaram sua experiência de onboarding
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="stat">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Colaboradores Ativos</div>
                </div>
                <div class="stat">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Taxa de Satisfação</div>
                </div>
                <div class="stat">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Suporte Disponível</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Seção de Testimonials -->
    <section class="py-20 bg-white dark:bg-hcp-secondary-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-hcp-secondary-900 dark:text-white mb-4">
                    O que nossos colaboradores dizem
                </h2>
                <p class="text-lg text-hcp-secondary-600 dark:text-hcp-secondary-300 max-w-2xl mx-auto">
                    Depoimentos reais de quem já viveu a transformação
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-hcp-gradient rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">AM</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-hcp-secondary-900 dark:text-white">Ana Maria Silva</h4>
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Analista de RH</p>
                        </div>
                    </div>
                    <p class="text-hcp-secondary-700 dark:text-hcp-secondary-300 italic">
                        "O sistema revolucionou nosso processo de onboarding. Os vídeos interativos e a gamificação tornaram o treinamento muito mais engajante."
                    </p>
                    <div class="flex text-yellow-400 mt-4">
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-hcp-success-gradient rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">CP</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-hcp-secondary-900 dark:text-white">Carlos Pereira</h4>
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Desenvolvedor Full Stack</p>
                        </div>
                    </div>
                    <p class="text-hcp-secondary-700 dark:text-hcp-secondary-300 italic">
                        "Acompanhar meu progresso em tempo real e receber feedback personalizado fez toda a diferença. Recomendo para todos!"
                    </p>
                    <div class="flex text-yellow-400 mt-4">
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-hcp-accent-gradient rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">LS</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-hcp-secondary-900 dark:text-white">Lúcia Santos</h4>
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Gerente de Operações</p>
                        </div>
                    </div>
                    <p class="text-hcp-secondary-700 dark:text-hcp-secondary-300 italic">
                        "Como gerente, posso acompanhar o progresso da minha equipe de forma eficiente. O sistema é intuitivo e completo."
                    </p>
                    <div class="flex text-yellow-400 mt-4">
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                        <x-icon name="star" size="sm" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section Melhorado -->
    <section class="cta-section">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                Comece sua jornada hoje mesmo!
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Junte-se a mais de 500 colaboradores que já transformaram sua carreira com nosso sistema de onboarding interativo
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('login') }}" 
                   class="btn-white px-8 py-4 text-lg font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
                   aria-label="Acessar sistema de login">
                    <x-icon name="arrow-right" size="sm" class="mr-2" />
                    Começar Agora
                </a>
                <button type="button" 
                        onclick="openRequestAccessModal()"
                        class="btn-outline-white px-8 py-4 text-lg font-semibold rounded-xl"
                        aria-label="Solicitar acesso ao sistema">
                    <x-icon name="user" size="sm" class="mr-2" />
                    Ver Demo
                </button>
            </div>
            <p class="text-white/70 mt-6 text-sm">
                ⚡ Acesso instantâneo • 🎯 Resultados comprovados • 💎 Suporte premium
            </p>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-hcp-secondary-50 dark:bg-hcp-secondary-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Links institucionais -->
                <div class="flex flex-wrap justify-center md:justify-start gap-6 mb-6 md:mb-0">
                    <a href="#" class="text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors">
                        Termos de Uso
                    </a>
                    <a href="#" class="text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors">
                        Política de Privacidade
                    </a>
                    <a href="#" class="text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors">
                        Contato
                    </a>
                </div>
                
                <!-- Copyright -->
                <div class="text-center md:text-right">
                    <p class="text-hcp-secondary-500 dark:text-hcp-secondary-400 text-sm">
                        © {{ date('Y') }} Hemera Capital Partners. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Modal de Solicitação de Acesso -->
    <div id="requestAccessModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-20 backdrop-blur-md">
        <div class="bg-white/90 dark:bg-hcp-secondary-900/90 backdrop-blur-xl rounded-2xl p-8 m-4 max-w-md w-full animate-scale-in border border-white/20 dark:border-hcp-secondary-700/20 shadow-2xl">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-hcp-gradient rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <x-icon name="user" size="lg" class="text-white" />
                </div>
                <h3 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                    Solicitar Acesso
                </h3>
                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-300">
                    Preencha os dados abaixo para solicitar acesso ao sistema.
                </p>
            </div>
            
            <form id="requestAccessForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        Nome Completo
                    </label>
                    <input type="text" name="name" required
                           class="input-hcp w-full bg-white/80 dark:bg-hcp-secondary-700/80 text-hcp-secondary-900 dark:text-white backdrop-blur-sm border border-white/30 dark:border-hcp-secondary-600/30">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        E-mail Corporativo
                    </label>
                    <input type="email" name="email" required
                           class="input-hcp w-full bg-white/80 dark:bg-hcp-secondary-700/80 text-hcp-secondary-900 dark:text-white backdrop-blur-sm border border-white/30 dark:border-hcp-secondary-600/30">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-2">
                        Departamento
                    </label>
                    <select name="department" required
                            class="input-hcp w-full bg-white/80 dark:bg-hcp-secondary-700/80 text-hcp-secondary-900 dark:text-white backdrop-blur-sm border border-white/30 dark:border-hcp-secondary-600/30">
                        <option value="">Selecione...</option>
                        <option value="rh">Recursos Humanos</option>
                        <option value="ti">Tecnologia da Informação</option>
                        <option value="financeiro">Financeiro</option>
                        <option value="comercial">Comercial</option>
                        <option value="operacoes">Operações</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeRequestAccessModal()"
                            class="btn-hcp btn-hcp-secondary flex-1 bg-white/80 dark:bg-hcp-secondary-700/80 backdrop-blur-sm border border-white/30 dark:border-hcp-secondary-600/30">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn-hcp btn-hcp-primary flex-1">
                        Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bottom Navigation (Mobile) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 dark:bg-hcp-secondary-900/95 backdrop-blur-lg border-t border-hcp-secondary-200 dark:border-hcp-secondary-700 z-50 md:hidden">
        <div class="flex items-center justify-around py-2 px-4">
            <button onclick="scrollToTop()" class="flex flex-col items-center py-2 px-3 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500 transition-colors">
                <x-icon name="home" size="sm" />
                <span class="text-xs mt-1">Início</span>
            </button>
            <button onclick="scrollToFeatures()" class="flex flex-col items-center py-2 px-3 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500 transition-colors">
                <x-icon name="star" size="sm" />
                <span class="text-xs mt-1">Recursos</span>
            </button>
            <button onclick="openRequestAccessModal()" class="flex flex-col items-center py-2 px-3 text-hcp-primary-500 hover:text-hcp-primary-600 transition-colors">
                <x-icon name="user-plus" size="sm" />
                <span class="text-xs mt-1">Acesso</span>
            </button>
            <button onclick="scrollToFooter()" class="flex flex-col items-center py-2 px-3 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500 transition-colors">
                <x-icon name="information-circle" size="sm" />
                <span class="text-xs mt-1">Contato</span>
            </button>
        </div>
    </nav>
    
    <!-- Mobile Navigation -->
    <x-mobile-nav />
    
    <!-- Toast Notifications -->
    <x-toast-notification />
    
    <!-- Splash Screen -->
    <x-splash-screen />
    
    <!-- Scripts -->
    <script>
        // Analytics e tracking de eventos
        function trackEvent(eventName, properties = {}) {
            // Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, {
                    event_category: 'welcome_page',
                    event_label: properties.button_text || properties.section || 'general',
                    ...properties
                });
            }
            
            // Console log para debugging
            console.log('Event tracked:', eventName, properties);
        }
        
        // Lazy loading de imagens
        function initLazyLoading() {
            const images = document.querySelectorAll('img[loading="lazy"]');
            
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('skeleton');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                images.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback para navegadores antigos
                images.forEach(img => {
                    img.src = img.dataset.src;
                    img.classList.remove('skeleton');
                });
            }
        }
        
        // Otimização de performance para scroll
        let ticking = false;
        let lastScrollY = 0;
        
        function updateScrollPosition() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.parallax-bg');
            const speed = scrolled * 0.3;
            
            if (parallax) {
                parallax.style.transform = `translate3d(0, ${speed}px, 0)`;
            }
            
            // Atualizar indicador de navegação inferior
            const bottomNav = document.querySelector('nav');
            if (bottomNav) {
                if (scrolled > 100) {
                    bottomNav.style.background = 'rgba(255, 255, 255, 0.98)';
                    bottomNav.style.backdropFilter = 'blur(20px)';
                } else {
                    bottomNav.style.background = 'rgba(255, 255, 255, 0.95)';
                    bottomNav.style.backdropFilter = 'blur(10px)';
                }
            }
            
            // Track scroll depth
            if (scrolled > lastScrollY + 100) {
                trackEvent('scroll_depth', { depth: Math.round(scrolled / 100) * 100 });
                lastScrollY = scrolled;
            }
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateScrollPosition);
                ticking = true;
            }
        }
        
        // Funções para modal de solicitação de acesso
        function openRequestAccessModal() {
            document.getElementById('requestAccessModal').classList.remove('hidden');
            document.getElementById('requestAccessModal').classList.add('flex');
            document.body.classList.add('overflow-hidden');
            
            // Track modal open
            trackEvent('modal_open', { modal_type: 'request_access' });
            
            // Focus management para acessibilidade
            setTimeout(() => {
                const firstInput = document.querySelector('#requestAccessModal input');
                if (firstInput) firstInput.focus();
            }, 100);
        }
        
        function closeRequestAccessModal() {
            document.getElementById('requestAccessModal').classList.add('hidden');
            document.getElementById('requestAccessModal').classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            
            // Track modal close
            trackEvent('modal_close', { modal_type: 'request_access' });
        }
        
        // Fechar modal ao clicar fora
        document.getElementById('requestAccessModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRequestAccessModal();
            }
        });
        
        // Submissão do formulário de solicitação
        document.getElementById('requestAccessForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Track form submission
            trackEvent('form_submit', { 
                form_type: 'request_access',
                department: data.department 
            });
            
            // Simular envio (implementar integração real depois)
            showToast('success', 'Solicitação enviada com sucesso! Entraremos em contato em breve.');
            closeRequestAccessModal();
            this.reset();
        });
        
        // Efeito ripple para botões (experiência nativa)
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            
            const rect = button.getBoundingClientRect();
            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - rect.left - radius}px`;
            circle.style.top = `${event.clientY - rect.top - radius}px`;
            circle.classList.add('ripple');
            
            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }
            
            button.appendChild(circle);
        }
        
        // Adicionar ripple effect a todos os botões
        document.querySelectorAll('button, .btn-hcp').forEach(button => {
            button.addEventListener('click', createRipple);
            
            // Track button clicks
            button.addEventListener('click', function() {
                const buttonText = this.textContent.trim();
                trackEvent('button_click', { 
                    button_text: buttonText,
                    button_type: this.classList.contains('btn-hcp-primary') ? 'primary' : 'secondary'
                });
            });
        });
        
        // Animações ao scroll com Intersection Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    
                    // Track section visibility
                    const sectionName = entry.target.dataset.section || 'unknown';
                    trackEvent('section_view', { section: sectionName });
                }
            });
        }, observerOptions);
        
        // Observar elementos para animação
        document.querySelectorAll('.card-hcp, .testimonial-card, section').forEach(element => {
            observer.observe(element);
        });
        
        // Funções de navegação suave para bottom navigation
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            trackEvent('navigation', { action: 'scroll_to_top' });
        }
        
        function scrollToFeatures() {
            const featuresSection = document.querySelector('section');
            if (featuresSection) {
                featuresSection.scrollIntoView({ behavior: 'smooth' });
                trackEvent('navigation', { action: 'scroll_to_features' });
            }
        }
        
        function scrollToFooter() {
            const footer = document.querySelector('footer');
            if (footer) {
                footer.scrollIntoView({ behavior: 'smooth' });
                trackEvent('navigation', { action: 'scroll_to_footer' });
            } else {
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }
        }
        
        // Haptic feedback para dispositivos móveis
        function hapticFeedback() {
            if ('vibrate' in navigator) {
                navigator.vibrate(50);
            }
        }
        
        // Suporte a gestos de swipe para navegação
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;
        
        function handleTouchStart(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }
        
        function handleTouchEnd(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }
        
        function handleSwipe() {
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;
            const minSwipeDistance = 50;
            
            // Verificar se é um swipe horizontal (não vertical)
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
                if (deltaX > 0) {
                    // Swipe para direita - voltar ao topo
                    scrollToTop();
                    hapticFeedback();
                } else {
                    // Swipe para esquerda - ir para recursos
                    scrollToFeatures();
                    hapticFeedback();
                }
            }
            
            // Swipe vertical para baixo - mostrar recursos
            if (deltaY > minSwipeDistance && Math.abs(deltaY) > Math.abs(deltaX)) {
                scrollToFeatures();
                hapticFeedback();
            }
        }
        
        // Adicionar listeners de touch apenas em dispositivos móveis
        if ('ontouchstart' in window) {
            document.addEventListener('touchstart', handleTouchStart, { passive: true });
            document.addEventListener('touchend', handleTouchEnd, { passive: true });
        }
        
        // Melhorar a navegação com teclado para acessibilidade
        document.addEventListener('keydown', function(e) {
            // Atalhos de teclado
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'Home':
                    case 'h':
                        e.preventDefault();
                        scrollToTop();
                        break;
                    case 'r':
                        e.preventDefault();
                        scrollToFeatures();
                        break;
                    case 'l':
                        e.preventDefault();
                        window.location.href = '/login';
                        break;
                }
            }
            
            // Escape para fechar modais
            if (e.key === 'Escape') {
                closeRequestAccessModal();
            }
        });
        
        // Service Worker para PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registrado com sucesso:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('Falha ao registrar SW:', error);
                    });
            });
        }
        
        // Detectar se o app foi instalado como PWA
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            
            // Mostrar botão de instalação personalizado (opcional)
            const installButton = document.getElementById('installButton');
            if (installButton) {
                installButton.style.display = 'block';
                installButton.addEventListener('click', function() {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(function(choiceResult) {
                        if (choiceResult.outcome === 'accepted') {
                            showToast('success', 'App instalado com sucesso!');
                            trackEvent('pwa_install', { outcome: 'accepted' });
                        }
                        deferredPrompt = null;
                        installButton.style.display = 'none';
                    });
                });
            }
        });
        
        // Detectar quando o app foi instalado
        window.addEventListener('appinstalled', function() {
            showToast('success', 'HCP Onboarding instalado com sucesso!');
            hapticFeedback();
            trackEvent('pwa_installed');
        });
        
        // Função para mostrar toasts (melhorada)
        function showToast(type, message, duration = 4000) {
            // Remove toast anterior se existir
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            const toast = document.createElement('div');
            toast.className = `toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 max-w-sm`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'polite');
            
            // Estilos baseados no tipo
            switch(type) {
                case 'success':
                    toast.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500', 'text-white');
                    break;
                case 'info':
                    toast.classList.add('bg-blue-500', 'text-white');
                    break;
                default:
                    toast.classList.add('bg-gray-500', 'text-white');
            }
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="ml-2 text-white/70 hover:text-white"
                            aria-label="Fechar notificação">
                        ×
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animar entrada
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Auto-remover
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, duration);
        }
        
        // Inicialização quando DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar lazy loading
            initLazyLoading();
            
            // Inicializar vídeo de background
            initHeroVideo();
            
            // Track page view
            trackEvent('page_view', { 
                page_title: document.title,
                page_url: window.location.href 
            });
            
            // Adicionar scroll listener otimizado
            window.addEventListener('scroll', requestTick, { passive: true });
            
            // Preload critical resources
            const criticalImages = [
                '{{ asset("images/hcp-building.jpg") }}',
                '{{ asset("images/hcp-logo.png") }}'
            ];
            
            criticalImages.forEach(src => {
                const img = new Image();
                img.src = src;
            });
        });
        
        // Função para inicializar o vídeo do YouTube de background
        function initHeroVideo() {
            // Carregar YouTube API
            if (!window.YT) {
                const tag = document.createElement('script');
                tag.src = 'https://www.youtube.com/iframe_api';
                const firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            }
            
            // Extrair ID do vídeo do YouTube
            const videoId = 'yD0Klnia320';
            const startTime = 16; // Começar no segundo 16
            
            // Função chamada quando YouTube API está pronta
            window.onYouTubeIframeAPIReady = function() {
                new YT.Player('youtube-player', {
                    height: '100%',
                    width: '100%',
                    videoId: videoId,
                    playerVars: {
                        'autoplay': 1,
                        'controls': 0,
                        'disablekb': 1,
                        'enablejsapi': 1,
                        'fs': 0,
                        'iv_load_policy': 3,
                        'loop': 1,
                        'modestbranding': 1,
                        'playsinline': 1,
                        'rel': 0,
                        'showinfo': 0,
                        'start': startTime,
                        'mute': 1,
                        'playlist': videoId // Necessário para loop
                    },
                    events: {
                        'onReady': function(event) {
                            console.log('YouTube video loaded successfully');
                            event.target.playVideo();
                            
                            // Track video load
                            if (typeof trackEvent === 'function') {
                                trackEvent('youtube_video_loaded', { 
                                    video_id: videoId,
                                    start_time: startTime
                                });
                            }
                        },
                        'onStateChange': function(event) {
                            // Reiniciar vídeo quando terminar para manter loop
                            if (event.data === YT.PlayerState.ENDED) {
                                event.target.seekTo(startTime);
                                event.target.playVideo();
                            }
                        },
                        'onError': function(event) {
                            console.log('YouTube video failed to load, using fallback');
                            // Mostrar fallback
                            const fallback = document.getElementById('video-fallback');
                            if (fallback) {
                                fallback.style.display = 'block';
                            }
                        }
                    }
                });
            };
            
            // Pausar vídeo quando página não está visível (economia de bateria)
            document.addEventListener('visibilitychange', function() {
                const player = document.querySelector('#youtube-player iframe');
                if (player && player.contentWindow) {
                    if (document.hidden) {
                        player.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                    } else {
                        player.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                    }
                }
            });
            
            // Otimização para mobile - pausar vídeo em conexões lentas
            if ('connection' in navigator) {
                if (navigator.connection.effectiveType === 'slow-2g' || 
                    navigator.connection.effectiveType === '2g') {
                    console.log('Slow connection detected, using fallback');
                    const fallback = document.getElementById('video-fallback');
                    if (fallback) {
                        fallback.style.display = 'block';
                    }
                }
            }
        }
        
        // Performance monitoring
        window.addEventListener('load', function() {
            // Track Core Web Vitals
            if ('PerformanceObserver' in window) {
                const observer = new PerformanceObserver((list) => {
                    for (const entry of list.getEntries()) {
                        if (entry.entryType === 'largest-contentful-paint') {
                            trackEvent('web_vital', { 
                                metric: 'LCP', 
                                value: Math.round(entry.startTime) 
                            });
                        }
                    }
                });
                
                observer.observe({ entryTypes: ['largest-contentful-paint'] });
            }
        });
    </script>
</body>
</html>