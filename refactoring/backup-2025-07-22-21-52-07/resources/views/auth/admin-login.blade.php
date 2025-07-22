<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#DC2626">
    
    <title>Admin Login - HCP Onboarding</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --admin-primary: #DC2626;
            --admin-primary-dark: #B91C1C;
            --admin-secondary: #1F2937;
            --admin-accent: #F59E0B;
            --admin-bg: #0F172A;
            --admin-card: #1E293B;
            --admin-border: #334155;
            --admin-text: #F8FAFC;
            --admin-text-muted: #94A3B8;
            --admin-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: var(--admin-bg);
            color: var(--admin-text);
            overflow-x: hidden;
        }

        .admin-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        /* Background Animation */
        .admin-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(220, 38, 38, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
            animation: backgroundShift 20s ease-in-out infinite;
        }

        @keyframes backgroundShift {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(1deg); }
        }

        .admin-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            box-shadow: var(--admin-shadow);
            position: relative;
            z-index: 10;
            backdrop-filter: blur(20px);
        }

        .admin-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--admin-primary), var(--admin-accent), var(--admin-primary));
            border-radius: 24px 24px 0 0;
        }

        .admin-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .admin-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-weight: 800;
            font-size: 32px;
            color: white;
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
            position: relative;
            overflow: hidden;
        }

        .admin-logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: logoShine 3s ease-in-out infinite;
        }

        @keyframes logoShine {
            0%, 100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .admin-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--admin-text);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--admin-text), var(--admin-text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-subtitle {
            font-size: 16px;
            color: var(--admin-text-muted);
            font-weight: 500;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.2);
            color: #FCA5A5;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--admin-text);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            background: var(--admin-bg);
            border: 2px solid var(--admin-border);
            border-radius: 12px;
            color: var(--admin-text);
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: var(--admin-text-muted);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            background: rgba(15, 23, 42, 0.8);
        }

        .form-input.error {
            border-color: #EF4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            font-size: 13px;
            color: #FCA5A5;
            margin-top: 0.75rem;
            font-weight: 500;
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
            gap: 0.75rem;
        }

        .admin-checkbox {
            width: 18px;
            height: 18px;
            background: var(--admin-bg);
            border: 2px solid var(--admin-border);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .admin-checkbox:checked {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .admin-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--admin-text-muted);
            font-weight: 500;
        }

        .forgot-link {
            font-size: 14px;
            color: var(--admin-accent);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #FBBF24;
        }

        .admin-btn {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(220, 38, 38, 0.4);
        }

        .admin-btn:active {
            transform: translateY(0);
        }

        .admin-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .admin-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .admin-btn:hover::before {
            left: 100%;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #86EFAC;
            padding: 1rem;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--admin-border);
        }

        .admin-footer-text {
            font-size: 13px;
            color: var(--admin-text-muted);
            margin-bottom: 1rem;
        }

        .admin-footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .admin-footer-link {
            font-size: 13px;
            color: var(--admin-text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .admin-footer-link:hover {
            color: var(--admin-accent);
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .admin-container {
                padding: 1rem;
            }

            .admin-card {
                padding: 2rem;
                border-radius: 16px;
            }

            .admin-title {
                font-size: 24px;
            }

            .form-input, .admin-btn {
                padding: 14px 18px;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="h-full">
    <div class="admin-container">
        <div class="admin-card fade-in">
            <!-- Header -->
            <div class="admin-header">
                <div class="admin-logo">
                    🛡️
                </div>
                <h1 class="admin-title">Painel Administrativo</h1>
                <p class="admin-subtitle">Acesso restrito para administradores</p>
                
                <div class="security-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                    Área Segura
                </div>
            </div>

            <!-- Mensagens -->
            @if(session('success'))
                <div class="success-message slide-up">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulário -->
            <form id="adminLoginForm" method="POST" action="{{ route('admin.login') }}">
                @csrf
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email do Administrador</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required
                        value="{{ old('email') }}"
                        placeholder="admin@hcp.com"
                        class="form-input @error('email') error @enderror"
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Senha -->
                <div class="form-group">
                    <label for="password" class="form-label">Senha de Administrador</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required
                        placeholder="Digite sua senha de administrador"
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
                            class="admin-checkbox"
                        >
                        <label for="remember" class="checkbox-label">Manter sessão ativa</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Recuperar acesso
                    </a>
                </div>

                <!-- Botão de Login -->
                <button type="submit" id="adminLoginButton" class="admin-btn">
                    <span id="adminLoginText">Acessar Painel</span>
                    <span id="adminLoginLoading" style="display: none;">
                        <div class="loading-spinner"></div>
                        Verificando...
                    </span>
                </button>
            </form>

            <!-- Footer -->
            <div class="admin-footer">
                <p class="admin-footer-text">
                    Sistema de Onboarding HCP - Painel Administrativo
                </p>
                <div class="admin-footer-links">
                    <a href="{{ route('login') }}" class="admin-footer-link">Login Funcionário</a>
                    <a href="#" class="admin-footer-link">Suporte Técnico</a>
                    <a href="#" class="admin-footer-link">Documentação</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form submission with loading state
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('adminLoginButton');
            const buttonText = document.getElementById('adminLoginText');
            const buttonLoading = document.getElementById('adminLoginLoading');
            
            button.disabled = true;
            buttonText.style.display = 'none';
            buttonLoading.style.display = 'inline-flex';
            buttonLoading.style.alignItems = 'center';
            buttonLoading.style.gap = '0.5rem';
        });

        // Real-time validation
        document.getElementById('email').addEventListener('input', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                e.target.classList.add('error');
            } else {
                e.target.classList.remove('error');
            }
        });

        // Focus management
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Enhanced security feedback
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            
            // Visual feedback for strong passwords
            if (password.length >= 8) {
                e.target.style.borderColor = 'var(--admin-primary)';
            } else {
                e.target.style.borderColor = 'var(--admin-border)';
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + A for admin quick access
            if (e.altKey && e.key === 'a') {
                e.preventDefault();
                document.getElementById('email').focus();
            }
        });

        // Enhanced animations
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.form-group');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease-out';
                    el.style.opacity = '1';
                    el.style.transform = 'translateX(0)';
                }, index * 100 + 300);
            });
        });
    </script>
</body>
</html>