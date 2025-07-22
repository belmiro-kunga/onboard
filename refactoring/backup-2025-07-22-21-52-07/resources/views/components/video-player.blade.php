{{-- Player de Vídeo Customizado HCP --}}
@props([
    'content',
    'module' => null,
    'autoplay' => false,
    'showControls' => true,
    'showTranscript' => true,
    'showNotes' => true,
])

@php
$playerId = 'hcp-player-' . uniqid();
$videoUrl = $content->getContentUrl();
$thumbnailUrl = $content->getThumbnailUrl();
$interactiveMarkers = $content->getInteractiveMarkers();
$transcript = $content->transcript ?? [];
@endphp

<div class="hcp-video-player-container bg-black rounded-hcp-xl overflow-hidden shadow-hcp-lg" 
     data-player-id="{{ $playerId }}"
     data-content-id="{{ $content->id }}"
     data-module-id="{{ $module?->id }}">
     
    <!-- Player Principal -->
    <div class="relative aspect-video bg-black">
        <!-- Vídeo -->
        <video 
            id="{{ $playerId }}"
            class="w-full h-full object-cover"
            poster="{{ $thumbnailUrl }}"
            preload="metadata"
            {{ $autoplay ? 'autoplay' : '' }}
            {{ $showControls ? '' : 'controls' }}
            playsinline
            webkit-playsinline>
            <source src="{{ $videoUrl }}" type="video/mp4">
            <p class="text-white p-4">
                Seu navegador não suporta reprodução de vídeo. 
                <a href="{{ $videoUrl }}" class="text-hcp-500 underline">Baixe o vídeo</a>
            </p>
        </video>

        <!-- Overlay de Loading -->
        <div id="{{ $playerId }}-loading" class="absolute inset-0 bg-black/50 flex items-center justify-center">
            <div class="text-center text-white">
                <div class="w-16 h-16 border-4 border-white/30 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-sm">Carregando vídeo...</p>
            </div>
        </div>

        <!-- Controles Customizados -->
        @if($showControls)
        <div id="{{ $playerId }}-controls" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 opacity-0 transition-opacity duration-300">
            <!-- Barra de Progresso -->
            <div class="mb-4">
                <div class="relative">
                    <!-- Progress Track -->
                    <div class="w-full h-2 bg-white/20 rounded-full cursor-pointer" id="{{ $playerId }}-progress-track">
                        <!-- Buffer Progress -->
                        <div id="{{ $playerId }}-buffer" class="absolute top-0 left-0 h-full bg-white/30 rounded-full"></div>
                        <!-- Play Progress -->
                        <div id="{{ $playerId }}-progress" class="absolute top-0 left-0 h-full bg-hcp-500 rounded-full"></div>
                        <!-- Scrubber -->
                        <div id="{{ $playerId }}-scrubber" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-hcp-500 rounded-full shadow-lg opacity-0 transition-opacity duration-200"></div>
                    </div>
                    
                    <!-- Marcadores Interativos -->
                    @foreach($interactiveMarkers as $marker)
                    <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-hcp-accent-500 rounded-full cursor-pointer hover:scale-125 transition-transform duration-200"
                         style="left: {{ ($marker['time'] / ($content->duration ?: 1)) * 100 }}%"
                         data-time="{{ $marker['time'] }}"
                         data-marker-id="{{ $marker['id'] }}"
                         title="{{ $marker['title'] }}">
                    </div>
                    @endforeach
                </div>
                
                <!-- Time Display -->
                <div class="flex justify-between text-xs text-white/80 mt-1">
                    <span id="{{ $playerId }}-current-time">0:00</span>
                    <span id="{{ $playerId }}-duration">{{ $content->formatted_duration }}</span>
                </div>
            </div>

            <!-- Controles Principais -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <!-- Play/Pause -->
                    <button id="{{ $playerId }}-play-pause" class="w-12 h-12 bg-hcp-500 hover:bg-hcp-400 rounded-full flex items-center justify-center transition-colors duration-200 touch-target">
                        <x-icon name="play" class="text-white ml-1" id="{{ $playerId }}-play-icon" />
                        <x-icon name="pause" class="text-white hidden" id="{{ $playerId }}-pause-icon" />
                    </button>

                    <!-- Volume -->
                    <div class="flex items-center space-x-2">
                        <button id="{{ $playerId }}-mute" class="text-white hover:text-hcp-400 transition-colors duration-200 touch-target">
                            <x-icon name="volume-2" size="sm" id="{{ $playerId }}-volume-icon" />
                            <x-icon name="volume-x" size="sm" class="hidden" id="{{ $playerId }}-mute-icon" />
                        </button>
                        <div class="w-20 h-1 bg-white/20 rounded-full cursor-pointer hidden md:block" id="{{ $playerId }}-volume-track">
                            <div id="{{ $playerId }}-volume-progress" class="h-full bg-white rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <!-- Speed -->
                    <div class="relative hidden md:block">
                        <button id="{{ $playerId }}-speed" class="text-white hover:text-hcp-400 text-sm font-medium transition-colors duration-200 touch-target">
                            1x
                        </button>
                        <div id="{{ $playerId }}-speed-menu" class="absolute bottom-full left-0 mb-2 bg-black/90 rounded-hcp p-2 hidden">
                            <div class="space-y-1">
                                <button class="block w-full text-left text-white hover:text-hcp-400 text-sm px-2 py-1 rounded transition-colors" data-speed="0.5">0.5x</button>
                                <button class="block w-full text-left text-white hover:text-hcp-400 text-sm px-2 py-1 rounded transition-colors" data-speed="0.75">0.75x</button>
                                <button class="block w-full text-left text-white hover:text-hcp-400 text-sm px-2 py-1 rounded transition-colors" data-speed="1">1x</button>
                                <button class="block w-full text-left text-white hover:text-hcp-400 text-sm px-2 py-1 rounded transition-colors" data-speed="1.25">1.25x</button>
                                <button class="block w-full text-left text-white hover:text-hcp-400 text-sm px-2 py-1 rounded transition-colors" data-speed="1.5">1.5x</button>
                                <button class="block w-full text-left text-white hover:text-hcp-400 text-sm px-2 py-1 rounded transition-colors" data-speed="2">2x</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Transcript Toggle -->
                    @if($showTranscript && $transcript)
                    <button id="{{ $playerId }}-transcript" class="text-white hover:text-hcp-400 transition-colors duration-200 touch-target" title="Legendas">
                        <x-icon name="type" size="sm" />
                    </button>
                    @endif

                    <!-- Notes Toggle -->
                    @if($showNotes && $content->notes_enabled)
                    <button id="{{ $playerId }}-notes" class="text-white hover:text-hcp-400 transition-colors duration-200 touch-target" title="Notas">
                        <x-icon name="edit" size="sm" />
                    </button>
                    @endif

                    <!-- Fullscreen -->
                    <button id="{{ $playerId }}-fullscreen" class="text-white hover:text-hcp-400 transition-colors duration-200 touch-target">
                        <x-icon name="maximize" size="sm" id="{{ $playerId }}-fullscreen-icon" />
                        <x-icon name="minimize" size="sm" class="hidden" id="{{ $playerId }}-minimize-icon" />
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Marca HCP -->
        <div class="absolute top-4 right-4 bg-black/50 rounded-hcp px-3 py-1">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-hcp-gradient rounded flex items-center justify-center">
                    <span class="text-white font-bold text-xs">HCP</span>
                </div>
                <span class="text-white text-sm font-medium">Onboarding</span>
            </div>
        </div>

        <!-- Marcador Interativo Popup -->
        <div id="{{ $playerId }}-marker-popup" class="absolute bg-black/90 text-white p-4 rounded-hcp-lg max-w-sm hidden z-10">
            <div class="flex items-start justify-between mb-2">
                <h4 class="font-semibold text-sm" id="{{ $playerId }}-marker-title"></h4>
                <button class="text-white/60 hover:text-white ml-2" id="{{ $playerId }}-marker-close">
                    <x-icon name="x" size="xs" />
                </button>
            </div>
            <div class="text-sm text-white/80" id="{{ $playerId }}-marker-content"></div>
        </div>
    </div>

    <!-- Painel Inferior -->
    <div class="bg-hcp-secondary-50 dark:bg-hcp-secondary-800 p-4">
        <!-- Título e Informações -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-1">
                    {{ $content->title }}
                </h3>
                @if($module)
                <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                    {{ $module->title }} • {{ $content->formatted_duration }}
                </p>
                @endif
            </div>
            
            <!-- Ações -->
            <div class="flex items-center space-x-2 ml-4">
                <button class="p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors rounded-hcp hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 touch-target" title="Favoritar">
                    <x-icon name="heart" size="sm" />
                </button>
                <button class="p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors rounded-hcp hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 touch-target" title="Compartilhar">
                    <x-icon name="share" size="sm" />
                </button>
                <button class="p-2 text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 transition-colors rounded-hcp hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 touch-target" title="Download">
                    <x-icon name="download" size="sm" />
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-hcp-secondary-200 dark:border-hcp-secondary-600 mb-4">
            <nav class="flex space-x-6">
                @if($showTranscript && $transcript)
                <button class="py-2 px-1 border-b-2 border-transparent text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 hover:border-hcp-300 dark:hover:border-hcp-500 transition-colors duration-200 tab-button active" data-tab="transcript">
                    Transcrição
                </button>
                @endif
                
                @if($showNotes && $content->notes_enabled)
                <button class="py-2 px-1 border-b-2 border-transparent text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 hover:border-hcp-300 dark:hover:border-hcp-500 transition-colors duration-200 tab-button" data-tab="notes">
                    Minhas Notas
                </button>
                @endif
                
                @if($interactiveMarkers)
                <button class="py-2 px-1 border-b-2 border-transparent text-sm font-medium text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-500 dark:hover:text-hcp-400 hover:border-hcp-300 dark:hover:border-hcp-500 transition-colors duration-200 tab-button" data-tab="markers">
                    Marcadores ({{ count($interactiveMarkers) }})
                </button>
                @endif
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Transcrição -->
            @if($showTranscript && $transcript)
            <div id="transcript-tab" class="tab-panel active">
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Buscar na transcrição..." 
                               class="w-full pl-10 pr-4 py-2 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp-lg bg-white dark:bg-hcp-secondary-700 text-hcp-secondary-900 dark:text-white focus:ring-2 focus:ring-hcp-500 focus:border-transparent"
                               id="{{ $playerId }}-transcript-search">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-hcp-secondary-400" size="sm" />
                    </div>
                </div>
                
                <div class="max-h-64 overflow-y-auto space-y-2" id="{{ $playerId }}-transcript-content">
                    @foreach($transcript as $segment)
                    <div class="transcript-segment p-3 rounded-hcp hover:bg-hcp-secondary-100 dark:hover:bg-hcp-secondary-700 cursor-pointer transition-colors duration-200"
                         data-start="{{ $segment['start'] ?? 0 }}"
                         data-end="{{ $segment['end'] ?? 0 }}">
                        <div class="flex items-start space-x-3">
                            <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 font-mono mt-1">
                                {{ gmdate('i:s', $segment['start'] ?? 0) }}
                            </span>
                            <p class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 flex-1">
                                {{ $segment['text'] ?? '' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Notas -->
            @if($showNotes && $content->notes_enabled)
            <div id="notes-tab" class="tab-panel hidden">
                <div class="space-y-4">
                    <!-- Adicionar Nova Nota -->
                    <div class="bg-hcp-secondary-100 dark:bg-hcp-secondary-700 p-4 rounded-hcp-lg">
                        <textarea 
                            placeholder="Adicione uma nota sobre este momento do vídeo..."
                            class="w-full p-3 border border-hcp-secondary-300 dark:border-hcp-secondary-600 rounded-hcp bg-white dark:bg-hcp-secondary-800 text-hcp-secondary-900 dark:text-white resize-none focus:ring-2 focus:ring-hcp-500 focus:border-transparent"
                            rows="3"
                            id="{{ $playerId }}-note-input"></textarea>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                Tempo atual: <span id="{{ $playerId }}-note-time">0:00</span>
                            </span>
                            <button class="bg-hcp-500 hover:bg-hcp-400 text-white px-4 py-2 rounded-hcp text-sm font-medium transition-colors duration-200">
                                Salvar Nota
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Notas -->
                    <div id="{{ $playerId }}-notes-list" class="space-y-3">
                        <!-- Notas serão carregadas via JavaScript -->
                    </div>
                </div>
            </div>
            @endif

            <!-- Marcadores -->
            @if($interactiveMarkers)
            <div id="markers-tab" class="tab-panel hidden">
                <div class="space-y-3">
                    @foreach($interactiveMarkers as $marker)
                    <div class="flex items-start space-x-3 p-3 bg-hcp-secondary-100 dark:bg-hcp-secondary-700 rounded-hcp-lg cursor-pointer hover:bg-hcp-secondary-200 dark:hover:bg-hcp-secondary-600 transition-colors duration-200"
                         data-time="{{ $marker['time'] }}">
                        <div class="w-8 h-8 bg-hcp-accent-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <x-icon name="bookmark" size="xs" class="text-white" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-medium text-hcp-secondary-900 dark:text-white text-sm">
                                    {{ $marker['title'] }}
                                </h4>
                                <span class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 font-mono">
                                    {{ gmdate('i:s', $marker['time']) }}
                                </span>
                            </div>
                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                                {{ $marker['content'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeVideoPlayer('{{ $playerId }}');
});
</script>
@endpush