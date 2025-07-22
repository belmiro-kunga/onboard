<x-layouts.admin title="Dashboard Administrativo">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-red-600 via-red-700 to-red-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Dashboard Administrativo</h1>
                            <p class="text-red-100 mt-1">Bem-vindo, {{ auth()->user()->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                            <span class="text-white font-medium">{{ now()->format('d/m/Y') }}</span>
                        </div>
                        <div class="bg-amber-500/20 border border-amber-400/30 text-amber-200 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                            🛡️ Área Restrita
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Estatísticas Principais -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Usuários -->
                <div class="group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700/50 hover:border-red-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-red-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-red-500/10 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-white">{{ $stats['users_count'] ?? 42 }}</div>
                                <div class="text-sm text-slate-400">Total</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-slate-300 font-medium">Usuários</h3>
                            <div class="flex items-center text-green-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                                +12%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Módulos -->
                <div class="group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-blue-500/10 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-white">{{ $stats['modules_count'] ?? 16 }}</div>
                                <div class="text-sm text-slate-400">Ativos</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-slate-300 font-medium">Módulos</h3>
                            <div class="flex items-center text-green-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                                +3
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificados -->
                <div class="group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700/50 hover:border-green-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-green-500/10 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-white">{{ $stats['certificates_count'] ?? 28 }}</div>
                                <div class="text-sm text-slate-400">Emitidos</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-slate-300 font-medium">Certificados</h3>
                            <div class="flex items-center text-green-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                                +8
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quizzes -->
                <div class="group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700/50 hover:border-purple-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-purple-500/10 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-white">{{ $stats['quizzes_count'] ?? 35 }}</div>
                                <div class="text-sm text-slate-400">Criados</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-slate-300 font-medium">Quizzes</h3>
                            <div class="flex items-center text-green-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                                +5
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Atividade Recente -->
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6 border-b border-slate-700/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Atividade Recente</h3>
                            <button class="text-slate-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach(range(1, 5) as $i)
                                <div class="flex items-center space-x-4 p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 transition-colors">
                                    <div class="flex-shrink-0">
                                        <img class="w-10 h-10 rounded-full ring-2 ring-slate-600" src="https://ui-avatars.com/api/?name=User{{ $i }}&color=FFFFFF&background={{ ['F87171', '60A5FA', '34D399', 'A78BFA', 'FBBF24'][($i-1) % 5] }}" alt="">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-white truncate">Usuário {{ $i }}</p>
                                            <p class="text-xs text-slate-400">{{ now()->subHours($i)->diffForHumans() }}</p>
                                        </div>
                                        <p class="text-sm text-slate-400 truncate">
                                            @if($i % 3 == 0)
                                                <span class="inline-flex items-center">
                                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                                    Completou o módulo "Segurança da Informação"
                                                </span>
                                            @elseif($i % 3 == 1)
                                                <span class="inline-flex items-center">
                                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                                    Obteve certificado em "Políticas de RH"
                                                </span>
                                            @else
                                                <span class="inline-flex items-center">
                                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                                    Iniciou o módulo "Cultura Organizacional"
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <button class="text-red-400 hover:text-red-300 font-medium text-sm transition-colors">
                                Ver todas as atividades →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Usuários Online -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6 border-b border-slate-700/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Usuários Online</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-sm text-green-400 font-medium">{{ rand(8, 15) }} online</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach(range(1, 6) as $i)
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=Active{{ $i }}&color=FFFFFF&background={{ ['F87171', '60A5FA', '34D399', 'A78BFA', 'FBBF24', 'F472B6'][($i-1) % 6] }}" alt="">
                                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 {{ $i % 2 == 0 ? 'bg-green-400' : 'bg-yellow-400' }} border-2 border-slate-800 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white truncate">Usuário Ativo {{ $i }}</p>
                                        <p class="text-xs text-slate-400 truncate">usuario{{ $i }}@hcp.com</p>
                                    </div>
                                    <div class="text-xs {{ $i % 2 == 0 ? 'text-green-400' : 'text-yellow-400' }}">
                                        {{ $i % 2 == 0 ? 'Online' : 'Ausente' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <button class="text-red-400 hover:text-red-300 font-medium text-sm transition-colors">
                                Ver todos os usuários →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                <h3 class="text-xl font-bold text-white mb-6">Ações Rápidas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button class="group relative bg-gradient-to-br from-red-500/10 to-red-600/10 hover:from-red-500/20 hover:to-red-600/20 border border-red-500/20 hover:border-red-500/40 rounded-xl p-6 text-left transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-red-500/20 p-2 rounded-lg group-hover:bg-red-500/30 transition-colors">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Novo Usuário</h4>
                        </div>
                        <p class="text-sm text-slate-400">Adicionar funcionário ao sistema</p>
                    </button>

                    <button class="group relative bg-gradient-to-br from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 border border-blue-500/20 hover:border-blue-500/40 rounded-xl p-6 text-left transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-blue-500/20 p-2 rounded-lg group-hover:bg-blue-500/30 transition-colors">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Novo Módulo</h4>
                        </div>
                        <p class="text-sm text-slate-400">Criar conteúdo de aprendizado</p>
                    </button>

                    <button class="group relative bg-gradient-to-br from-purple-500/10 to-purple-600/10 hover:from-purple-500/20 hover:to-purple-600/20 border border-purple-500/20 hover:border-purple-500/40 rounded-xl p-6 text-left transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-purple-500/20 p-2 rounded-lg group-hover:bg-purple-500/30 transition-colors">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Novo Quiz</h4>
                        </div>
                        <p class="text-sm text-slate-400">Criar avaliação interativa</p>
                    </button>

                    <button class="group relative bg-gradient-to-br from-green-500/10 to-green-600/10 hover:from-green-500/20 hover:to-green-600/20 border border-green-500/20 hover:border-green-500/40 rounded-xl p-6 text-left transition-all duration-300 hover:shadow-lg hover:shadow-green-500/10">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-green-500/20 p-2 rounded-lg group-hover:bg-green-500/30 transition-colors">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Relatórios</h4>
                        </div>
                        <p class="text-sm text-slate-400">Visualizar estatísticas detalhadas</p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Efeito de hover nos cards */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        /* Gradiente animado */
        @keyframes gradient-shift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 4s ease infinite;
        }

        /* Pulse personalizado */
        .pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Scrollbar personalizada */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(51, 65, 85, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(220, 38, 38, 0.5);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 38, 38, 0.7);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            // Animar cards de estatísticas
            const statCards = document.querySelectorAll('.group');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Contador animado para estatísticas
            const counters = document.querySelectorAll('.text-2xl.font-bold');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 30);
            });

            // Atualizar status online em tempo real
            setInterval(() => {
                const onlineCount = document.querySelector('.text-green-400.font-medium');
                if (onlineCount) {
                    const currentCount = parseInt(onlineCount.textContent.split(' ')[0]);
                    const newCount = Math.max(5, currentCount + Math.floor(Math.random() * 3) - 1);
                    onlineCount.textContent = `${newCount} online`;
                }
            }, 10000);

            // Efeito de hover nos botões de ação rápida
            const actionButtons = document.querySelectorAll('button[class*="group relative bg-gradient"]');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.02)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });

        // Função para atualizar dados em tempo real
        function updateDashboardData() {
            // Simular atualizações de dados
            const activities = document.querySelectorAll('.space-y-4 > div');
            if (activities.length > 0) {
                // Adicionar nova atividade ocasionalmente
                if (Math.random() > 0.8) {
                    const newActivity = activities[0].cloneNode(true);
                    const userName = `Usuário ${Math.floor(Math.random() * 100)}`;
                    newActivity.querySelector('.text-white').textContent = userName;
                    newActivity.querySelector('.text-xs.text-slate-400').textContent = 'agora mesmo';
                    activities[0].parentNode.insertBefore(newActivity, activities[0]);
                    
                    // Remover última atividade para manter o limite
                    if (activities.length > 5) {
                        activities[activities.length - 1].remove();
                    }
                }
            }
        }

        // Atualizar dados a cada 30 segundos
        setInterval(updateDashboardData, 30000);

        // Adicionar tooltips interativos
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg';
                tooltip.textContent = this.getAttribute('title');
                tooltip.style.top = (e.pageY - 40) + 'px';
                tooltip.style.left = (e.pageX - 50) + 'px';
                document.body.appendChild(tooltip);
                
                this.addEventListener('mouseleave', () => {
                    tooltip.remove();
                });
            });
        });
    </script>
    @endpush
</x-layouts.admin>