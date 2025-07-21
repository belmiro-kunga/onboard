/**
 * QuizGamificationManager - Gerencia interações de gamificação após completar quizzes
 * 
 * Este componente gerencia:
 * - Animações de conquistas
 * - Exibição de pontos ganhos
 * - Celebrações visuais
 */
class QuizGamificationManager {
    /**
     * Inicializa o gerenciador de gamificação
     * 
     * @param {Object} config - Configurações de gamificação
     */
    constructor(config = {}) {
        this.config = {
            enableConfetti: true,
            enableSound: true,
            enableHaptic: true,
            ...config
        };
        
        this.achievements = [];
        this.pointsEarned = 0;
        this.streakCount = 0;
        
        // Inicializar eventos
        this.initEvents();
    }

    /**
     * Inicializa eventos
     */
    initEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            // Verificar se estamos na página de resultados
            const resultsContainer = document.querySelector('.quiz-results-container');
            if (resultsContainer) {
                this.loadResultsData();
                
                // Iniciar animações após um pequeno delay
                setTimeout(() => {
                    this.animateResults();
                }, 500);
            }
        });
    }

    /**
     * Carrega dados de resultados da página
     */
    loadResultsData() {
        // Obter pontos ganhos
        const pointsElement = document.querySelector('.points-earned');
        if (pointsElement) {
            this.pointsEarned = parseInt(pointsElement.dataset.points || 0);
        }
        
        // Obter conquistas
        document.querySelectorAll('.achievement-item').forEach(item => {
            this.achievements.push({
                id: item.dataset.id,
                title: item.dataset.title,
                description: item.dataset.description,
                element: item
            });
        });
        
        // Obter streak
        const streakElement = document.querySelector('.streak-count');
        if (streakElement) {
            this.streakCount = parseInt(streakElement.dataset.count || 0);
        }
    }

    /**
     * Anima os resultados com efeitos visuais
     */
    animateResults() {
        // Animar pontos
        this.animatePoints();
        
        // Animar conquistas
        setTimeout(() => {
            this.animateAchievements();
        }, 1000);
        
        // Animar streak
        setTimeout(() => {
            this.animateStreak();
        }, 1500);
        
        // Lançar confetti para aprovação
        const passedElement = document.querySelector('.quiz-passed');
        if (passedElement && this.config.enableConfetti) {
            this.launchConfetti();
        }
    }

    /**
     * Anima a exibição de pontos
     */
    animatePoints() {
        const pointsElement = document.querySelector('.points-earned');
        if (!pointsElement || this.pointsEarned <= 0) return;
        
        // Adicionar classe de animação
        pointsElement.classList.add('animate-points');
        
        // Animação de contagem
        let currentPoints = 0;
        const duration = 1500; // ms
        const interval = 30; // ms
        const increment = Math.max(1, Math.floor(this.pointsEarned / (duration / interval)));
        
        const counter = setInterval(() => {
            currentPoints = Math.min(currentPoints + increment, this.pointsEarned);
            pointsElement.textContent = `+${currentPoints}`;
            
            if (currentPoints >= this.pointsEarned) {
                clearInterval(counter);
                
                // Adicionar efeito de pulse no final
                pointsElement.classList.add('pulse-effect');
                
                // Haptic feedback
                if (this.config.enableHaptic && navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }
            }
        }, interval);
    }

    /**
     * Anima as conquistas obtidas
     */
    animateAchievements() {
        if (this.achievements.length === 0) return;
        
        this.achievements.forEach((achievement, index) => {
            setTimeout(() => {
                // Animar entrada da conquista
                achievement.element.classList.add('animate-achievement');
                
                // Adicionar efeito de brilho
                const glow = document.createElement('div');
                glow.className = 'achievement-glow';
                achievement.element.appendChild(glow);
                
                // Som de conquista
                if (this.config.enableSound) {
                    this.playAchievementSound();
                }
                
                // Haptic feedback
                if (this.config.enableHaptic && navigator.vibrate) {
                    navigator.vibrate([50, 100, 50, 100]);
                }
                
                // Remover glow após animação
                setTimeout(() => {
                    glow.remove();
                }, 2000);
            }, index * 800); // Escalonar animações
        });
    }

    /**
     * Anima o contador de streak
     */
    animateStreak() {
        const streakElement = document.querySelector('.streak-count');
        if (!streakElement || this.streakCount <= 0) return;
        
        // Adicionar classe de animação
        streakElement.classList.add('animate-streak');
        
        // Animação de contagem
        let currentStreak = 0;
        const duration = 1000; // ms
        const interval = 100; // ms
        
        const counter = setInterval(() => {
            currentStreak++;
            streakElement.textContent = currentStreak;
            
            if (currentStreak >= this.streakCount) {
                clearInterval(counter);
                
                // Adicionar efeito de fire no final
                if (this.streakCount >= 3) {
                    const fireEmoji = document.createElement('span');
                    fireEmoji.textContent = ' 🔥';
                    fireEmoji.className = 'fire-emoji';
                    streakElement.appendChild(fireEmoji);
                }
            }
        }, interval);
    }

    /**
     * Lança efeito de confetti
     */
    launchConfetti() {
        // Verificar se a biblioteca confetti está disponível
        if (typeof confetti === 'undefined') {
            // Carregar script de confetti dinamicamente
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js';
            document.head.appendChild(script);
            
            script.onload = () => {
                this.triggerConfetti();
            };
        } else {
            this.triggerConfetti();
        }
    }

    /**
     * Dispara o efeito de confetti
     */
    triggerConfetti() {
        if (typeof confetti !== 'undefined') {
            // Confetti em duas etapas
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
            
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                
                confetti({
                    particleCount: 50,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });
            }, 500);
        }
    }

    /**
     * Reproduz som de conquista
     */
    playAchievementSound() {
        // Implementação básica de som
        try {
            const audio = new Audio('/sounds/achievement.mp3');
            audio.volume = 0.5;
            audio.play();
        } catch (e) {
            console.log('Som não suportado ou arquivo não encontrado');
        }
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    window.quizGamificationManager = new QuizGamificationManager();
});