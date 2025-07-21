// YouTube Background Video Controller
(function() {
    'use strict';
    
    // Configurações do vídeo
    const VIDEO_ID = 'yD0Klnia320';
    const START_TIME = 16; // Começar no segundo 16
    
    let player;
    let isInitialized = false;
    
    // Função para inicializar o player do YouTube
    function initYouTubePlayer() {
        if (isInitialized) return;
        
        // Verificar se o elemento existe
        const playerElement = document.getElementById('youtube-player');
        if (!playerElement) {
            console.log('YouTube player element not found');
            return;
        }
        
        // Verificar se a API do YouTube está disponível
        if (typeof YT === 'undefined' || !YT.Player) {
            console.log('YouTube API not loaded, retrying...');
            setTimeout(initYouTubePlayer, 1000);
            return;
        }
        
        try {
            player = new YT.Player('youtube-player', {
                height: '100%',
                width: '100%',
                videoId: VIDEO_ID,
                playerVars: {
                    'autoplay': 1,
                    'controls': 0,
                    'disablekb': 1,
                    'enablejsapi': 1,
                    'fs': 0,
                    'iv_load_policy': 3,
                    'loop': 1,
                    'modestbranding': 1,
                    'playsinline': 1,
                    'rel': 0,
                    'showinfo': 0,
                    'start': START_TIME,
                    'mute': 1,
                    'playlist': VIDEO_ID // Necessário para loop
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': onPlayerError
                }
            });
            
            isInitialized = true;
            console.log('YouTube player initialized successfully');
            
        } catch (error) {
            console.error('Error initializing YouTube player:', error);
            showFallback();
        }
    }
    
    // Função chamada quando o player está pronto
    function onPlayerReady(event) {
        console.log('YouTube video loaded successfully');
        event.target.playVideo();
        
        // Track video load
        if (typeof trackEvent === 'function') {
            trackEvent('youtube_video_loaded', { 
                video_id: VIDEO_ID,
                start_time: START_TIME
            });
        }
        
        // Esconder fallback se estiver visível
        const fallback = document.getElementById('video-fallback');
        if (fallback) {
            fallback.style.display = 'none';
        }
    }
    
    // Função chamada quando o estado do player muda
    function onPlayerStateChange(event) {
        // Reiniciar vídeo quando terminar para manter loop
        if (event.data === YT.PlayerState.ENDED) {
            event.target.seekTo(START_TIME);
            event.target.playVideo();
        }
    }
    
    // Função chamada quando há erro no player
    function onPlayerError(event) {
        console.log('YouTube video failed to load, using fallback');
        showFallback();
    }
    
    // Função para mostrar fallback
    function showFallback() {
        const fallback = document.getElementById('video-fallback');
        if (fallback) {
            fallback.style.display = 'block';
        }
    }
    
    // Função para pausar vídeo
    function pauseVideo() {
        if (player && typeof player.pauseVideo === 'function') {
            player.pauseVideo();
        }
    }
    
    // Função para reproduzir vídeo
    function playVideo() {
        if (player && typeof player.playVideo === 'function') {
            player.playVideo();
        }
    }
    
    // Pausar vídeo quando página não está visível (economia de bateria)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            pauseVideo();
        } else {
            playVideo();
        }
    });
    
    // Otimização para mobile - pausar vídeo em conexões lentas
    if ('connection' in navigator) {
        if (navigator.connection.effectiveType === 'slow-2g' || 
            navigator.connection.effectiveType === '2g') {
            console.log('Slow connection detected, using fallback');
            showFallback();
        }
    }
    
    // Inicializar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initYouTubePlayer);
    } else {
        initYouTubePlayer();
    }
    
    // Inicializar quando YouTube API estiver pronta
    window.onYouTubeIframeAPIReady = function() {
        initYouTubePlayer();
    };
    
    // Expor funções globalmente
    window.YouTubeBackground = {
        init: initYouTubePlayer,
        pause: pauseVideo,
        play: playVideo,
        showFallback: showFallback
    };
    
})(); 