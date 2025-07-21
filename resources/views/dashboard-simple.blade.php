<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HCP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Bem-vindo, {{ auth()->user()->name }}! 👋
        </h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card de Progresso -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Progresso do Onboarding</h2>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-blue-500 h-4 rounded-full" style="width: 25%"></div>
                </div>
                <p class="mt-2 text-sm text-gray-600">25% concluído</p>
            </div>
            
            <!-- Card de Pontos -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Pontos</h2>
                <p class="text-3xl font-bold text-green-500">150</p>
                <p class="text-sm text-gray-600">Total de pontos</p>
            </div>
            
            <!-- Card de Nível -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Nível</h2>
                <p class="text-2xl font-bold text-purple-500">Iniciante</p>
                <p class="text-sm text-gray-600">Nível atual</p>
            </div>

            <!-- Card de Notificações -->
            <div class="bg-white rounded-lg shadow p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Notificações</h2>
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('read_at', null)
                            ->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 7h6m0 0v6m0-6l-6 6"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">
                        @if($unreadCount > 0)
                            {{ $unreadCount }} nova{{ $unreadCount > 1 ? 's' : '' }}
                        @else
                            Tudo em dia!
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Próximos Passos -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Próximos Passos</h2>
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        1
                    </div>
                    <div>
                        <h3 class="font-medium">Introdução à HCP</h3>
                        <p class="text-sm text-gray-600">Conheça nossa história e valores</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-gray-50 rounded-lg opacity-50">
                    <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        2
                    </div>
                    <div>
                        <h3 class="font-medium">Políticas e Procedimentos</h3>
                        <p class="text-sm text-gray-600">Entenda nossas diretrizes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Certificações -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Certificações</h2>
                <a href="{{ route('certificates.index') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                    Ver todas →
                </a>
            </div>
            
            @php
                $userCertificates = \App\Models\Certificate::where('user_id', auth()->id())
                    ->with(['quiz'])
                    ->orderBy('issued_at', 'desc')
                    ->limit(3)
                    ->get();
            @endphp

            @if($userCertificates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($userCertificates as $certificate)
                        <div class="border border-green-200 rounded-lg p-4 bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-md transition-all duration-300 hover:scale-105">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3 animate-pulse">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-green-900 text-sm">{{ $certificate->quiz->title }}</h3>
                                    <p class="text-xs text-green-700">{{ $certificate->issued_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-green-600 font-medium">
                                    #{{ $certificate->certificate_number }}
                                </span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                                    {{ $certificate->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Estatísticas de Certificações -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    @php
                        $totalCertificates = $userCertificates->count();
                        $validCertificates = $userCertificates->where('valid_until', '>', now())->count();
                        $thisMonthCertificates = $userCertificates->where('issued_at', '>=', now()->startOfMonth())->count();
                    @endphp
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $totalCertificates }}</p>
                            <p class="text-xs text-gray-600">Total</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $validCertificates }}</p>
                            <p class="text-xs text-gray-600">Válidos</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">{{ $thisMonthCertificates }}</p>
                            <p class="text-xs text-gray-600">Este Mês</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum certificado ainda</h3>
                    <p class="text-sm text-gray-600 mb-4">Complete quizzes para ganhar certificados digitais!</p>
                    <a href="{{ route('quizzes.index') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Começar Quizzes
                    </a>
                </div>
            @endif
        </div>

        <!-- Seção de Quizzes -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Quizzes Disponíveis</h2>
                <a href="{{ route('quizzes.index') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                    Ver todos →
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    // Buscar quizzes disponíveis (limitado a 3 para o dashboard)
                    $featuredQuizzes = \App\Models\Quiz::where('is_active', true)
                        ->with('questions')
                        ->limit(3)
                        ->get();
                @endphp

                @forelse($featuredQuizzes as $quiz)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-2">
                            <span class="bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-100 text-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $quiz->formatted_category }}
                            </span>
                            <span class="bg-{{ $quiz->difficulty_level === 'basic' ? 'green' : ($quiz->difficulty_level === 'intermediate' ? 'yellow' : 'red') }}-100 text-{{ $quiz->difficulty_level === 'basic' ? 'green' : ($quiz->difficulty_level === 'intermediate' ? 'yellow' : 'red') }}-800 text-xs font-medium px-2.5 py-0.5 rounded-full ml-2">
                                {{ $quiz->formatted_difficulty }}
                            </span>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($quiz->description, 50) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                @if($quiz->time_limit)
                                    ⏱️ {{ $quiz->time_limit }} min
                                @else
                                    ⏱️ Sem limite
                                @endif
                            </span>
                            @php
                                $userPassed = isset($quiz->user_passed) ? $quiz->user_passed : false;
                                $canAttempt = isset($quiz->can_attempt) ? $quiz->can_attempt : true;
                            @endphp
                            
                            @if($userPassed)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded font-medium">
                                    ✅ Concluído
                                </span>
                            @elseif($canAttempt)
                                <a href="{{ route('quizzes.start.show', $quiz) }}" 
                                   class="bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-500 text-white text-xs px-3 py-1 rounded hover:bg-{{ $quiz->category === 'hr' ? 'blue' : ($quiz->category === 'it' ? 'purple' : ($quiz->category === 'security' ? 'red' : ($quiz->category === 'culture' ? 'pink' : 'gray'))) }}-600 transition-colors">
                                    Iniciar
                                </a>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded">
                                    Sem tentativas
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">Nenhum quiz disponível no momento.</p>
                        <p class="text-sm text-gray-400 mt-1">Novos quizzes serão adicionados em breve!</p>
                    </div>
                @endforelse
            </div>

            <!-- Estatísticas de Quizzes -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                @php
                    $user = auth()->user();
                    $totalQuizzes = \App\Models\Quiz::where('is_active', true)->count();
                    $completedQuizzes = \App\Models\Quiz::where('is_active', true)
                        ->whereHas('attempts', function($q) use ($user) {
                            $q->where('user_id', $user->id)->where('passed', true);
                        })->count();
                    $bestScore = \App\Models\QuizAttempt::where('user_id', $user->id)
                        ->where('passed', true)
                        ->max('score') ?? 0;
                @endphp
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalQuizzes }}</p>
                        <p class="text-xs text-gray-600">Quizzes Disponíveis</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $completedQuizzes }}</p>
                        <p class="text-xs text-gray-600">Concluídos</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-purple-600">{{ $bestScore }}%</p>
                        <p class="text-xs text-gray-600">Melhor Pontuação</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botão de Logout -->
        <div class="mt-8 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Sair
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts para interações avançadas -->
    <script>
        // Pull-to-refresh functionality
        let startY = 0;
        let currentY = 0;
        let pullDistance = 0;
        let isPulling = false;
        let refreshThreshold = 80;
        
        // Create pull-to-refresh indicator
        const pullIndicator = document.createElement('div');
        pullIndicator.id = 'pull-indicator';
        pullIndicator.innerHTML = `
            <div class="flex items-center justify-center p-4 bg-blue-500 text-white rounded-lg shadow-lg transform -translate-y-full transition-transform duration-300">
                <svg class="w-6 h-6 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Solte para atualizar</span>
            </div>
        `;
        pullIndicator.style.cssText = `
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            opacity: 0;
        `;
        document.body.appendChild(pullIndicator);

        // Touch events for pull-to-refresh
        document.addEventListener('touchstart', function(e) {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                isPulling = true;
            }
        });

        document.addEventListener('touchmove', function(e) {
            if (!isPulling) return;
            
            currentY = e.touches[0].clientY;
            pullDistance = currentY - startY;
            
            if (pullDistance > 0 && window.scrollY === 0) {
                e.preventDefault();
                
                // Show indicator
                pullIndicator.style.opacity = Math.min(pullDistance / refreshThreshold, 1);
                pullIndicator.style.transform = `translateX(-50%) translateY(${Math.min(pullDistance - 50, 0)}px)`;
                
                // Add haptic feedback
                if (pullDistance > refreshThreshold && navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }
        });

        document.addEventListener('touchend', function(e) {
            if (!isPulling) return;
            
            isPulling = false;
            
            if (pullDistance > refreshThreshold) {
                // Trigger refresh
                refreshDashboard();
            }
            
            // Hide indicator
            pullIndicator.style.opacity = '0';
            pullIndicator.style.transform = 'translateX(-50%) translateY(-100%)';
            
            pullDistance = 0;
        });

        // Refresh dashboard function
        function refreshDashboard() {
            // Show loading state
            const indicator = pullIndicator.querySelector('div');
            indicator.innerHTML = `
                <svg class="w-6 h-6 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Atualizando...</span>
            `;
            
            // Simulate refresh (in real app, this would reload data)
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        // Swipe gestures for navigation
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        });

        function handleSwipe() {
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;
            const minSwipeDistance = 50;
            
            // Only process horizontal swipes
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
                if (deltaX > 0) {
                    // Swipe right - go to quizzes
                    showSwipeHint('Quizzes →');
                    setTimeout(() => {
                        window.location.href = '{{ route("quizzes.index") }}';
                    }, 500);
                } else {
                    // Swipe left - go to certificates
                    showSwipeHint('← Certificados');
                    setTimeout(() => {
                        window.location.href = '{{ route("certificates.index") }}';
                    }, 500);
                }
            }
        }

        function showSwipeHint(text) {
            const hint = document.createElement('div');
            hint.innerHTML = text;
            hint.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                font-weight: bold;
                z-index: 1000;
                animation: fadeInOut 1s ease-in-out;
            `;
            
            document.body.appendChild(hint);
            
            setTimeout(() => {
                hint.remove();
            }, 1000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
                50% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            }
            
            .card-hover {
                transition: all 0.3s ease;
            }
            
            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            
            .pulse-animation {
                animation: pulse 2s infinite;
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);

        // Add hover effects to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.bg-white.rounded-lg.shadow');
            cards.forEach(card => {
                card.classList.add('card-hover');
            });
            
            // Add pulse animation to notification badge
            const notificationBadge = document.querySelector('.animate-pulse');
            if (notificationBadge) {
                notificationBadge.classList.add('pulse-animation');
            }
        });

        // Smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Add loading states for buttons
        document.addEventListener('click', function(e) {
            if (e.target.matches('button[type="submit"], a[href*="quiz"]')) {
                const element = e.target;
                const originalText = element.textContent;
                
                element.style.opacity = '0.7';
                element.style.pointerEvents = 'none';
                
                if (element.tagName === 'BUTTON') {
                    element.textContent = 'Carregando...';
                }
                
                // Reset after navigation or form submission
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.pointerEvents = 'auto';
                    element.textContent = originalText;
                }, 2000);
            }
        });
    </script>
</body>
</html>