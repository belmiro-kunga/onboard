<x-app-layout>
    <div class="py-12 bg-gradient-to-b from-blue-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Navegação -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('courses.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Cursos</a>
                            </div>
                        </li>
                        @if($course)
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('courses.show', $course) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{ $course->title }}</a>
                                </div>
                            </li>
                        @endif
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $module->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Coluna Principal: Conteúdo do Módulo -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Cabeçalho do Módulo -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $module->title }}</h1>
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $module->category }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Ações do Vídeo -->
                                <div class="flex items-center space-x-3">
                                    <button class="flex items-center px-4 py-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600 transition-colors">
                                        <x-icon name="thumbs-up" size="sm" class="mr-2" />
                                        <span class="text-sm font-medium">{{ $currentVideo['likes'] ?? '45' }}</span>
                                    </button>
                                    <button class="flex items-center px-4 py-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600 transition-colors">
                                        <x-icon name="bookmark" size="sm" class="mr-2" />
                                        <span class="text-sm font-medium">Salvar</span>
                                    </button>
                                    <button class="flex items-center px-4 py-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600 transition-colors">
                                        <x-icon name="share" size="sm" class="mr-2" />
                                        <span class="text-sm font-medium">Compartilhar</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Progresso do Vídeo -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">Progresso da Aula</span>
                                    <span class="font-bold text-hcp-500">{{ $currentVideo['progress'] ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-hcp-500 to-hcp-600 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $currentVideo['progress'] ?? 0 }}%"></div>
                                </div>
                            </div>
                            
                            <!-- Navegação entre Vídeos -->
                            <div class="flex items-center justify-between">
                                @if($currentVideoIndex > 0)
                                    <a href="{{ route('modules.show.video', ['module' => $module['id'], 'videoIndex' => $currentVideoIndex - 1]) }}" 
                                       class="flex items-center px-4 py-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600 transition-colors">
                                        <x-icon name="chevron-left" size="sm" class="mr-2" />
                                        <span class="text-sm font-medium">Anterior</span>
                                    </a>
                                @else
                                    <button class="flex items-center px-4 py-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg opacity-50 cursor-not-allowed" disabled>
                                        <x-icon name="chevron-left" size="sm" class="mr-2" />
                                        <span class="text-sm font-medium">Anterior</span>
                                    </button>
                                @endif
                                
                                <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    Aula {{ $currentVideoIndex + 1 }} de {{ count($module['videos']) }}
                                </div>
                                
                                @if($currentVideoIndex < count($module['videos']) - 1)
                                    <a href="{{ route('modules.show.video', ['module' => $module['id'], 'videoIndex' => $currentVideoIndex + 1]) }}" 
                                       class="flex items-center px-4 py-2 bg-hcp-gradient text-white rounded-lg hover:shadow-hcp-lg transition-all duration-200">
                                        <span class="text-sm font-medium">Próxima</span>
                                        <x-icon name="chevron-right" size="sm" class="ml-2" />
                                    </a>
                                @else
                                    <button class="flex items-center px-4 py-2 bg-hcp-gradient text-white rounded-lg opacity-50 cursor-not-allowed" disabled>
                                        <span class="text-sm font-medium">Próxima</span>
                                        <x-icon name="chevron-right" size="sm" class="ml-2" />
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Descrição do Curso -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-2xl shadow-lg p-6 border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <h2 class="text-xl font-bold text-hcp-secondary-900 dark:text-white mb-4 flex items-center">
                            <x-icon name="info" size="sm" class="mr-2 text-hcp-500" />
                            Sobre este Curso
                        </h2>
                        <div class="prose prose-hcp dark:prose-invert max-w-none">
                            <p class="text-hcp-secondary-600 dark:text-hcp-secondary-400 leading-relaxed">
                                {{ $module['description'] }}
                            </p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 p-4 bg-hcp-secondary-50 dark:bg-hcp-secondary-700 rounded-lg">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-hcp-500">{{ count($module['videos']) }}</div>
                                    <div class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Aulas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-hcp-500">{{ $module['total_duration'] }}</div>
                                    <div class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Duração Total</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-hcp-500">{{ $module['difficulty'] }}</div>
                                    <div class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Nível</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-hcp-500">{{ $module['points'] }}</div>
                                    <div class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">Pontos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar - Lista de Vídeos (1/4) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-2xl shadow-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700 sticky top-6">
                        <div class="p-6 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <h3 class="text-lg font-bold text-hcp-secondary-900 dark:text-white flex items-center">
                                <x-icon name="list" size="sm" class="mr-2 text-hcp-500" />
                                Conteúdo do Curso
                            </h3>
                            <div class="mt-2 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                {{ count($module['videos']) }} aulas • {{ $module['total_duration'] }}
                            </div>
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto">
                            @foreach($module['videos'] as $index => $video)
                                <a href="{{ route('modules.show.video', ['module' => $module['id'], 'videoIndex' => $index]) }}" 
                                   class="video-item block p-4 border-b border-hcp-secondary-100 dark:border-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 transition-colors {{ $index === $currentVideoIndex ? 'bg-hcp-50 dark:bg-hcp-secondary-700 border-l-4 border-l-hcp-500' : '' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="relative flex-shrink-0">
                                            <img src="https://img.youtube.com/vi/{{ $video['youtube_id'] }}/mqdefault.jpg" 
                                                 alt="{{ $video['title'] }}"
                                                 class="w-16 h-12 object-cover rounded">
                                            @if($index === $currentVideoIndex)
                                                <div class="absolute inset-0 bg-hcp-500/20 rounded flex items-center justify-center">
                                                    <div class="w-6 h-6 bg-hcp-500 rounded-full flex items-center justify-center">
                                                        <x-icon name="play" size="xs" class="text-white ml-0.5" />
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white line-clamp-2 {{ $index === $currentVideoIndex ? 'text-hcp-500' : '' }}">
                                                {{ $video['title'] }}
                                            </h4>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                    {{ $video['duration'] }}
                                                </span>
                                                @if(isset($video['completed']) && $video['completed'])
                                                    <div class="w-4 h-4 bg-hcp-success-500 rounded-full flex items-center justify-center">
                                                        <x-icon name="check" size="xs" class="text-white" />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                        <!-- Progresso Geral do Curso -->
                        <div class="p-6 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300">Progresso do Curso</span>
                                <span class="font-bold text-hcp-500">{{ $module['progress'] }}%</span>
                            </div>
                            <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-700 rounded-full h-3">
                                <div class="bg-gradient-to-r from-hcp-500 to-hcp-600 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ $module['progress'] }}%"></div>
                            </div>
                            <div class="mt-2 text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                {{ $completedVideos }} de {{ count($module['videos']) }} aulas concluídas
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Navegação por teclado entre vídeos
        document.addEventListener('keydown', function(e) {
            const currentVideoIndex = {{ $currentVideoIndex }};
            const totalVideos = {{ count($module['videos']) }};
            const moduleId = {{ $module['id'] }};
            
            if (e.key === 'ArrowLeft' && currentVideoIndex > 0) {
                // Navegar para vídeo anterior
                window.location.href = `/modules/${moduleId}/video/${currentVideoIndex - 1}`;
            } else if (e.key === 'ArrowRight' && currentVideoIndex < totalVideos - 1) {
                // Navegar para próximo vídeo
                window.location.href = `/modules/${moduleId}/video/${currentVideoIndex + 1}`;
            }
        });
        
        // Adicionar indicador visual quando teclas são pressionadas
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                // Adicionar feedback visual
                const body = document.body;
                body.style.transition = 'opacity 0.1s';
                body.style.opacity = '0.9';
                setTimeout(() => {
                    body.style.opacity = '1';
                }, 100);
            }
        });
    </script>
    @endpush
</x-layouts.employee>