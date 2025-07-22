<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F4C542">
    
    <title>Login - Crextio</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos específicos da página de login -->
    <style>
        :root {
            --crextio-primary: #F4C542;
            --crextio-primary-dark: #E6B73A;
            --crextio-text-primary: #2E2E2E;
            --crextio-text-secondary: #7E7E7E;
            --crextio-border: #DADADA;
            --crextio-placeholder: #B0B0B0;
            --crextio-bg: #FFFFFF;
            --crextio-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --crextio-shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .form-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .hero-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            top: 2rem;
            right: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            z-index: 10;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .calendar-widget {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .calendar-day {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            background: var(--crextio-bg);
            color: var(--crextio-text-primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.highlighted {
            background: var(--crextio-primary);
            color: white;
        }

        .meeting-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .avatars-group {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
            margin-left: -8px;
        }

        .avatar:first-child {
            margin-left: 0;
        }

        .form-card {
            background: var(--crextio-bg);
            border-radius: 24px;
            padding: 3rem;
            width: 400px;
            box-shadow: var(--crextio-shadow);
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: var(--crextio-primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: 700;
            font-size: 24px;
            color: white;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 600;
            line-height: 32px;
            color: var(--crextio-text-primary);
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            color: var(--crextio-text-secondary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--crextio-text-primary);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid var(--crextio-border);
            border-radius: 12px;
            background: var(--crextio-bg);
            font-size: 16px;
            font-weight: 400;
            color: var(--crextio-text-primary);
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: var(--crextio-placeholder);
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--crextio-primary);
            box-shadow: 0 0 0 3px rgba(244, 197, 66, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .error-message {
            font-size: 12px;
            color: #ef4444;
            margin-top: 0.5rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox {
            width: 16px;
            height: 16px;
            border: 1px solid var(--crextio-border);
            border-radius: 4px;
            cursor: pointer;
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--crextio-text-secondary);
        }

        .forgot-link {
            font-size: 14px;
            color: var(--crextio-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 14px 24px;
            background: var(--crextio-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            background: var(--crextio-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--crextio-shadow-hover);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .session-reminder {
            background: var(--crextio-bg);
            color: var(--crextio-text-primary);
            font-size: 13px;
            padding: 0.75rem 1rem;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .footer-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--crextio-border);
        }

        .footer-link {
            font-size: 14px;
            color: var(--crextio-text-secondary);
            text-decoration: none;
            margin: 0 1rem;
        }

        .footer-link:hover {
            color: var(--crextio-text-primary);
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .hero-section {
                display: none;
            }

            .form-card {
                width: 100%;
                max-width: 400px;
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 0.5rem;
            }

            .form-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .brand-title {
                font-size: 20px;
            }

            .form-input {
                padding: 12px 16px;
            }

            .btn-primary {
                padding: 12px 20px;
            }
        }

        /* Animações */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
</head>
<body class="h-full">
    <div class="login-container">
        <!-- Seção do Formulário -->
        <div class="form-section">
            <div class="form-card">
                <!-- Header da Marca -->
                <div class="brand-header">
                    <div class="brand-logo floating">
                        C
                    </div>
                    <h1 class="brand-title">Bem-vindo de volta</h1>
                    <p class="brand-subtitle">Faça login em sua conta Crextio</p>
                </div>

                <!-- Lembretes de Sessão -->
                <div class="session-reminder">
                    📅 Próxima reunião: Hoje às 14:00 - Onboarding Team
                </div>

                <!-- Mensagens de Sucesso/Erro -->
                @if(session('success'))
                    <div class="session-reminder" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <!-- Formulário de Login -->
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail corporativo</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required
                            value="{{ old('email') }}"
                            placeholder="seu.email@empresa.com"
                            class="form-input @error('email') error @enderror"
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="form-group">
                        <label for="password" class="form-label">Senha</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required
                            placeholder="Digite sua senha"
                            class="form-input @error('password') error @enderror"
                        >
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Opções -->
                    <div class="form-options">
                        <div class="checkbox-group">
                            <input 
                                type="checkbox" 
                                id="remember" 
                                name="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                                class="checkbox"
                            >
                            <label for="remember" class="checkbox-label">Lembrar-me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Esqueceu a senha?
                        </a>
                    </div>

                    <!-- Botão de Login -->
                    <button type="submit" id="loginButton" class="btn-primary">
                        <span id="loginButtonText">Entrar</span>
                        <span id="loginButtonLoading" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Entrando...
                        </span>
                    </button>
                </form>

                <!-- Links do Footer -->
                <div class="footer-links">
                    <a href="{{ route('welcome') }}" class="footer-link">Termos & Condições</a>
                    <a href="#" class="footer-link">Suporte</a>
                </div>
            </div>
        </div>

        <!-- Seção Hero -->
        <div class="hero-section">
            <div class="hero-image">
                <!-- Overlay com Widgets -->
                <div class="hero-overlay">
                    <!-- Widget do Calendário -->
                    <div class="glass-card">
                        <div class="calendar-widget">
                            <div class="calendar-day">15</div>
                            <div class="calendar-day">16</div>
                            <div class="calendar-day highlighted">17</div>
                            <div class="calendar-day">18</div>
                            <div class="calendar-day">19</div>
                        </div>
                    </div>

                    <!-- Widget da Reunião -->
                    <div class="glass-card">
                        <div class="meeting-card">
                            <div class="avatars-group">
                                <div class="avatar">JS</div>
                                <div class="avatar">MA</div>
                                <div class="avatar">+3</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: var(--crextio-text-primary);">
                                    Onboarding Team
                                </div>
                                <div style="font-size: 10px; color: var(--crextio-text-secondary);">
                                    14:00 - 15:00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conteúdo Central -->
                <div style="text-align: center; color: white; z-index: 5;">
                    <h2 style="font-size: 32px; font-weight: 700; margin-bottom: 1rem;">
                        Colaboração Simplificada
                    </h2>
                    <p style="font-size: 18px; opacity: 0.9; max-width: 400px;">
                        Conecte-se com sua equipe e maximize sua produtividade com ferramentas inteligentes
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Submissão do formulário com loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('loginButtonText');
            const buttonLoading = document.getElementById('loginButtonLoading');
            
            // Mostrar loading
            button.disabled = true;
            buttonText.style.display = 'none';
            buttonLoading.style.display = 'inline-flex';
            
            // Adicionar haptic feedback se disponível
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        });

        // Validação em tempo real
        document.getElementById('email').addEventListener('input', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                e.target.classList.add('error');
            } else {
                e.target.classList.remove('error');
            }
        });

        // Focar no primeiro campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.form-card, .hero-image');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    el.style.transition = 'all 0.8s ease-out';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>