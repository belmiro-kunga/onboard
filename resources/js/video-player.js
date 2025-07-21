/**
 * HCP Video Player - Sistema de Player Customizado
 * Funcionalidades: Controles customizados, marcadores interativos, notas, transcrições
 */

class HCPVideoPlayer {
    constructor(playerId) {
        this.playerId = playerId;
        this.video = document.getElementById(playerId);
        this.container = document.querySelector(`[data-player-id="${playerId}"]`);
        this.contentId = this.container.dataset.contentId;
        this.moduleId = this.container.dataset.moduleId;
        
        this.isPlaying = false;
        this.isMuted = false;
        this.currentVolume = 1;
        this.currentSpeed = 1;
        this.isFullscreen = false;
        this.currentTime = 0;
        this.duration = 0;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupControls();
        this.setupTabs();
        this.setupKeyboardShortcuts();
        this.loadUserProgress();
        this.trackProgress();
    }

    setupEventListeners() {
        // Video events
        this.video.addEventListener('loadedmetadata', () => {
            this.duration = this.video.duration;
            this.updateDurationDisplay();
            this.hideLoading();
        });

        this.video.addEventListener('timeupdate', () => {
            this.currentTime = this.video.currentTime;
            this.updateProgress();
            this.updateTimeDisplay();
            this.checkMarkers();
            this.saveProgress();
        });

        this.video.addEventListener('play', () => {
            this.isPlaying = true;
            this.updatePlayButton();
        });

        this.video.addEventListener('pause', () => {
            this.isPlaying = false;
            this.updatePlayButton();
        });

        this.video.addEventListener('ended', () => {
            this.onVideoEnded();
        });

        this.video.addEventListener('error', (e) => {
            this.handleError(e);
        });

        // Progress events
        this.video.addEventListener('progress', () => {
            this.updateBufferProgress();
        });

        // Mouse events for controls
        this.container.addEventListener('mouseenter', () => {
            this.showControls();
        });

        this.container.addEventListener('mouseleave', () => {
            this.hideControls();
        });

        // Touch events for mobile
        this.container.addEventListener('touchstart', () => {
            this.toggleControls();
        });
    }

