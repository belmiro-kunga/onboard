<x-layouts.employee title="Módulos de Aprendizado">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-hcp-secondary-900 dark:text-white">
                        Módulos de Aprendizado
                    </h1>
                    <p class="mt-2 text-lg text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Explore os módulos disponíveis e acompanhe seu progresso
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <div class="relative">
                        <select id="module-filter" class="appearance-none bg-white dark:bg-hcp-secondary-800 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-lg py-2 pl-3 pr-10 text-sm leading-5 text-hcp-secondary-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-hcp-500 focus:border-hcp-500">
                            <option value="all">Todos os módulos</option>
                            <option value="in_progress">Em andamento</option>
                            <option value="completed">Completados</option>
                            <option value="not_started">Não iniciados</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Suas Missões Ativas -->
            <div class="animate-fade-in-left animate-delay-300 mb-8">
                <div class="rounded-hcp-xl transition-all duration-300 ease-in-out bg-white dark:bg-hcp-secondary-800 border border-hcp-secondary-200 dark:border-hcp-secondary-600 shadow-hcp p-8 hover:shadow-hcp-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-hcp-secondary-900 dark:text-white flex items-center">
                            <div class="w-8 h-8 bg-hcp-gradient rounded-hcp flex items-center justify-center mr-3">
                                <x-icon name="target" size="sm" class="text-white" />
                            </div>
                            Suas Missões Ativas
                        </h2>
                        <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                            2 em andamento
                        </div>
                    </div>
                    
                    <div class="space-y-4" id="active-missions-container">
                        @php
                            $activeMissions = collect($modulesWithProgress ?? [])->where('user_status', 'in_progress')->take(3);
                        @endphp
                        
                        @php
                            // Dados de exemplo para missões ativas
                            $activeMissionsData = [
                                [
                                    'id' => 1,
                                    'title' => 'Introdução ao Laravel',
                                    'description' => 'Aprenda os fundamentos do framework Laravel, desde a instalação até a criação de suas primeiras rotas e controllers.',
                                    'progress' => 65,
                                    'formatted_duration' => '15:32',
                                    'points_reward' => 150
                                ],
                                [
                                    'id' => 4,
                                    'title' => 'JavaScript Moderno ES6+',
                                    'description' => 'Explore as funcionalidades modernas do JavaScript: arrow functions, destructuring, promises e async/await.',
                                    'progress' => 30,
                                    'formatted_duration' => '25:15',
                                    'points_reward' => 180
                                ]
                            ];
                        @endphp
                        
                        @forelse($activeMissionsData as $module)
                            <div class="module-mission group cursor-pointer" data-module="{{ $module['id'] }}">
                                <a href="{{ route('modules.show', $module['id']) }}" class="block">
                                    <div class="flex items-center p-6 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-2xl hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-600 transition-all duration-200 hover:scale-[1.02] hover:shadow-hcp-lg border border-hcp-secondary-200/50 dark:border-hcp-secondary-600/50">
                                        <div class="relative mr-6">
                                            <div class="w-16 h-16 bg-gradient-to-br from-hcp-500 to-hcp-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200 shadow-hcp-md">
                                                <x-icon name="play" size="lg" class="text-white" />
                                            </div>
                                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-hcp-warning-500 rounded-full border-3 border-white dark:border-hcp-secondary-700 animate-pulse shadow-lg flex items-center justify-center">
                                                <x-icon name="clock" size="xs" class="text-white" />
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white group-hover:text-hcp-500 dark:group-hover:text-hcp-400 transition-colors">
                                                    {{ $module['title'] }}
                                                </h3>
                                                <span class="text-xs bg-hcp-warning-100 dark:bg-hcp-warning-900/20 text-hcp-warning-700 dark:text-hcp-warning-300 px-2 py-1 rounded-full font-medium">
                                                    Em Andamento
                                                </span>
                                            </div>
                                            <p class="text-base text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-3 line-clamp-2">
                                                {{ $module['description'] }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    <span class="flex items-center">
                                                        <x-icon name="clock" size="xs" class="mr-1" />
                                                        {{ $module['formatted_duration'] }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <x-icon name="star" size="xs" class="mr-1" />
                                                        {{ $module['points_reward'] }} pontos
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-24 bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-2">
                                                        <div class="bg-hcp-gradient rounded-full h-2 transition-all duration-500" 
                                                             style="width: {{ $module['progress'] }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 min-w-[3rem]">
                                                        {{ $module['progress'] }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <x-icon name="chevron-right" size="sm" class="text-hcp-secondary-400 group-hover:text-hcp-500 transition-colors" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <x-icon name="check-circle" size="xl" class="text-hcp-success-500" />
                                </div>
                                <h3 class="text-xl font-semibold text-hcp-secondary-900 dark:text-white mb-3">
                                    Nenhuma missão ativa no momento
                                </h3>
                                <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-6 max-w-md mx-auto">
                                    Você está em dia com seus estudos! Explore novos módulos abaixo para continuar aprendendo.
                                </p>
                                <div class="inline-flex items-center px-6 py-3 bg-hcp-gradient text-white rounded-hcp-xl font-semibold hover:shadow-hcp-lg transition-all duration-200 hover:scale-105">
                                    <x-icon name="search" size="sm" class="mr-2" />
                                    Explorar Módulos
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    @if(count($activeMissionsData) > 0)
                    <div class="mt-6 pt-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-600">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                Continue seus estudos e ganhe mais pontos! 🚀
                            </div>
                            <button onclick="document.getElementById('modules-grid').scrollIntoView({behavior: 'smooth'})" 
                                    class="text-hcp-500 hover:text-hcp-400 text-sm font-medium transition-colors flex items-center">
                                Ver todos os módulos
                                <x-icon name="arrow-down" size="sm" class="ml-1" />
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Módulos Grid - Cards Quadrados com Thumbnails -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="modules-grid">
                @php
                    // Dados de exemplo com vídeos do YouTube
                    $exampleModules = [
                        [
                            'id' => 1,
                            'title' => 'Introdução ao Laravel',
                            'description' => 'Aprenda os fundamentos do framework Laravel, desde a instalação até a criação de suas primeiras rotas e controllers.',
                            'youtube_id' => '1G_pjxjxTkA',
                            'thumbnail' => 'https://img.youtube.com/vi/1G_pjxjxTkA/maxresdefault.jpg',
                            'duration' => '15:32',
                            'difficulty' => 'Iniciante',
                            'points' => 150,
                            'status' => 'in_progress',
                            'progress' => 65,
                            'video_count' => 1,
                            'unlocked' => true
                        ],
                        [
                            'id' => 2,
                            'title' => 'PHP Orientado a Objetos',
                            'description' => 'Domine os conceitos de programação orientada a objetos em PHP: classes, objetos, herança e polimorfismo.',
                            'youtube_id' => 'hEYWTzPvQhE',
                            'thumbnail' => 'https://img.youtube.com/vi/hEYWTzPvQhE/maxresdefault.jpg',
                            'duration' => '22:45',
                            'difficulty' => 'Intermediário',
                            'points' => 200,
                            'status' => 'completed',
                            'progress' => 100,
                            'video_count' => 3,
                            'unlocked' => true
                        ],
                        [
                            'id' => 3,
                            'title' => 'Banco de Dados MySQL',
                            'description' => 'Aprenda a trabalhar com MySQL: criação de tabelas, consultas SQL, relacionamentos e otimização de queries.',
                            'youtube_id' => 'Cz3WcZLRaWc',
                            'thumbnail' => 'https://img.youtube.com/vi/Cz3WcZLRaWc/maxresdefault.jpg',
                            'duration' => '18:20',
                            'difficulty' => 'Iniciante',
                            'points' => 120,
                            'status' => 'not_started',
                            'progress' => 0,
                            'video_count' => 2,
                            'unlocked' => true
                        ],
                        [
                            'id' => 4,
                            'title' => 'JavaScript Moderno ES6+',
                            'description' => 'Explore as funcionalidades modernas do JavaScript: arrow functions, destructuring, promises e async/await.',
                            'youtube_id' => 'NCwa_xi0Uuc',
                            'thumbnail' => 'https://img.youtube.com/vi/NCwa_xi0Uuc/maxresdefault.jpg',
                            'duration' => '25:15',
                            'difficulty' => 'Intermediário',
                            'points' => 180,
                            'status' => 'in_progress',
                            'progress' => 30,
                            'video_count' => 4,
                            'unlocked' => true
                        ],
                        [
                            'id' => 5,
                            'title' => 'Git e GitHub Essencial',
                            'description' => 'Domine o controle de versão com Git e aprenda a colaborar em projetos usando GitHub.',
                            'youtube_id' => 'xEKo29OWILE',
                            'thumbnail' => 'https://img.youtube.com/vi/xEKo29OWILE/maxresdefault.jpg',
                            'duration' => '12:30',
                            'difficulty' => 'Iniciante',
                            'points' => 100,
                            'status' => 'not_started',
                            'progress' => 0,
                            'video_count' => 1,
                            'unlocked' => false
                        ],
                        [
                            'id' => 6,
                            'title' => 'API REST com Laravel',
                            'description' => 'Construa APIs RESTful robustas com Laravel: autenticação, validação, serialização e documentação.',
                            'youtube_id' => 'MT-GJQIY3EU',
                            'thumbnail' => 'https://img.youtube.com/vi/MT-GJQIY3EU/maxresdefault.jpg',
                            'duration' => '35:45',
                            'difficulty' => 'Avançado',
                            'points' => 250,
                            'status' => 'not_started',
                            'progress' => 0,
                            'video_count' => 5,
                            'unlocked' => false
                        ],
                        [
                            'id' => 7,
                            'title' => 'Vue.js Fundamentos',
                            'description' => 'Aprenda Vue.js do zero: componentes, diretivas, computed properties e gerenciamento de estado.',
                            'youtube_id' => '5LYrN_cAJoA',
                            'thumbnail' => 'https://img.youtube.com/vi/5LYrN_cAJoA/maxresdefault.jpg',
                            'duration' => '28:10',
                            'difficulty' => 'Intermediário',
                            'points' => 190,
                            'status' => 'not_started',
                            'progress' => 0,
                            'video_count' => 3,
                            'unlocked' => false
                        ],
                        [
                            'id' => 8,
                            'title' => 'Docker para Desenvolvedores',
                            'description' => 'Containerize suas aplicações com Docker: criação de imagens, docker-compose e deploy em produção.',
                            'youtube_id' => 'Kzcz-EVKBEQ',
                            'thumbnail' => 'https://img.youtube.com/vi/Kzcz-EVKBEQ/maxresdefault.jpg',
                            'duration' => '42:20',
                            'difficulty' => 'Avançado',
                            'points' => 300,
                            'status' => 'not_started',
                            'progress' => 0,
                            'video_count' => 6,
                            'unlocked' => false
                        ]
                    ];
                @endphp

                @foreach($exampleModules as $module)
                    <div class="module-card group bg-white dark:bg-hcp-secondary-800 rounded-2xl shadow-lg overflow-hidden border border-hcp-secondary-200 dark:border-hcp-secondary-700 transition-all duration-300 hover:shadow-2xl hover:border-hcp-500 dark:hover:border-hcp-400 hover:-translate-y-2 {{ !$module['unlocked'] ? 'opacity-75' : '' }}" 
                         data-status="{{ $module['status'] }}" 
                         data-difficulty="{{ $module['difficulty'] }}" 
                         data-time="{{ $module['duration'] }}" 
                         data-name="{{ $module['title'] }}" 
                         data-order="{{ $module['id'] }}">
                        
                        <!-- Thumbnail Quadrado -->
                        <div class="relative aspect-square bg-gradient-to-br from-hcp-500 to-hcp-600 overflow-hidden">
                            <img src="{{ $module['thumbnail'] }}" 
                                 alt="{{ $module['title'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            <!-- Overlay com botão play -->
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                    <x-icon name="play" size="lg" class="text-hcp-500 ml-1" />
                                </div>
                            </div>
                            
                            <!-- Status badge -->
                            <div class="absolute top-3 right-3 z-10">
                                @if($module['status'] === 'completed')
                                    <div class="w-8 h-8 bg-hcp-success-500 rounded-full flex items-center justify-center shadow-lg">
                                        <x-icon name="check" size="sm" class="text-white" />
                                    </div>
                                @elseif($module['status'] === 'in_progress')
                                    <div class="w-8 h-8 bg-hcp-warning-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                        <x-icon name="clock" size="sm" class="text-white" />
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-hcp-secondary-400 rounded-full flex items-center justify-center shadow-lg">
                                        <x-icon name="play" size="sm" class="text-white" />
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Duração do vídeo -->
                            <div class="absolute bottom-3 right-3 bg-black/70 text-white px-2 py-1 rounded text-xs font-medium">
                                {{ $module['duration'] }}
                            </div>
                            
                            <!-- YouTube badge -->
                            <div class="absolute bottom-3 left-3 bg-red-600 text-white px-2 py-1 rounded text-xs font-medium flex items-center">
                                <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                YouTube
                            </div>
                            
                            <!-- Lock overlay -->
                            @if(!$module['unlocked'])
                                <div class="absolute inset-0 bg-hcp-secondary-900/80 flex items-center justify-center z-20">
                                    <div class="text-center p-4">
                                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <x-icon name="lock" size="lg" class="text-white" />
                                        </div>
                                        <p class="text-xs text-white font-medium">Módulo Bloqueado</p>
                                        <p class="text-xs text-white/70 mt-1">Complete os anteriores</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Conteúdo do Card -->
                        <div class="p-5">
                            <!-- Título e Nível -->
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-bold text-hcp-secondary-900 dark:text-white line-clamp-2 group-hover:text-hcp-500 dark:group-hover:text-hcp-400 transition-colors">
                                    {{ $module['title'] }}
                                </h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0 {{ $module['difficulty'] === 'Iniciante' ? 'bg-hcp-success-100 text-hcp-success-700 dark:bg-hcp-success-900/20 dark:text-hcp-success-300' : ($module['difficulty'] === 'Intermediário' ? 'bg-hcp-warning-100 text-hcp-warning-700 dark:bg-hcp-warning-900/20 dark:text-hcp-warning-300' : 'bg-hcp-danger-100 text-hcp-danger-700 dark:bg-hcp-danger-900/20 dark:text-hcp-danger-300') }}">
                                    {{ $module['difficulty'] }}
                                </span>
                            </div>
                            
                            <!-- Descrição -->
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 line-clamp-3 mb-4">
                                {{ $module['description'] }}
                            </p>
                            
                            <!-- Informações do Vídeo -->
                            <div class="flex items-center justify-between text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mb-4">
                                <div class="flex items-center space-x-3">
                                    <span class="flex items-center">
                                        <x-icon name="play-circle" size="xs" class="mr-1" />
                                        {{ $module['video_count'] }} vídeo{{ $module['video_count'] > 1 ? 's' : '' }}
                                    </span>
                                    <span class="flex items-center">
                                        <x-icon name="clock" size="xs" class="mr-1" />
                                        {{ $module['duration'] }}
                                    </span>
                                </div>
                                <span class="flex items-center font-medium text-hcp-500">
                                    <x-icon name="star" size="xs" class="mr-1" />
                                    {{ $module['points'] }}
                                </span>
                            </div>
                            
                            <!-- Barra de Progresso -->
                            @if($module['status'] !== 'not_started')
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <span class="font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">
                                            @if($module['status'] === 'completed')
                                                Concluído
                                            @else
                                                Progresso
                                            @endif
                                        </span>
                                        <span class="font-bold text-hcp-500">{{ $module['progress'] }}%</span>
                                    </div>
                                    <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-hcp-500 to-hcp-600 h-2 rounded-full transition-all duration-500 shadow-sm" 
                                             style="width: {{ $module['progress'] }}%"></div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Botão de Ação -->
                            <a href="{{ $module['unlocked'] ? route('modules.show', $module['id']) : '#' }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105 active:scale-95 {{ $module['unlocked'] ? 'bg-hcp-gradient text-white hover:shadow-hcp-lg' : 'bg-hcp-secondary-300 dark:bg-hcp-secondary-700 text-hcp-secondary-500 dark:text-hcp-secondary-400 cursor-not-allowed' }}"
                               {{ !$module['unlocked'] ? 'onclick="return false;"' : '' }}>
                                @if($module['status'] === 'completed')
                                    <x-icon name="refresh-cw" size="sm" class="mr-2" />
                                    Revisar Curso
                                @elseif($module['status'] === 'in_progress')
                                    <x-icon name="play" size="sm" class="mr-2" />
                                    Continuar Curso
                                @else
                                    <x-icon name="play" size="sm" class="mr-2" />
                                    Iniciar Curso
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Empty state -->
            <div id="empty-state" class="hidden py-12 text-center">
                <div class="text-6xl text-hcp-secondary-400 dark:text-hcp-secondary-600 mb-4">🔍</div>
                <h3 class="text-lg font-medium text-hcp-secondary-900 dark:text-white">Nenhum módulo encontrado</h3>
                <p class="mt-2 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">Tente ajustar seus filtros para encontrar o que procura.</p>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation -->
    <x-mobile-nav current="modules" />
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moduleFilter = document.getElementById('module-filter');
            const modulesGrid = document.getElementById('modules-grid');
            const emptyState = document.getElementById('empty-state');
            const moduleCards = document.querySelectorAll('.module-card');

            function filterModules() {
                const filterValue = moduleFilter.value;
                let visibleCount = 0;

                moduleCards.forEach(card => {
                    const status = card.dataset.status;
                    let shouldShow = true;

                    if (filterValue !== 'all' && status !== filterValue) {
                        shouldShow = false;
                    }

                    if (shouldShow) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (visibleCount === 0) {
                    modulesGrid.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    modulesGrid.style.display = 'grid';
                    emptyState.style.display = 'none';
                }
            }

            moduleFilter.addEventListener('change', filterModules);
        });
    </script>
    @endpush
</x-layouts.app>