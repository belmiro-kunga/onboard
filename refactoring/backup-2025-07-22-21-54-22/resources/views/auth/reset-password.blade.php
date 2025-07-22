<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0ea5e9">
    
    <title>Redefinir Senha - Sistema HCP</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos específicos -->
    <style>
        .reset-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            position: relative;
            overflow: hidden;
        }
        
        .reset-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInUp 0.8s ease-out;
        }
        
        [data-theme="dark"] .reset-card {
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
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s ease-in-out;
        }
        
        .password-toggle:hover {
            color: #0ea5e9;
        }
        
        .password-strength {
            margin-top: 8px;
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background-color: #ef4444; width: 25%; }
        .strength-fair { background-color: #f59e0b; width: 50%; }
        .strength-good { background-color: #10b981; width: 75%; }
        .strength-strong { background-color: #059669; width: 100%; }
        
        .btn-reset {
            position: relative;
            overflow: hidden;
        }
        
        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-reset:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="h-full">
    <!-- Background com gradiente -->
    <div class="reset-bg min-h-screen flex items-center justify-center relative">
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
                    Redefinir Senha
                </h1>
                <p class="text-white/80 text-sm">
                    Digite sua nova senha para acessar sua conta
                </p>
            </div>

            <!-- Card de redefinição -->
            <div class="reset-card rounded-3xl p-8 shadow-hcp-glass">
                <!-- Formulário de redefinição -->
                <form id="resetForm" method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Token oculto -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <!-- E-mail (readonly) -->
                    <div class="input-group">
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               readonly
                               value="{{ $email ?? old('email') }}"
                               placeholder=" "
                               class="input-floating input-hcp w-full pt-6 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 @error('email') border-hcp-error-500 @enderror">
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

                    <!-- Nova senha -->
                    <div class="input-group">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               placeholder=" "
                               class="input-floating input-hcp w-full pt-6 pr-12 @error('password') border-hcp-error-500 @enderror">
                        <label for="password" class="input-floating-label">
                            <x-icon name="eye-off" size="sm" class="inline mr-1" />
                            Nova senha
                        </label>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'passwordIcon')">
                            <x-icon id="passwordIcon" name="eye" size="sm" />
                        </button>
                        @error('password')
                            <p class="mt-2 text-sm text-hcp-error-500 animate-fade-in-up">
                                <x-icon name="x" size="sm" class="inline mr-1" />
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Indicador de força da senha -->
                        <div id="passwordStrength" class="password-strength hidden">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Força da senha:</span>
                                <span id="strengthText" class="text-xs font-medium"></span>
                            </div>
                            <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-1">
                                <div id="strengthBar" class="strength-bar"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmar senha -->
                    <div class="input-group">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               placeholder=" "
                               class="input-floating input-hcp w-full pt-6 pr-12 @error('password_confirmation') border-hcp-error-500 @enderror">
                        <label for="password_confirmation" class="input-floating-label">
                            <x-icon name="eye-off" size="sm" class="inline mr-1" />
                            Confirmar nova senha
                        </label>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')">
                            <x-icon id="confirmPasswordIcon" name="eye" size="sm" />
                        </button>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-hcp-error-500 animate-fade-in-up">
                                <x-icon name="x" size="sm" class="inline mr-1" />
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Indicador de correspondência -->
                        <div id="passwordMatch" class="mt-2 text-xs hidden">
                            <span id="matchText"></span>
                        </div>
                    </div>

                    <!-- Requisitos da senha -->
                    <div class="bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-hcp-lg p-4">
                        <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white mb-2">
                            Requisitos da senha:
                        </h4>
                        <ul class="space-y-1 text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            <li id="req-length" class="flex items-center">
                                <x-icon name="x" size="sm" class="mr-2 text-hcp-error-500" />
                                Pelo menos 8 caracteres
                            </li>
                            <li id="req-uppercase" class="flex items-center">
                                <x-icon name="x" size="sm" class="mr-2 text-hcp-error-500" />
                                Uma letra maiúscula
                            </li>
                            <li id="req-lowercase" class="flex items-center">
                                <x-icon name="x" size="sm" class="mr-2 text-hcp-error-500" />
                                Uma letra minúscula
                            </li>
                            <li id="req-number" class="flex items-center">
                                <x-icon name="x" size="sm" class="mr-2 text-hcp-error-500" />
                                Um número
                            </li>
                            <li id="req-special" class="flex items-center">
                                <x-icon name="x" size="sm" class="mr-2 text-hcp-error-500" />
                                Um caractere especial
                            </li>
                        </ul>
                    </div>

                    <!-- Botão de redefinição -->
                    <div>
                        <button type="submit" 
                                id="resetButton"
                                class="btn-reset w-full bg-hcp-gradient hover:shadow-hcp-lg text-white font-semibold py-4 px-6 rounded-hcp-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-hcp-500/30">
                            <span id="resetButtonText" class="flex items-center justify-center">
                                <x-icon name="check" size="sm" class="mr-2" />
                                Redefinir Senha
                            </span>
                            <span id="resetButtonLoading" class="hidden flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Redefinindo...
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
        </div>
    </div>

    <!-- Scripts específicos da página -->
    <script>
        // Toggle de visibilidade da senha
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>';
            } else {
                passwordInput.type = 'password';
                passwordIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
        
        // Verificar força da senha
        function checkPasswordStrength(password) {
            let score = 0;
            let feedback = '';
            
            // Critérios de força
            const criteria = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Atualizar indicadores visuais
            Object.keys(criteria).forEach(key => {
                const element = document.getElementById(`req-${key}`);
                const icon = element.querySelector('svg');
                
                if (criteria[key]) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                    icon.classList.remove('text-hcp-error-500');
                    icon.classList.add('text-hcp-success-500');
                    score++;
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                    icon.classList.remove('text-hcp-success-500');
                    icon.classList.add('text-hcp-error-500');
                }
            });
            
            // Determinar força
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            if (score < 2) {
                strengthBar.className = 'strength-bar strength-weak';
                strengthText.textContent = 'Fraca';
                strengthText.className = 'text-xs font-medium text-hcp-error-500';
            } else if (score < 4) {
                strengthBar.className = 'strength-bar strength-fair';
                strengthText.textContent = 'Razoável';
                strengthText.className = 'text-xs font-medium text-hcp-warning-500';
            } else if (score < 5) {
                strengthBar.className = 'strength-bar strength-good';
                strengthText.textContent = 'Boa';
                strengthText.className = 'text-xs font-medium text-hcp-success-500';
            } else {
                strengthBar.className = 'strength-bar strength-strong';
                strengthText.textContent = 'Forte';
                strengthText.className = 'text-xs font-medium text-hcp-success-600';
            }
            
            return score;
        }
        
        // Verificar correspondência de senhas
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');
            const matchText = document.getElementById('matchText');
            
            if (confirmation.length > 0) {
                matchDiv.classList.remove('hidden');
                
                if (password === confirmation) {
                    matchText.innerHTML = '<span class="text-hcp-success-500">✓ As senhas coincidem</span>';
                } else {
                    matchText.innerHTML = '<span class="text-hcp-error-500">✗ As senhas não coincidem</span>';
                }
            } else {
                matchDiv.classList.add('hidden');
            }
        }
        
        // Submissão do formulário com loading state
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const button = document.getElementById('resetButton');
            const buttonText = document.getElementById('resetButtonText');
            const buttonLoading = document.getElementById('resetButtonLoading');
            
            // Mostrar loading
            button.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');
            
            // Adicionar haptic feedback se disponível
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        });
        
        // Event listeners
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            
            if (password.length > 0) {
                document.getElementById('passwordStrength').classList.remove('hidden');
                checkPasswordStrength(password);
            } else {
                document.getElementById('passwordStrength').classList.add('hidden');
            }
            
            checkPasswordMatch();
        });
        
        document.getElementById('password_confirmation').addEventListener('input', function(e) {
            checkPasswordMatch();
        });
        
        // Inicializar quando DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            // Focar no campo de senha
            document.getElementById('password').focus();
        });
    </script>
</body>
</html>