/**
 * Gamification Animations
 * Responsável por animações visuais na gamificação
 */
class GamificationAnimations {
    constructor() {
        this.container = document.getElementById('floating-animations');
        this.init();
    }

    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'floating-animations';
            this.container.className = 'fixed inset-0 pointer-events-none z-50';
            document.body.appendChild(this.container);
        }
    }

    /**
     * Mostra animação de pontos flutuantes
     */
    showPointsFloat(points, reason = '') {
        const element = document.createElement('div');
        element.className = 'fixed pointer-events-none z-50';
        element.innerHTML = `
            <div class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg animate-bounce">
                +${points} pts
            </div>
            ${reason ? `<div class="text-xs text-gray-600 mt-1 text-center">${reason}</div>` : ''}
        `;

        // Posição aleatória
        const x = Math.random() * (window.innerWidth - 100);
        const y = Math.random() * (window.innerHeight - 100);
        
        element.style.left = `${x}px`;
        element.style.top = `${y}px`;

        this.container.appendChild(element);

        // Animar entrada
        element.style.opacity = '0';
        element.style.transform = 'scale(0.5) translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease-out';
            element.style.opacity = '1';
            element.style.transform = 'scale(1) translateY(0)';
        }, 100);

        // Remover após animação
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease-in';
            element.style.opacity = '0';
            element.style.transform = 'scale(0.8) translateY(-20px)';
            
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 500);
        }, 3000);
    }

    /**
     * Mostra animação de level up
     */
    showLevelUp(oldLevel, newLevel) {
        const element = document.createElement('div');
        element.className = 'fixed inset-0 flex items-center justify-center pointer-events-none z-50';
        element.innerHTML = `
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-8 rounded-2xl shadow-2xl text-center max-w-md mx-4">
                <div class="text-6xl mb-4">🎉</div>
                <h3 class="text-2xl font-bold mb-2">Level Up!</h3>
                <p class="text-lg mb-4">${oldLevel} → ${newLevel}</p>
                <p class="text-sm opacity-90">Parabéns! Você subiu de nível!</p>
            </div>
        `;

        this.container.appendChild(element);

        // Animar entrada
        element.style.opacity = '0';
        element.style.transform = 'scale(0.5)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            element.style.opacity = '1';
            element.style.transform = 'scale(1)';
        }, 100);

        // Remover após animação
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease-in';
            element.style.opacity = '0';
            element.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 500);
        }, 4000);
    }

    /**
     * Mostra animação de achievement conquistado
     */
    showAchievementEarned(achievement) {
        const element = document.createElement('div');
        element.className = 'fixed top-4 right-4 pointer-events-none z-50';
        element.innerHTML = `
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl" 
                         style="background-color: ${this.getRarityColor(achievement.rarity)}20; border: 2px solid ${this.getRarityColor(achievement.rarity)}">
                        🏆
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 dark:text-white">${achievement.name}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${achievement.description}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">+${achievement.points_reward} pontos</p>
                    </div>
                </div>
            </div>
        `;

        this.container.appendChild(element);

        // Animar entrada
        element.style.opacity = '0';
        element.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease-out';
            element.style.opacity = '1';
            element.style.transform = 'translateX(0)';
        }, 100);

        // Remover após animação
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease-in';
            element.style.opacity = '0';
            element.style.transform = 'translateX(100%)';
            
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 500);
        }, 5000);
    }

    /**
     * Mostra confete para celebrações
     */
    showConfetti() {
        for (let i = 0; i < 50; i++) {
            this.createConfetti();
        }
    }

    /**
     * Cria um confete individual
     */
    createConfetti() {
        const confetti = document.createElement('div');
        confetti.className = 'fixed pointer-events-none z-50 w-2 h-2';
        
        const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3'];
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        confetti.style.backgroundColor = color;
        confetti.style.left = Math.random() * window.innerWidth + 'px';
        confetti.style.top = -10 + 'px';
        confetti.style.transform = 'rotate(' + Math.random() * 360 + 'deg)';

        this.container.appendChild(confetti);

        // Animar queda
        const animation = confetti.animate([
            { 
                top: -10 + 'px',
                transform: 'rotate(' + Math.random() * 360 + 'deg)'
            },
            { 
                top: window.innerHeight + 'px',
                transform: 'rotate(' + Math.random() * 360 + 'deg)'
            }
        ], {
            duration: Math.random() * 3000 + 2000,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        });

        animation.onfinish = () => {
            if (confetti.parentNode) {
                confetti.parentNode.removeChild(confetti);
            }
        };
    }

    /**
     * Obtém cor baseada na raridade
     */
    getRarityColor(rarity) {
        const colors = {
            'common': '#6B7280',
            'rare': '#3B82F6',
            'epic': '#8B5CF6',
            'legendary': '#F59E0B'
        };
        return colors[rarity] || colors.common;
    }

    /**
     * Anima contador de pontos
     */
    animatePointsCounter(element, startValue, endValue, duration = 1000) {
        const start = performance.now();
        const difference = endValue - startValue;

        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = startValue + (difference * easeOutQuart);
            
            element.textContent = Math.floor(currentValue).toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    /**
     * Anima barra de progresso
     */
    animateProgressBar(element, targetPercentage, duration = 1000) {
        const start = performance.now();
        const startWidth = 0;
        const endWidth = targetPercentage;

        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentWidth = startWidth + (endWidth - startWidth) * easeOutQuart;
            
            element.style.width = currentWidth + '%';
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }
}

// Exportar para uso global
window.GamificationAnimations = GamificationAnimations; 