    setupControls() {
        // Play/Pause button
        const playPauseBtn = document.getElementById(`${this.playerId}-play-pause`);
        playPauseBtn?.addEventListener('click', () => {
            this.togglePlayPause();
        });

        // Progress bar
        const progressTrack = document.getElementById(`${this.playerId}-progress-track`);
        progressTrack?.addEventListener('click', (e) => {
            this.seekTo(e);
        });

        // Volume controls
        const muteBtn = document.getElementById(`${this.playerId}-mute`);
        muteBtn?.addEventListener('click', () => {
            this.toggleMute();
        });

        const volumeTrack = document.getElementById(`${this.playerId}-volume-track`);
        volumeTrack?.addEventListener('click', (e) => {
            this.setVolume(e);
        });

        // Speed controls
        const speedBtn = document.getElementById(`${this.playerId}-speed`);
        const speedMenu = document.getElementById(`${this.playerId}-speed-menu`);
        
        speedBtn?.addEventListener('click', () => {
            speedMenu?.classList.toggle('hidden');
        });

        speedMenu?.querySelectorAll('[data-speed]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.setSpeed(parseFloat(e.target.dataset.speed));
                speedMenu.classList.add('hidden');
            });
        });

        // Fullscreen
        const fullscreenBtn = document.getElementById(`${this.playerId}-fullscreen`);
        fullscreenBtn?.addEventListener('click', () => {
            this.toggleFullscreen();
        });

        // Interactive markers
        this.setupMarkers();
    }

    setupMarkers() {
        const markers = this.container.querySelectorAll('[data-marker-id]');
        markers.forEach(marker => {
            marker.addEventListener('click', () => {
                const time = parseFloat(marker.dataset.time);
                this.seekToTime(time);
                this.showMarkerPopup(marker);
            });
        });

        // Marker popup close
        const markerClose = document.getElementById(`${this.playerId}-marker-close`);
        markerClose?.addEventListener('click', () => {
            this.hideMarkerPopup();
        });
    }

    setupTabs() {
        const tabButtons = this.container.querySelectorAll('.tab-button');
        const tabPanels = this.container.querySelectorAll('.tab-panel');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.dataset.tab;
                
                // Update active tab button
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'border-hcp-500', 'text-hcp-500');
                    btn.classList.add('border-transparent');
                });
                
                button.classList.add('active', 'border-hcp-500', 'text-hcp-500');
                button.classList.remove('border-transparent');

                // Update active tab panel
                tabPanels.forEach(panel => {
                    panel.classList.add('hidden');
                    panel.classList.remove('active');
                });

                const activePanel = document.getElementById(`${tabName}-tab`);
                if (activePanel) {
                    activePanel.classList.remove('hidden');
                    activePanel.classList.add('active');
                }
            });
        });

        // Setup transcript search
        this.setupTranscriptSearch();
        
        // Setup notes functionality
        this.setupNotes();
    }

    setupTranscriptSearch() {
        const searchInput = document.getElementById(`${this.playerId}-transcript-search`);
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            this.searchTranscript(e.target.value);
        });

        // Transcript segment clicks
        const segments = this.container.querySelectorAll('.transcript-segment');
        segments.forEach(segment => {
            segment.addEventListener('click', () => {
                const startTime = parseFloat(segment.dataset.start);
                this.seekToTime(startTime);
            });
        });
    }

    setupNotes() {
        const noteInput = document.getElementById(`${this.playerId}-note-input`);
        const saveBtn = this.container.querySelector('#notes-tab button');
        
        if (noteInput && saveBtn) {
            saveBtn.addEventListener('click', () => {
                this.saveNote(noteInput.value);
                noteInput.value = '';
            });

            // Update note time display
            setInterval(() => {
                const noteTime = document.getElementById(`${this.playerId}-note-time`);
                if (noteTime) {
                    noteTime.textContent = this.formatTime(this.currentTime);
                }
            }, 1000);
        }

        this.loadNotes();
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (!this.isPlayerFocused()) return;

            switch(e.code) {
                case 'Space':
                    e.preventDefault();
                    this.togglePlayPause();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    this.seekRelative(-10);
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.seekRelative(10);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    this.adjustVolume(0.1);
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    this.adjustVolume(-0.1);
                    break;
                case 'KeyM':
                    e.preventDefault();
                    this.toggleMute();
                    break;
                case 'KeyF':
                    e.preventDefault();
                    this.toggleFullscreen();
                    break;
            }
        });
    }

    // Control Methods
    togglePlayPause() {
        if (this.isPlaying) {
            this.video.pause();
        } else {
            this.video.play();
        }
    }

    updatePlayButton() {
        const playIcon = document.getElementById(`${this.playerId}-play-icon`);
        const pauseIcon = document.getElementById(`${this.playerId}-pause-icon`);
        
        if (this.isPlaying) {
            playIcon?.classList.add('hidden');
            pauseIcon?.classList.remove('hidden');
        } else {
            playIcon?.classList.remove('hidden');
            pauseIcon?.classList.add('hidden');
        }
    }

    seekTo(e) {
        const progressTrack = document.getElementById(`${this.playerId}-progress-track`);
        const rect = progressTrack.getBoundingClientRect();
        const percent = (e.clientX - rect.left) / rect.width;
        const time = percent * this.duration;
        this.seekToTime(time);
    }

    seekToTime(time) {
        this.video.currentTime = Math.max(0, Math.min(time, this.duration));
    }

    seekRelative(seconds) {
        this.seekToTime(this.currentTime + seconds);
    }

    updateProgress() {
        const progress = document.getElementById(`${this.playerId}-progress`);
        const scrubber = document.getElementById(`${this.playerId}-scrubber`);
        
        if (this.duration > 0) {
            const percent = (this.currentTime / this.duration) * 100;
            
            if (progress) {
                progress.style.width = `${percent}%`;
            }
            
            if (scrubber) {
                scrubber.style.left = `${percent}%`;
            }
        }
    }

    updateBufferProgress() {
        const buffer = document.getElementById(`${this.playerId}-buffer`);
        if (!buffer || !this.video.buffered.length) return;

        const bufferedEnd = this.video.buffered.end(this.video.buffered.length - 1);
        const percent = (bufferedEnd / this.duration) * 100;
        buffer.style.width = `${percent}%`;
    }

    updateTimeDisplay() {
        const currentTimeEl = document.getElementById(`${this.playerId}-current-time`);
        if (currentTimeEl) {
            currentTimeEl.textContent = this.formatTime(this.currentTime);
        }
    }

    updateDurationDisplay() {
        const durationEl = document.getElementById(`${this.playerId}-duration`);
        if (durationEl) {
            durationEl.textContent = this.formatTime(this.duration);
        }
    }

    toggleMute() {
        if (this.isMuted) {
            this.video.volume = this.currentVolume;
            this.isMuted = false;
        } else {
            this.currentVolume = this.video.volume;
            this.video.volume = 0;
            this.isMuted = true;
        }
        this.updateVolumeIcon();
    }

    setVolume(e) {
        const volumeTrack = document.getElementById(`${this.playerId}-volume-track`);
        const rect = volumeTrack.getBoundingClientRect();
        const percent = (e.clientX - rect.left) / rect.width;
        const volume = Math.max(0, Math.min(1, percent));
        
        this.video.volume = volume;
        this.currentVolume = volume;
        this.isMuted = volume === 0;
        this.updateVolumeDisplay();
        this.updateVolumeIcon();
    }

    adjustVolume(delta) {
        const newVolume = Math.max(0, Math.min(1, this.video.volume + delta));
        this.video.volume = newVolume;
        this.currentVolume = newVolume;
        this.isMuted = newVolume === 0;
        this.updateVolumeDisplay();
        this.updateVolumeIcon();
    }

    updateVolumeDisplay() {
        const volumeProgress = document.getElementById(`${this.playerId}-volume-progress`);
        if (volumeProgress) {
            volumeProgress.style.width = `${this.video.volume * 100}%`;
        }
    }

    updateVolumeIcon() {
        const volumeIcon = document.getElementById(`${this.playerId}-volume-icon`);
        const muteIcon = document.getElementById(`${this.playerId}-mute-icon`);
        
        if (this.isMuted || this.video.volume === 0) {
            volumeIcon?.classList.add('hidden');
            muteIcon?.classList.remove('hidden');
        } else {
            volumeIcon?.classList.remove('hidden');
            muteIcon?.classList.add('hidden');
        }
    }

    setSpeed(speed) {
        this.video.playbackRate = speed;
        this.currentSpeed = speed;
        
        const speedBtn = document.getElementById(`${this.playerId}-speed`);
        if (speedBtn) {
            speedBtn.textContent = `${speed}x`;
        }
    }

    toggleFullscreen() {
        if (!this.isFullscreen) {
            if (this.container.requestFullscreen) {
                this.container.requestFullscreen();
            } else if (this.container.webkitRequestFullscreen) {
                this.container.webkitRequestFullscreen();
            } else if (this.container.msRequestFullscreen) {
                this.container.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    // UI Methods
    showControls() {
        const controls = document.getElementById(`${this.playerId}-controls`);
        const scrubber = document.getElementById(`${this.playerId}-scrubber`);
        
        controls?.classList.remove('opacity-0');
        controls?.classList.add('opacity-100');
        scrubber?.classList.remove('opacity-0');
        scrubber?.classList.add('opacity-100');
    }

    hideControls() {
        if (this.isPlaying) {
            const controls = document.getElementById(`${this.playerId}-controls`);
            const scrubber = document.getElementById(`${this.playerId}-scrubber`);
            
            controls?.classList.add('opacity-0');
            controls?.classList.remove('opacity-100');
            scrubber?.classList.add('opacity-0');
            scrubber?.classList.remove('opacity-100');
        }
    }

    toggleControls() {
        const controls = document.getElementById(`${this.playerId}-controls`);
        if (controls?.classList.contains('opacity-0')) {
            this.showControls();
            setTimeout(() => this.hideControls(), 3000);
        } else {
            this.hideControls();
        }
    }

    hideLoading() {
        const loading = document.getElementById(`${this.playerId}-loading`);
        loading?.classList.add('hidden');
    }

    showMarkerPopup(marker) {
        const popup = document.getElementById(`${this.playerId}-marker-popup`);
        const title = document.getElementById(`${this.playerId}-marker-title`);
        const content = document.getElementById(`${this.playerId}-marker-content`);
        
        if (popup && title && content) {
            title.textContent = marker.title || '';
            content.textContent = marker.dataset.content || '';
            
            popup.classList.remove('hidden');
            popup.style.left = marker.offsetLeft + 'px';
            popup.style.top = (marker.offsetTop - popup.offsetHeight - 10) + 'px';
        }
    }

    hideMarkerPopup() {
        const popup = document.getElementById(`${this.playerId}-marker-popup`);
        popup?.classList.add('hidden');
    }

    // Interactive Features
    checkMarkers() {
        // Highlight current transcript segment
        const segments = this.container.querySelectorAll('.transcript-segment');
        segments.forEach(segment => {
            const start = parseFloat(segment.dataset.start);
            const end = parseFloat(segment.dataset.end);
            
            if (this.currentTime >= start && this.currentTime <= end) {
                segment.classList.add('bg-hcp-500/10', 'border-l-4', 'border-hcp-500');
            } else {
                segment.classList.remove('bg-hcp-500/10', 'border-l-4', 'border-hcp-500');
            }
        });
    }

    searchTranscript(query) {
        const segments = this.container.querySelectorAll('.transcript-segment');
        const searchQuery = query.toLowerCase();
        
        segments.forEach(segment => {
            const text = segment.textContent.toLowerCase();
            if (searchQuery === '' || text.includes(searchQuery)) {
                segment.style.display = 'block';
                
                if (searchQuery !== '') {
                    // Highlight search terms
                    const textEl = segment.querySelector('p');
                    if (textEl) {
                        const originalText = textEl.dataset.originalText || textEl.textContent;
                        textEl.dataset.originalText = originalText;
                        
                        const highlightedText = originalText.replace(
                            new RegExp(searchQuery, 'gi'),
                            `<mark class="bg-yellow-200 dark:bg-yellow-800">$&</mark>`
                        );
                        textEl.innerHTML = highlightedText;
                    }
                }
            } else {
                segment.style.display = 'none';
            }
        });
    }

    // Notes functionality
    async saveNote(content) {
        if (!content.trim()) return;

        const noteData = {
            content: content.trim(),
            timestamp: this.currentTime,
            content_id: this.contentId,
            module_id: this.moduleId
        };

        try {
            const response = await fetch('/api/video-notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(noteData)
            });

            if (response.ok) {
                this.loadNotes();
                this.showToast('Nota salva com sucesso!', 'success');
            }
        } catch (error) {
            console.error('Erro ao salvar nota:', error);
            this.showToast('Erro ao salvar nota', 'error');
        }
    }

    async loadNotes() {
        try {
            const response = await fetch(`/api/video-notes?content_id=${this.contentId}`);
            const notes = await response.json();
            
            this.renderNotes(notes);
        } catch (error) {
            console.error('Erro ao carregar notas:', error);
        }
    }

    renderNotes(notes) {
        const notesList = document.getElementById(`${this.playerId}-notes-list`);
        if (!notesList) return;

        notesList.innerHTML = notes.map(note => `
            <div class="bg-white dark:bg-hcp-secondary-800 p-4 rounded-hcp-lg border border-hcp-secondary-200 dark:border-hcp-secondary-600">
                <div class="flex items-start justify-between mb-2">
                    <span class="text-xs text-hcp-500 font-mono cursor-pointer hover:underline" 
                          onclick="window.hcpPlayers['${this.playerId}'].seekToTime(${note.timestamp})">
                        ${this.formatTime(note.timestamp)}
                    </span>
                    <button class="text-hcp-secondary-400 hover:text-hcp-error-500 transition-colors"
                            onclick="window.hcpPlayers['${this.playerId}'].deleteNote(${note.id})">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300">${note.content}</p>
                <div class="text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400 mt-2">
                    ${new Date(note.created_at).toLocaleDateString('pt-BR')}
                </div>
            </div>
        `).join('');
    }

    async deleteNote(noteId) {
        try {
            const response = await fetch(`/api/video-notes/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                this.loadNotes();
                this.showToast('Nota removida', 'success');
            }
        } catch (error) {
            console.error('Erro ao remover nota:', error);
        }
    }

    // Progress tracking
    async saveProgress() {
        // Salvar progresso a cada 10 segundos
        if (Math.floor(this.currentTime) % 10 === 0) {
            try {
                await fetch('/api/video-progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content_id: this.contentId,
                        module_id: this.moduleId,
                        current_time: this.currentTime,
                        duration: this.duration,
                        completed: this.currentTime >= this.duration * 0.9
                    })
                });
            } catch (error) {
                console.error('Erro ao salvar progresso:', error);
            }
        }
    }

    async loadUserProgress() {
        try {
            const response = await fetch(`/api/video-progress?content_id=${this.contentId}`);
            const progress = await response.json();
            
            if (progress && progress.current_time > 30) {
                this.showResumeDialog(progress.current_time);
            }
        } catch (error) {
            console.error('Erro ao carregar progresso:', error);
        }
    }

    showResumeDialog(time) {
        const resume = confirm(`Você parou em ${this.formatTime(time)}. Deseja continuar de onde parou?`);
        if (resume) {
            this.seekToTime(time);
        }
    }

    trackProgress() {
        // Analytics e tracking de engajamento
        setInterval(() => {
            if (this.isPlaying) {
                this.sendAnalytics('video_progress', {
                    content_id: this.contentId,
                    module_id: this.moduleId,
                    current_time: this.currentTime,
                    duration: this.duration,
                    engagement_rate: this.currentTime / this.duration
                });
            }
        }, 30000); // A cada 30 segundos
    }

    async sendAnalytics(event, data) {
        try {
            await fetch('/api/analytics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ event, data })
            });
        } catch (error) {
            console.error('Erro ao enviar analytics:', error);
        }
    }

    onVideoEnded() {
        this.sendAnalytics('video_completed', {
            content_id: this.contentId,
            module_id: this.moduleId,
            duration: this.duration,
            completion_rate: 100
        });

        // Mostrar próximo conteúdo ou quiz
        this.showNextContent();
    }

    showNextContent() {
        // Implementar lógica para mostrar próximo conteúdo
        this.showToast('Vídeo concluído! 🎉', 'success');
    }

    // Utility methods
    formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = Math.floor(seconds % 60);
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    }

    isPlayerFocused() {
        return this.container.contains(document.activeElement) || 
               document.activeElement === this.video;
    }

    showToast(message, type = 'info') {
        // Implementar sistema de toast notifications
        console.log(`Toast: ${message} (${type})`);
    }

    handleError(error) {
        console.error('Video player error:', error);
        this.showToast('Erro ao reproduzir vídeo', 'error');
    }
}

// Global player instances
window.hcpPlayers = window.hcpPlayers || {};

// Initialize player function
function initializeVideoPlayer(playerId) {
    if (!window.hcpPlayers[playerId]) {
        window.hcpPlayers[playerId] = new HCPVideoPlayer(playerId);
    }
    return window.hcpPlayers[playerId];
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { HCPVideoPlayer, initializeVideoPlayer };
}