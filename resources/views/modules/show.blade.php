<x-layouts.employee title="Curso - {{ $module['title'] }}">
    <div class="min-h-screen bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('modules.index') }}" class="inline-flex items-center text-sm font-medium text-hcp-secondary-700 hover:text-hcp-500 dark:text-hcp-secondary-400 dark:hover:text-white">
                            <x-icon name="home" size="sm" class="mr-2" />
                            Módulos
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <x-icon name="chevron-right" size="sm" class="text-hcp-secondary-400" />
                            <span class="ml-1 text-sm font-medium text-hcp-secondary-500 dark:text-hcp-secondary-400">{{ $module['title'] }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Conteúdo Principal (3/4) -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Player de Vídeo -->
                    <div class="bg-white dark:bg-hcp-secondary-800 rounded-2xl shadow-lg overflow-hidden border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="aspect-video bg-black relative">
                            <iframe 
                                id="youtube-player"
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/{{ $currentVideo['youtube_id'] }}?autoplay=0&rel=0&modestbranding=1"
                                title="{{ $currentVideo['title'] }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        
                        <!-- Controles e Informações do Vídeo -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white mb-2">
                                        {{ $currentVideo['title'] }}
                                    </h1>
                                    <div class="flex items-center space-x-4 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                        <span class="flex items-center">
                                            <x-icon name="clock" size="xs" class="mr-1" />
                                            {{ $currentVideo['duration'] }}
                                        </span>
                                        <span class="flex items-center">
                                            <x-icon name="eye" size="xs" class="mr-1" />
                                            {{ $currentVideo['views'] ?? '1.2K' }} visualizações
                                        </span>
                                        <span class="flex items-center">
                                            <x-icon name="calendar" size="xs" class="mr-1" />
                                            {{ $currentVideo['published'] ?? 'há 2 dias' }}
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
                                <button class="flex items-center px-4 py-2 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 text-hcp-secondary-700 dark:text-hcp-secondary-300 rounded-lg hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $currentVideoIndex <= 0 ? 'disabled' : '' }}>
                                    <x-icon name="chevron-left" size="sm" class="mr-2" />
                                    <span class="text-sm font-medium">Anterior</span>
                                </button>
                                
                                <div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                    Aula {{ $currentVideoIndex + 1 }} de {{ count($module['videos']) }}
                                </div>
                                
                                <button class="flex items-center px-4 py-2 bg-hcp-gradient text-white rounded-lg hover:shadow-hcp-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $currentVideoIndex >= count($module['videos']) - 1 ? 'disabled' : '' }}>
                                    <span class="text-sm font-medium">Próxima</span>
                                    <x-icon name="chevron-right" size="sm" class="ml-2" />
                                </button>
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
                                <div class="video-item p-4 border-b border-hcp-secondary-100 dark:border-hcp-secondary-700 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors {{ $index === $currentVideoIndex ? 'bg-hcp-50 dark:bg-hcp-secondary-700 border-l-4 border-l-hcp-500' : '' }}"
                                     onclick="changeVideo({{ $index }})">
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
                                </div>
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
        let currentVideoIndex = {{ $currentVideoIndex }};
        const videos = @json($module['videos']);
        
        function changeVideo(index) {
            if (index >= 0 && index < videos.length) {
                currentVideoIndex = index;
                const video = videos[index];
                
                // Atualizar iframe
                const iframe = document.getElementById('youtube-player');
                iframe.src = `https://www.youtube.com/embed/${video.youtube_id}?autoplay=1&rel=0&modestbranding=1`;
                
                // Atualizar título
                const titleElement = document.querySelector('h1');
                if (titleElement) {
                    titleElement.textContent = video.title;
                }
                
                // Atualizar informações do vídeo
                const infoContainer = document.querySelector('.flex.items-center.space-x-4');
                if (infoContainer) {
                    infoContainer.innerHTML = `
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            ${video.duration}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            ${video.views || '1.2K'} visualizações
                        </span>
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            ${video.published || 'há 2 dias'}
                        </span>
                    `;
                }
                
                // Atualizar progresso do vídeo
                const progressBar = document.querySelector('.bg-gradient-to-r.from-hcp-500.to-hcp-600');
                const progressText = document.querySelector('.font-bold.text-hcp-500');
                if (progressBar) {
                    progressBar.style.width = `${video.progress || 0}%`;
                }
                if (progressText) {
                    progressText.textContent = `${video.progress || 0}%`;
                }
                
                // Atualizar indicador de aula atual
                document.querySelectorAll('.video-item').forEach((item, i) => {
                    if (i === index) {
                        item.classList.add('bg-hcp-50', 'dark:bg-hcp-secondary-700', 'border-l-4', 'border-l-hcp-500');
                        // Adicionar ícone de play no vídeo atual
                        const playIcon = item.querySelector('.absolute.inset-0');
                        if (playIcon) {
                            playIcon.style.display = 'flex';
                        }
                    } else {
                        item.classList.remove('bg-hcp-50', 'dark:bg-hcp-secondary-700', 'border-l-4', 'border-l-hcp-500');
                        // Remover ícone de play dos outros vídeos
                        const playIcon = item.querySelector('.absolute.inset-0');
                        if (playIcon) {
                            playIcon.style.display = 'none';
                        }
                    }
                });
                
                // Atualizar contador de aula
                const lessonCounter = document.querySelector('.text-sm.text-hcp-secondary-600.dark\\:text-hcp-secondary-400');
                if (lessonCounter) {
                    lessonCounter.textContent = `Aula ${index + 1} de ${videos.length}`;
                }
                
                // Atualizar botões de navegação
                const prevBtn = document.querySelector('button:first-of-type');
                const nextBtn = document.querySelector('.bg-hcp-gradient');
                
                if (prevBtn) {
                    prevBtn.disabled = index <= 0;
                    prevBtn.onclick = () => changeVideo(index - 1);
                }
                if (nextBtn) {
                    nextBtn.disabled = index >= videos.length - 1;
                    nextBtn.onclick = () => changeVideo(index + 1);
                }
            }
        }
        
        // Inicializar quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar botões de navegação
            const prevBtn = document.querySelector('button:first-of-type');
            const nextBtn = document.querySelector('.bg-hcp-gradient');
            
            if (prevBtn) {
                prevBtn.onclick = () => {
                    if (currentVideoIndex > 0) {
                        changeVideo(currentVideoIndex - 1);
                    }
                };
            }
            
            if (nextBtn) {
                nextBtn.onclick = () => {
                    if (currentVideoIndex < videos.length - 1) {
                        changeVideo(currentVideoIndex + 1);
                    }
                };
            }
        });
        
        // Navegação por teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' && currentVideoIndex > 0) {
                changeVideo(currentVideoIndex - 1);
            } else if (e.key === 'ArrowRight' && currentVideoIndex < videos.length - 1) {
                changeVideo(currentVideoIndex + 1);
            }
        });
    </script>
    @endpush
</x-layouts.employee>