/**
 * HCP Gamification Animations - Sistema de animações avançadas para gamificação
 */

class GamificationAnimations {
    constructor() {
        this.container = document.getElementById('floating-animations') || this.createContainer();
        this.sounds = this.initializeSounds();
        this.hapticSupported = 'vibrate' in navigator;
    }

    /**
     * Criar container de animações se não existir
     */
    createContainer() {
        const container = document.createElement('div');
        container.id = 'floating-animations';
        container.className = 'fixed inset-0 pointer-events-none z-50';
        document.body.appendChild(container);
        return container;
    }

    /**
     * Inicializar sons (opcional)
     */
    initializeSounds() {
        return {
            points: this.createAudioContext() ? this.createPointsSound() : null,
            levelUp: this.createAudioContext() ? this.createLevelUpSound() : null,
            achievement: this.createAudioContext() ? this.createAchievementSound() : null
        };
    }

    /**
     * Criar contexto de áudio
     */
    createAudioContext() {
        try {
            return new (window.AudioContext || window.webkitAudioContext)();
        } catch (e) {
            return null;
        }
    }

    /**
     * Criar som de pontos
     */
    createPointsSound() {
        // Som sintético para pontos
        return (audioContext) => {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(1200, audioContext.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
        };
    }

    /**
     * Criar som de level up
     */
    createLevelUpSound() {
        return (audioContext) => {
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
            
            notes.forEach((freq, index) => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(freq, audioContext.currentTime + index * 0.15);
                gainNode.gain.setValueAtTime(0.2, audioContext.currentTime + index * 0.15);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + index * 0.15 + 0.4);
                
                oscillator.start(audioContext.currentTime + index * 0.15);
                oscillator.stop(audioContext.currentTime + index * 0.15 + 0.4);
            });
        };
    }

    /**
     * Criar som de conquista
     */
    createAchievementSound() {
        return (audioContext) => {
            // Fanfarra simples
            const melody = [523.25, 659.25, 783.99, 1046.50, 783.99, 1046.50, 1318.51];
            
            melody.forEach((freq, index) => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(freq, audioContext.currentTime + index * 0.1);
                gainNode.gain.setValueAtTime(0.15, audioContext.currentTime + index * 0.1);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + index * 0.1 + 0.3);
                
                oscillator.start(audioContext.currentTime + index * 0.1);
                oscillator.stop(audioContext.currentTime + index * 0.1 + 0.3);
            });
        };
    }

    /**
     * Reproduzir som
     */
    playSound(type) {
        if (this.sounds[type] && this.createAudioContext()) {
            try {
                const audioContext = this.createAudioContext();
                this.sounds[type](audioContext);
            } catch (e) {
                console.log('Audio not supported');
            }
        }
    }

    /**
     * Haptic feedback
     */
    hapticFeedback(pattern = [100]) {
        if (this.hapticSupported) {
            navigator.vibrate(pattern);
        }
    }

    /**
     * Animação de pontos flutuantes
     */
    showPointsFloat(points, reason = '', sourceElement = null) {
        const pointsElement = document.createElement('div');
        pointsElement.className = 'points-float';
        pointsElement.innerHTML = `
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full shadow-lg font-bold text-lg">
                +${points} ⭐
            </div>
            ${reason ? `<div class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mt-1 text-center">${reason}</div>` : ''}
        `;

        // Posicionamento
        const startX = sourceElement ? 
            sourceElement.getBoundingClientRect().left + sourceElement.offsetWidth / 2 :
            window.innerWidth / 2;
        const startY = sourceElement ?
            sourceElement.getBoundingClientRect().top :
            window.innerHeight / 2;

        pointsElement.style.cssText = `
            position: fixed;
            left: ${startX}px;
            top: ${startY}px;
            transform: translate(-50%, -50%);
            z-index: 1000;
            pointer-events: none;
            animation: pointsFloat 2s ease-out forwards;
        `;

        this.container.appendChild(pointsElement);

        // Remover após animação
        setTimeout(() => {
            if (pointsElement.parentNode) {
                pointsElement.parentNode.removeChild(pointsElement);
            }
        }, 2000);

        // Som e haptic
        this.playSound('points');
        this.hapticFeedback([50]);
    }

    /**
     * Animação de level up
     */
    showLevelUp(oldLevel, newLevel) {
        const levelUpElement = document.createElement('div');
        levelUpElement.className = 'level-up-animation';
        levelUpElement.innerHTML = `
            <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white p-8 rounded-2xl shadow-2xl text-center max-w-md mx-auto">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-2xl font-bold mb-2">LEVEL UP!</h2>
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <div class="text-lg opacity-75">${oldLevel}</div>
                    <div class="text-2xl">→</div>
                    <div class="text-xl font-bold text-yellow-300">${newLevel}</div>
                </div>
                <p class="text-sm opacity-90">Parabéns! Você subiu de nível!</p>
            </div>
        `;

        levelUpElement.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            pointer-events: none;
            animation: levelUpAnimation 3s ease-out forwards;
        `;

        this.container.appendChild(levelUpElement);

        // Confetti
        this.createConfetti();

        // Remover após animação
        setTimeout(() => {
            if (levelUpElement.parentNode) {
                levelUpElement.parentNode.removeChild(levelUpElement);
            }
        }, 3000);

        // Som e haptic
        this.playSound('levelUp');
        this.hapticFeedback([100, 50, 100, 50, 200]);
    }

    /**
     * Animação de conquista
     */
    showAchievementEarned(achievement) {
        const achievementElement = document.createElement('div');
        achievementElement.className = 'achievement-animation';
        achievementElement.innerHTML = `
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 rounded-2xl shadow-2xl max-w-sm mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" 
                         style="background-color: ${achievement.rarity_color}20; border: 3px solid ${achievement.rarity_color}">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Nova Conquista!</h3>
                    <h4 class="text-xl font-bold text-yellow-300 mb-2">${achievement.name}</h4>
                    <p class="text-sm opacity-90 mb-3">${achievement.description}</p>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="text-xs px-2 py-1 rounded-full" style="background-color: ${achievement.rarity_color}20; color: ${achievement.rarity_color}">
                            ${achievement.rarity}
                        </span>
                        <span class="text-xs text-yellow-300">+${achievement.points_reward} pontos</span>
                    </div>
                </div>
            </div>
        `;

        achievementElement.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            pointer-events: none;
            animation: achievementAnimation 4s ease-out forwards;
        `;

        this.container.appendChild(achievementElement);

        // Confetti especial para conquistas raras
        if (['epic', 'legendary'].includes(achievement.rarity)) {
            this.createConfetti(achievement.rarity_color);
        }

        // Remover após animação
        setTimeout(() => {
            if (achievementElement.parentNode) {
                achievementElement.parentNode.removeChild(achievementElement);
            }
        }, 4000);

        // Som e haptic
        this.playSound('achievement');
        this.hapticFeedback([200, 100, 200]);
    }

    /**
     * Criar efeito confetti
     */
    createConfetti(color = null) {
        const colors = color ? [color] : ['#FFD700', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];
        const confettiCount = 50;

        for (let i = 0; i < confettiCount; i++) {
            setTimeout(() => {
                this.createConfettiPiece(colors[Math.floor(Math.random() * colors.length)]);
            }, i * 20);
        }
    }

    /**
     * Criar peça individual de confetti
     */
    createConfettiPiece(color) {
        const confetti = document.createElement('div');
        confetti.style.cssText = `
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: ${color};
            top: -10px;
            left: ${Math.random() * window.innerWidth}px;
            z-index: 999;
            pointer-events: none;
            animation: confettiFall ${2 + Math.random() * 3}s linear forwards;
            transform: rotate(${Math.random() * 360}deg);
        `;

        this.container.appendChild(confetti);

        // Remover após queda
        setTimeout(() => {
            if (confetti.parentNode) {
                confetti.parentNode.removeChild(confetti);
            }
        }, 5000);
    }

    /**
     * Animação de progress ring
     */
    animateProgressRing(element, fromPercent, toPercent, duration = 1000) {
        const circle = element.querySelector('circle:last-child') || element.querySelector('circle');
        if (!circle) return;

        const radius = circle.r ? circle.r.baseVal.value : 45; // fallback radius
        const circumference = 2 * Math.PI * radius;
        
        circle.style.strokeDasharray = circumference;
        
        const fromOffset = circumference - (fromPercent / 100) * circumference;
        const toOffset = circumference - (toPercent / 100) * circumference;
        
        circle.style.strokeDashoffset = fromOffset;
        
        // Animar
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const currentOffset = fromOffset + (toOffset - fromOffset) * easeOutCubic;
            
            circle.style.strokeDashoffset = currentOffset;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    /**
     * Criar progress ring SVG
     */
    createProgressRing(container, percentage, color = '#4F46E5', size = 100) {
        const radius = (size - 10) / 2;
        const circumference = 2 * Math.PI * radius;
        const offset = circumference - (percentage / 100) * circumference;

        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', size);
        svg.setAttribute('height', size);
        svg.classList.add('progress-ring');

        // Background circle
        const bgCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        bgCircle.setAttribute('cx', size / 2);
        bgCircle.setAttribute('cy', size / 2);
        bgCircle.setAttribute('r', radius);
        bgCircle.setAttribute('fill', 'none');
        bgCircle.setAttribute('stroke', '#e5e7eb');
        bgCircle.setAttribute('stroke-width', '8');

        // Progress circle
        const progressCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        progressCircle.setAttribute('cx', size / 2);
        progressCircle.setAttribute('cy', size / 2);
        progressCircle.setAttribute('r', radius);
        progressCircle.setAttribute('fill', 'none');
        progressCircle.setAttribute('stroke', color);
        progressCircle.setAttribute('stroke-width', '8');
        progressCircle.setAttribute('stroke-linecap', 'round');
        progressCircle.style.strokeDasharray = circumference;
        progressCircle.style.strokeDashoffset = offset;
        progressCircle.style.transform = 'rotate(-90deg)';
        progressCircle.style.transformOrigin = '50% 50%';
        progressCircle.style.transition = 'stroke-dashoffset 0.5s ease-in-out';

        svg.appendChild(bgCircle);
        svg.appendChild(progressCircle);
        container.appendChild(svg);

        return { svg, progressCircle };
    }

    /**
     * Animação de contador
     */
    animateCounter(element, fromValue, toValue, duration = 1000, formatter = null) {
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.round(fromValue + (toValue - fromValue) * easeOutCubic);
            
            element.textContent = formatter ? formatter(currentValue) : currentValue;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    /**
     * Shake animation para elementos
     */
    shakeElement(element, intensity = 'medium') {
        const intensityMap = {
            light: 'shake-light',
            medium: 'shake-medium',
            strong: 'shake-strong'
        };
        
        const className = intensityMap[intensity] || intensityMap.medium;
        element.classList.add(className);
        
        setTimeout(() => {
            element.classList.remove(className);
        }, 600);
    }

    /**
     * Pulse animation para elementos
     */
    pulseElement(element, color = '#4F46E5', duration = 1000) {
        element.style.animation = `pulse-${color.replace('#', '')} ${duration}ms ease-in-out`;
        
        setTimeout(() => {
            element.style.animation = '';
        }, duration);
    }
}

// Adicionar CSS para animações
const style = document.createElement('style');
style.textContent = `
    @keyframes pointsFloat {
        0% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(0.8);
        }
        50% {
            opacity: 1;
            transform: translate(-50%, -150px) scale(1.2);
        }
        100% {
            opacity: 0;
            transform: translate(-50%, -200px) scale(1);
        }
    }

    @keyframes levelUpAnimation {
        0% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.5);
        }
        20% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
        }
        80% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        100% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
    }

    @keyframes achievementAnimation {
        0% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.3) rotateY(90deg);
        }
        20% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1) rotateY(0deg);
        }
        80% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1) rotateY(0deg);
        }
        100% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.8) rotateY(-90deg);
        }
    }

    @keyframes confettiFall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }

    @keyframes shake-light {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        75% { transform: translateX(2px); }
    }

    @keyframes shake-medium {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    @keyframes shake-strong {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .shake-light { animation: shake-light 0.6s ease-in-out; }
    .shake-medium { animation: shake-medium 0.6s ease-in-out; }
    .shake-strong { animation: shake-strong 0.6s ease-in-out; }
`;

document.head.appendChild(style);

// Exportar para uso global
window.GamificationAnimations = GamificationAnimations;