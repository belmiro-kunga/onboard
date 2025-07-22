<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0ea5e9">
    
    <title>Recuperar Senha - Sistema HCP</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos específicos -->
    <style>
        .recovery-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            position: relative;
            overflow: hidden;
        }
        
        .recovery-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInUp 0.8s ease-out;
        }
        
        [data-theme="dark"] .recovery-card {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.2);
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
        
        .input-group {
            position: relative;
        }
        
        .input-floating-label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            padding: 0 4px;
            color: #6b7280;
            transition: all 0.2s ease-in-out;
            pointer-events: none;
        }
        
        .input-floating:focus + .input-floating-label,
        .input-floating:not(:placeholder-shown) + .input-floating-label {
            top: 0;
            font-size: 0.75rem;
            color: #0ea5e9;
            background: var(--hcp-bg-primary);
        }
        
        .btn-recovery {
            position: relative;
            overflow: hidden;
        }
        
        .btn-recovery::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-recovery:hover::before {
            left: 100%;
        }
        
        .success-animation {
            animation: successBounce 0.6s ease-out;
        }
        
        @keyframes successBounce {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="h-full">
    <!-- Background com gradiente -->
    <div class="recovery-bg min-h-screen flex items-center justify-center relative">
        <!-- Toggle de tema no canto superior direito -->
        <div class="absolute top-6 right-6 z-10">
            <x-theme-toggle size="md" />
        </div>
        
        <!-- Conteúdo principal -->
        <div class="relative z-10 w-full max-w-md mx-auto px-4 sm:px-6">
            <!-- Header com animação -->
            <div class="text-center mb-8 animate-fade-in-down">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center shadow-hcp-xl">
                        <span class="text-hcp-500 font-bold text-2xl">HCP</span>
                    </div>
                </div>
                
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">
                    Recuperar Senha
                </h1>
                <p class="text-white/80 text-sm">
                    Digite seu e-mail corporativo para receber as instruções de recuperação
                </p>
            </div>

            <!-- Card de recuperação -->
            <div class="recovery-card rounded-3xl p-8 shadow-hcp-glass">
                <!-- Status da solicitação -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-hcp-success-50 dark:bg-hcp-success-900/20 border border-hcp-success-200 dark:border-hcp-success-800 rounded-hcp-lg success-animation">
                        <div class="flex items-center">
                            <x-icon name="check" class="w-5 h-5 text-hcp-success-500 mr-3" />
                            <div>
                                <p class="text-sm font-medium text-hcp-success-700 dark:text-hcp-success-300">
                                    E-mail enviado com sucesso!
                                </p>
                                <p class="text-xs text-hcp-success-600 dark:text-hcp-success-400 mt-1">
                                    {{ session('status') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Formulário de recuperação -->
                <form id="recoveryForm" method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <!-- E-mail com floating label -->
                    <div class="input-group">
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               value="{{ old('email') }}"
                               placeholder=" "
                               class="input-floating input-hcp w-full pt-6 @error('email') border-hcp-error-500 @enderror">
                        <label for="email" class="input-floating-label">
                            <x-icon name="user" size="sm" class="inline mr-1" />
                            E-mail corporativo
                        </label>
                        @error('email')
                            <p class="mt-2 text-sm text-hcp-error-500 animate-fade-in-up">
                                <x-icon name="x" size="sm" class="inline mr-1" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botão de envio -->
                    <div>
                        <button type="submit" 
                                id="recoveryButton"
                                class="btn-recovery w-full bg-hcp-gradient hover:shadow-hcp-lg text-white font-semibold py-4 px-6 rounded-hcp-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-hcp-500/30">
                            <span id="recoveryButtonText" class="flex items-center justify-center">
                                <x-icon name="arrow-right" size="sm" class="mr-2" />
                                Enviar Link de Recuperação
                            </span>
                            <span id="recoveryButtonLoading" class="hidden flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Enviando...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-8 pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-600">
                    <div class="text-center">
                        <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            Lembrou da senha? 
                            <a href="{{ route('login') }}" class="font-medium text-hcp-500 hover:text-hcp-400 transition-colors hover:underline">
                                Voltar ao login
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informações adicionais -->
            <div class="mt-6 text-center">
                <p class="text-white/60 text-xs">
                    Não recebeu o e-mail? Verifique sua caixa de spam ou 
                    <a href="#" class="text-white hover:text-white/80 underline">
                        entre em contato com o suporte
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts específicos da página -->
    <script>
        // Submissão do formulário com loading state
        document.getElementById('recoveryForm').addEventListener('submit', function(e) {
            const button = document.getElementById('recoveryButton');
            const buttonText = document.getElementById('recoveryButtonText');
            const buttonLoading = document.getElementById('recoveryButtonLoading');
            
            // Mostrar loading
            button.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');
            
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
                e.target.classList.add('border-hcp-warning-500');
                e.target.classList.remove('border-hcp-success-500');
            } else if (email && emailRegex.test(email)) {
                e.target.classList.add('border-hcp-success-500');
                e.target.classList.remove('border-hcp-warning-500');
            } else {
                e.target.classList.remove('border-hcp-warning-500', 'border-hcp-success-500');
            }
        });
        
        // Inicializar quando DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            // Focar no campo de e-mail
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>