<x-layouts.admin title="Gestão de Usuários">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header com Estatísticas Rápidas -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Gestão de Usuários</h1>
                            <p class="text-blue-100 mt-1">Painel de controle e estatísticas</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.users.create') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Novo Usuário
                        </a>
                    </div>
                </div>

                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total de Usuários</p>
                                <p class="text-white text-3xl font-bold">{{ $stats['total_users'] ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Usuários Ativos</p>
                                <p class="text-white text-3xl font-bold">{{ $stats['active_users'] ?? 0 }}</p>
                            </div>
                            <div class="bg-green-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Administradores</p>
                                <p class="text-white text-3xl font-bold">{{ $stats['admin_users'] ?? 0 }}</p>
                            </div>
                            <div class="bg-red-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Novos (30 dias)</p>
                                <p class="text-white text-3xl font-bold">{{ $stats['new_users'] ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filtros e Ações Rápidas -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <!-- Filtro por Role -->
                        <select class="bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todas as Funções</option>
                            <option value="admin">Administradores</option>
                            <option value="manager">Gerentes</option>
                            <option value="employee">Funcionários</option>
                        </select>

                        <!-- Filtro por Status -->
                        <select class="bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos os Status</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                        </select>

                        <!-- Filtro por Departamento -->
                        <select class="bg-slate-700/50 border border-slate-600 text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos os Departamentos</option>
                            <option value="TI">TI</option>
                            <option value="RH">RH</option>
                            <option value="Vendas">Vendas</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                    </div>

                    <div class="flex space-x-3">
                        <!-- Busca -->
                        <div class="relative">
                            <input type="text" placeholder="Buscar usuários..." class="bg-slate-700/50 border border-slate-600 text-white rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <!-- Ações em Massa -->
                        <button class="bg-slate-700/50 hover:bg-slate-600/50 border border-slate-600 text-white px-4 py-2 rounded-xl transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                            Ações
                        </button>
                    </div>
                </div>
            </div>

            <!-- Gráficos e Análises -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Distribuição por Função -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Distribuição por Função</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                <span class="text-slate-300">Administradores</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-white font-semibold">{{ $stats['admin_users'] ?? 0 }}</span>
                                <div class="w-24 bg-slate-700 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $stats['admin_percentage'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                                <span class="text-slate-300">Gerentes</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-white font-semibold">{{ $stats['manager_users'] ?? 0 }}</span>
                                <div class="w-24 bg-slate-700 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['manager_percentage'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                <span class="text-slate-300">Funcionários</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-white font-semibold">{{ $stats['employee_users'] ?? 0 }}</span>
                                <div class="w-24 bg-slate-700 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['employee_percentage'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atividade Recente -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Atividade Recente</h3>
                    <div class="space-y-4">
                        @foreach(range(1, 5) as $i)
                            <div class="flex items-center space-x-4 p-3 bg-slate-800/50 rounded-xl">
                                <div class="flex-shrink-0">
                                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=User{{ $i }}&color=FFFFFF&background={{ ['F87171', '60A5FA', '34D399'][($i-1) % 3] }}" alt="">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white">Usuário {{ $i }}</p>
                                    <p class="text-xs text-slate-400">
                                        @if($i % 3 == 0)
                                            Conta criada
                                        @elseif($i % 3 == 1)
                                            Perfil atualizado
                                        @else
                                            Login realizado
                                        @endif
                                    </p>
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ now()->subHours($i)->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Análise de Engajamento -->
            @include('admin.components.engagement-analytics')

            <!-- Sugestões Inteligentes -->
            @include('admin.components.smart-suggestions')

            <!-- Ações Rápidas -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6">
                <h3 class="text-xl font-bold text-white mb-6">Ações Rápidas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.users.index') }}" class="group bg-gradient-to-br from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 border border-blue-500/20 hover:border-blue-500/40 rounded-xl p-6 transition-all duration-300 hover:scale-105">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-blue-500/20 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Listar Usuários</h4>
                        </div>
                        <p class="text-sm text-slate-400">Ver todos os usuários cadastrados</p>
                    </a>

                    <a href="{{ route('admin.users.create') }}" class="group bg-gradient-to-br from-green-500/10 to-green-600/10 hover:from-green-500/20 hover:to-green-600/20 border border-green-500/20 hover:border-green-500/40 rounded-xl p-6 transition-all duration-300 hover:scale-105">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-green-500/20 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Criar Usuário</h4>
                        </div>
                        <p class="text-sm text-slate-400">Adicionar novo usuário ao sistema</p>
                    </a>

                    <button class="group bg-gradient-to-br from-purple-500/10 to-purple-600/10 hover:from-purple-500/20 hover:to-purple-600/20 border border-purple-500/20 hover:border-purple-500/40 rounded-xl p-6 transition-all duration-300 hover:scale-105">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-purple-500/20 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Relatório</h4>
                        </div>
                        <p class="text-sm text-slate-400">Gerar relatório de usuários</p>
                    </button>

                    <button class="group bg-gradient-to-br from-amber-500/10 to-amber-600/10 hover:from-amber-500/20 hover:to-amber-600/20 border border-amber-500/20 hover:border-amber-500/40 rounded-xl p-6 transition-all duration-300 hover:scale-105">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-amber-500/20 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-white">Importar</h4>
                        </div>
                        <p class="text-sm text-slate-400">Importar usuários em massa</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>