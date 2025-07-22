<x-layouts.employee title="Mapa Interativo da Empresa">
    <!-- Hero Section com Background Animado -->
    <div class="relative min-h-screen overflow-hidden">
        <!-- Background Animado -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-hcp-500/10 to-transparent"></div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-hcp-500/20 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-20 w-32 h-32 bg-purple-500/20 rounded-full blur-xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-blue-500/20 rounded-full blur-xl animate-pulse delay-2000"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Moderno -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/80 text-sm font-medium mb-6 border border-white/20">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                    Sistema Interativo • Hemera Capital Partners
                </div>
                
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-white via-hcp-200 to-white bg-clip-text text-transparent">
                        Mapa da Empresa
                    </span>
                </h1>
                
                <p class="text-xl text-white/70 max-w-2xl mx-auto mb-8 leading-relaxed">
                    Explore nossa estrutura organizacional de forma interativa e conheça todos os departamentos e equipes
                </p>

                <!-- Controles Modernos -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                    <!-- Seletor de Andar Moderno -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-2 border border-white/20 shadow-2xl">
                        <div class="flex" role="tablist">
                            @foreach($floors as $floor)
                                <button class="floor-tab relative px-6 py-3 text-sm font-semibold transition-all duration-300 {{ $loop->first ? 'bg-white text-hcp-600 shadow-lg' : 'text-white/70 hover:text-white hover:bg-white/10' }} {{ $loop->first ? 'rounded-xl' : '' }} {{ $loop->last ? 'rounded-xl' : '' }}"
                                        data-floor="{{ $floor['id'] }}"
                                        onclick="switchFloor({{ $floor['id'] }})">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 rounded-full {{ $loop->first ? 'bg-hcp-500' : 'bg-white/40' }}"></div>
                                        <span>{{ $floor['name'] }}</span>
                                    </div>
                                    @if($loop->first)
                                        <div class="absolute inset-0 bg-gradient-to-r from-hcp-500/20 to-hcp-600/20 rounded-xl -z-10"></div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="flex items-center space-x-3">
                        <button class="group bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 shadow-lg hover:shadow-xl"
                                onclick="toggleLegend()">
                            <div class="flex items-center space-x-2">
                                <x-icon name="info" size="sm" class="group-hover:scale-110 transition-transform" />
                                <span class="font-medium">Legenda</span>
                            </div>
                        </button>
                        
                        <button class="group bg-gradient-to-r from-hcp-500 to-hcp-600 text-white px-6 py-3 rounded-xl hover:from-hcp-600 hover:to-hcp-700 transition-all duration-300 shadow-lg hover:shadow-xl font-medium"
                                onclick="toggleFullscreen()">
                            <div class="flex items-center space-x-2">
                                <x-icon name="expand" size="sm" class="group-hover:scale-110 transition-transform" />
                                <span>Tela Cheia</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Layout Principal Moderno -->
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-8">
                <!-- Mapa Interativo Principal (4/5) -->
                <div class="xl:col-span-4">
                    <!-- Container do Mapa com Glass Effect -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                        <!-- Header Elegante do Mapa -->
                        <div class="relative bg-gradient-to-r from-slate-800/90 via-hcp-600/90 to-slate-800/90 backdrop-blur-sm">
                            <div class="absolute inset-0 bg-gradient-to-r from-hcp-500/20 to-purple-500/20"></div>
                            <div class="relative p-8">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30">
                                            <x-icon name="building" size="lg" class="text-white" />
                                        </div>
                                        <div>
                                            <h2 class="text-3xl font-bold text-white mb-1" id="floor-title">1º Andar</h2>
                                            <p class="text-white/80 text-lg" id="floor-description">Atendimento ao cliente, operações e suporte</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Métricas do Andar -->
                                    <div class="flex items-center space-x-6">
                                        <div class="text-center">
                                            <div class="text-4xl font-bold text-white" id="floor-employees">46</div>
                                            <div class="text-white/70 text-sm font-medium">Funcionários</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-4xl font-bold text-white" id="floor-departments">4</div>
                                            <div class="text-white/70 text-sm font-medium">Departamentos</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Área do Mapa Interativo -->
                        <div class="relative bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800" style="height: 700px;">
                            <!-- Grid de Fundo -->
                            <div class="absolute inset-0 opacity-30">
                                <div class="w-full h-full" style="background-image: radial-gradient(circle, #e2e8f0 1px, transparent 1px); background-size: 20px 20px;"></div>
                            </div>
                            
                            <!-- Planta do Escritório -->
                            @foreach($floors as $floor)
                                <div class="floor-map absolute inset-0 {{ $loop->first ? '' : 'hidden' }}" 
                                     id="floor-{{ $floor['id'] }}"
                                     data-floor="{{ $floor['id'] }}">
                                    
                                    <!-- Departamentos Modernos -->
                                    @foreach($departments as $dept)
                                        @if(in_array($dept['id'], $floor['departments']))
                                            <div class="department-area absolute cursor-pointer group"
                                                 data-department="{{ $dept['id'] }}"
                                                 style="left: {{ $dept['position']['x'] }}%; top: {{ $dept['position']['y'] }}%; width: {{ $dept['size']['width'] }}%; height: {{ $dept['size']['height'] }}%;"
                                                 onclick="showDepartmentInfo('{{ $dept['id'] }}')">
                                                
                                                <!-- Card do Departamento -->
                                                <div class="relative w-full h-full rounded-2xl shadow-xl border-2 transition-all duration-500 group-hover:scale-105 group-hover:shadow-2xl group-hover:z-20"
                                                     style="background: linear-gradient(135deg, {{ $dept['color'] }}15, {{ $dept['color'] }}25); border-color: {{ $dept['color'] }};">
                                                    
                                                    <!-- Efeito de Brilho -->
                                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                    
                                                    <!-- Conteúdo do Departamento -->
                                                    <div class="relative p-4 h-full flex flex-col justify-between">
                                                        <!-- Header do Departamento -->
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex items-center space-x-3">
                                                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"
                                                                     style="background: linear-gradient(135deg, {{ $dept['color'] }}, {{ $dept['color'] }}dd);">
                                                                    <x-icon name="{{ $dept['icon'] }}" size="sm" />
                                                                </div>
                                                                <div>
                                                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white leading-tight mb-1">
                                                                        {{ $dept['name'] }}
                                                                    </h3>
                                                                    <div class="text-xs text-slate-600 dark:text-slate-400">
                                                                        {{ $dept['team_count'] }} pessoas
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Status Indicator -->
                                                            <div class="w-3 h-3 rounded-full bg-green-400 shadow-lg animate-pulse"></div>
                                                        </div>
                                                        
                                                        <!-- Footer com Líder -->
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center space-x-2">
                                                                <img src="{{ $dept['leader']['avatar'] }}" 
                                                                     alt="{{ $dept['leader']['name'] }}"
                                                                     class="w-6 h-6 rounded-full border-2 border-white shadow-sm">
                                                                <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">
                                                                    {{ explode(' ', $dept['leader']['name'])[0] }}
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="text-xs text-slate-500 dark:text-slate-500">
                                                                {{ $dept['leader']['position'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Overlay de Hover -->
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                                        <div class="bg-white/90 backdrop-blur-sm text-slate-800 px-4 py-2 rounded-xl font-semibold text-sm shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                                            <div class="flex items-center space-x-2">
                                                                <x-icon name="cursor-click" size="xs" />
                                                                <span>Ver Detalhes</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                            
                            <!-- Indicadores de Navegação -->
                            <div class="absolute bottom-6 left-6 flex items-center space-x-2">
                                <div class="bg-white/80 backdrop-blur-sm rounded-full px-4 py-2 shadow-lg border border-white/30">
                                    <div class="flex items-center space-x-2 text-sm font-medium text-slate-700">
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                        <span>Sistema Ativo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Painel Lateral Moderno (1/5) -->
                <div class="xl:col-span-1 space-y-6">
                    <!-- Informações do Departamento -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 overflow-hidden" id="department-info-panel">
                        <div class="p-6">
                            <div class="text-center text-white/70">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm border border-white/20">
                                    <x-icon name="cursor-click" size="lg" class="text-white/60" />
                                </div>
                                <h3 class="text-lg font-semibold text-white mb-2">Explore os Departamentos</h3>
                                <p class="text-sm text-white/60 leading-relaxed">
                                    Clique em qualquer departamento no mapa para descobrir informações detalhadas sobre a equipe e suas funções
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Legenda Moderna -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 overflow-hidden hidden" id="legend-panel">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm border border-white/30">
                                    <x-icon name="info" size="sm" class="text-white" />
                                </div>
                                <h3 class="text-lg font-bold text-white">Legenda dos Departamentos</h3>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($departments as $dept)
                                    <div class="flex items-center p-3 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300">
                                        <div class="w-4 h-4 rounded-full mr-4 shadow-lg" style="background-color: {{ $dept['color'] }};"></div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-white">{{ $dept['name'] }}</div>
                                            <div class="text-xs text-white/60">{{ $dept['team_count'] }} funcionários</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-white/20">
                                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                                    <div class="text-xs text-white/70 space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            <span><strong>Dica:</strong> Clique nos departamentos para ver detalhes</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                                            <span><strong>Navegação:</strong> Use as abas para alternar entre andares</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas Avançadas -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-gradient-to-br from-hcp-500 to-hcp-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <x-icon name="chart-bar" size="sm" class="text-white" />
                                </div>
                                <h3 class="text-lg font-bold text-white">Estatísticas da Empresa</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Total de Departamentos -->
                                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3">
                                                <x-icon name="building" size="xs" class="text-purple-400" />
                                            </div>
                                            <span class="text-sm font-medium text-white/80">Departamentos</span>
                                        </div>
                                        <span class="text-2xl font-bold text-white">{{ count($departments) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Total de Funcionários -->
                                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center mr-3">
                                                <x-icon name="users" size="xs" class="text-green-400" />
                                            </div>
                                            <span class="text-sm font-medium text-white/80">Funcionários</span>
                                        </div>
                                        <span class="text-2xl font-bold text-white">{{ array_sum(array_column($departments, 'team_count')) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Andares -->
                                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3">
                                                <x-icon name="layers" size="xs" class="text-blue-400" />
                                            </div>
                                            <span class="text-sm font-medium text-white/80">Andares</span>
                                        </div>
                                        <span class="text-2xl font-bold text-white">{{ count($floors) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico de Distribuição -->
                            <div class="mt-6 pt-4 border-t border-white/20">
                                <h4 class="text-sm font-semibold text-white mb-3">Distribuição por Andar</h4>
                                @foreach($floors as $floor)
                                    @php
                                        $floorEmployees = array_sum(array_map(function($dept) use ($floor) {
                                            return in_array($dept['id'], $floor['departments']) ? $dept['team_count'] : 0;
                                        }, $departments));
                                        $percentage = round(($floorEmployees / array_sum(array_column($departments, 'team_count'))) * 100);
                                    @endphp
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between text-xs text-white/70 mb-1">
                                            <span>{{ $floor['name'] }}</span>
                                            <span>{{ $floorEmployees }} pessoas ({{ $percentage }}%)</span>
                                        </div>
                                        <div class="w-full bg-white/10 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-hcp-500 to-hcp-600 h-2 rounded-full transition-all duration-1000" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Dados dos departamentos (passados do PHP)
        const departments = @json($departments);
        const floors = @json($floors);
        
        // Estado atual
        let currentFloor = 1;
        let legendVisible = false;

        /**
         * Alternar entre andares
         */
        function switchFloor(floorId) {
            currentFloor = floorId;
            
            // Atualizar abas com novo estilo
            document.querySelectorAll('.floor-tab').forEach(tab => {
                const isActive = tab.dataset.floor == floorId;
                
                if (isActive) {
                    tab.classList.add('bg-white', 'text-hcp-600', 'shadow-lg');
                    tab.classList.remove('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                    // Atualizar indicador
                    const indicator = tab.querySelector('.w-2.h-2');
                    if (indicator) {
                        indicator.classList.add('bg-hcp-500');
                        indicator.classList.remove('bg-white/40');
                    }
                } else {
                    tab.classList.remove('bg-white', 'text-hcp-600', 'shadow-lg');
                    tab.classList.add('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                    // Atualizar indicador
                    const indicator = tab.querySelector('.w-2.h-2');
                    if (indicator) {
                        indicator.classList.remove('bg-hcp-500');
                        indicator.classList.add('bg-white/40');
                    }
                }
            });
            
            // Atualizar mapas
            document.querySelectorAll('.floor-map').forEach(map => {
                const isActive = map.dataset.floor == floorId;
                map.classList.toggle('hidden', !isActive);
            });
            
            // Atualizar informações do andar
            const floor = floors.find(f => f.id == floorId);
            if (floor) {
                document.getElementById('floor-title').textContent = floor.name;
                document.getElementById('floor-description').textContent = floor.description;
                
                // Calcular funcionários e departamentos do andar
                const floorEmployees = departments
                    .filter(dept => floor.departments.includes(dept.id))
                    .reduce((sum, dept) => sum + dept.team_count, 0);
                const floorDepartments = floor.departments.length;
                
                document.getElementById('floor-employees').textContent = floorEmployees;
                document.getElementById('floor-departments').textContent = floorDepartments;
            }
            
            // Limpar painel de informações
            resetDepartmentInfo();
        }

        /**
         * Mostrar informações do departamento com design moderno
         */
        function showDepartmentInfo(departmentId) {
            const department = departments.find(d => d.id === departmentId);
            if (!department) return;
            
            const panel = document.getElementById('department-info-panel');
            panel.innerHTML = `
                <div class="p-6">
                    <!-- Header Moderno do Departamento -->
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent rounded-2xl"></div>
                        <div class="relative flex items-center p-4">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white mr-4 shadow-2xl" 
                                 style="background: linear-gradient(135deg, ${department.color}, ${department.color}dd);">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <!-- Ícone dinâmico baseado no departamento -->
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white mb-1">
                                    ${department.name}
                                </h3>
                                <div class="flex items-center space-x-4 text-sm text-white/70">
                                    <span class="flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                        ${department.team_count} funcionários
                                    </span>
                                    <span class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                                        Ativo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Líder do Departamento Moderno -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 mb-6 border border-white/10">
                        <div class="flex items-center mb-4">
                            <div class="relative">
                                <img src="${department.leader.avatar}" 
                                     alt="${department.leader.name}"
                                     class="w-12 h-12 rounded-xl border-2 border-white/20 shadow-lg">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white/20"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="font-bold text-white text-sm mb-1">
                                    ${department.leader.name}
                                </h4>
                                <p class="text-xs text-white/70 mb-2">
                                    ${department.leader.position}
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center p-2 bg-white/5 rounded-lg border border-white/10">
                                <svg class="w-4 h-4 mr-3 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span class="text-xs text-white/80">${department.leader.email}</span>
                            </div>
                            <div class="flex items-center p-2 bg-white/5 rounded-lg border border-white/10">
                                <svg class="w-4 h-4 mr-3 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <span class="text-xs text-white/80">${department.leader.phone}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Descrição Moderna -->
                    <div class="mb-6">
                        <h5 class="font-bold text-white text-sm mb-3 flex items-center">
                            <div class="w-2 h-2 bg-hcp-500 rounded-full mr-2"></div>
                            Sobre o Departamento
                        </h5>
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <p class="text-sm text-white/80 leading-relaxed">
                                ${department.description}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Objetivos Modernos -->
                    <div class="mb-6">
                        <h5 class="font-bold text-white text-sm mb-3 flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            Principais Objetivos
                        </h5>
                        <div class="space-y-2">
                            ${department.objectives.map(obj => `
                                <div class="flex items-start p-3 bg-white/5 rounded-lg border border-white/10 hover:bg-white/10 transition-all duration-300">
                                    <div class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-white/80">${obj}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    
                    <!-- Funções Modernas -->
                    <div>
                        <h5 class="font-bold text-white text-sm mb-3 flex items-center">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                            Principais Funções
                        </h5>
                        <div class="flex flex-wrap gap-2">
                            ${department.functions.map(func => `
                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-white/10 text-white/90 rounded-full border border-white/20 backdrop-blur-sm hover:bg-white/20 transition-all duration-300">
                                    ${func}
                                </span>
                            `).join('')}
                        </div>
                    </div>
                    
                    <!-- Botão de Ação -->
                    <div class="mt-6 pt-4 border-t border-white/20">
                        <button class="w-full bg-gradient-to-r from-hcp-500 to-hcp-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-hcp-600 hover:to-hcp-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span>Entrar em Contato</span>
                            </div>
                        </button>
                    </div>
                </div>
            `;
        }

        /**
         * Resetar painel de informações com design moderno
         */
        function resetDepartmentInfo() {
            const panel = document.getElementById('department-info-panel');
            panel.innerHTML = `
                <div class="p-6">
                    <div class="text-center text-white/70">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm border border-white/20">
                            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.121 2.122"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Explore os Departamentos</h3>
                        <p class="text-sm text-white/60 leading-relaxed">
                            Clique em qualquer departamento no mapa para descobrir informações detalhadas sobre a equipe e suas funções
                        </p>
                    </div>
                </div>
            `;
        }

        /**
         * Alternar legenda com animação
         */
        function toggleLegend() {
            legendVisible = !legendVisible;
            const panel = document.getElementById('legend-panel');
            
            if (legendVisible) {
                panel.classList.remove('hidden');
                // Animação de entrada
                setTimeout(() => {
                    panel.style.transform = 'translateY(0)';
                    panel.style.opacity = '1';
                }, 10);
            } else {
                // Animação de saída
                panel.style.transform = 'translateY(-20px)';
                panel.style.opacity = '0';
                setTimeout(() => {
                    panel.classList.add('hidden');
                }, 300);
            }
        }

        /**
         * Alternar tela cheia
         */
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Erro ao entrar em tela cheia:', err);
                });
            } else {
                document.exitFullscreen();
            }
        }

        /**
         * Animações de entrada
         */
        function initAnimations() {
            // Animar departamentos na entrada
            const departments = document.querySelectorAll('.department-area');
            departments.forEach((dept, index) => {
                dept.style.opacity = '0';
                dept.style.transform = 'scale(0.8) translateY(20px)';
                
                setTimeout(() => {
                    dept.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    dept.style.opacity = '1';
                    dept.style.transform = 'scale(1) translateY(0)';
                }, index * 100);
            });
        }

        /**
         * Efeitos de hover avançados
         */
        function initHoverEffects() {
            const departments = document.querySelectorAll('.department-area');
            
            departments.forEach(dept => {
                dept.addEventListener('mouseenter', function() {
                    // Destacar departamento relacionado
                    const deptId = this.dataset.department;
                    this.style.zIndex = '50';
                    
                    // Diminuir opacidade dos outros
                    departments.forEach(other => {
                        if (other !== this) {
                            other.style.opacity = '0.6';
                        }
                    });
                });
                
                dept.addEventListener('mouseleave', function() {
                    this.style.zIndex = '';
                    
                    // Restaurar opacidade
                    departments.forEach(other => {
                        other.style.opacity = '1';
                    });
                });
            });
        }

        /**
         * Atalhos de teclado
         */
        function initKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                switch(e.key) {
                    case '1':
                        switchFloor(1);
                        break;
                    case '2':
                        switchFloor(2);
                        break;
                    case 'l':
                    case 'L':
                        toggleLegend();
                        break;
                    case 'f':
                    case 'F':
                        if (e.ctrlKey || e.metaKey) {
                            e.preventDefault();
                            toggleFullscreen();
                        }
                        break;
                    case 'Escape':
                        if (legendVisible) {
                            toggleLegend();
                        }
                        break;
                }
            });
        }

        // Inicialização completa
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar andar inicial
            switchFloor(1);
            
            // Inicializar animações
            setTimeout(initAnimations, 500);
            
            // Inicializar efeitos de hover
            initHoverEffects();
            
            // Inicializar atalhos de teclado
            initKeyboardShortcuts();
            
            // Configurar transições suaves para painéis
            const panels = document.querySelectorAll('#legend-panel');
            panels.forEach(panel => {
                panel.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });

        // Detectar mudanças de tela cheia
        document.addEventListener('fullscreenchange', function() {
            const button = document.querySelector('[onclick="toggleFullscreen()"]');
            const icon = button.querySelector('svg');
            const text = button.querySelector('span');
            
            if (document.fullscreenElement) {
                text.textContent = 'Sair da Tela Cheia';
                // Trocar ícone se necessário
            } else {
                text.textContent = 'Tela Cheia';
                // Restaurar ícone
            }
        });
    </script>
    @endpush
</x-layouts.employee>
                 