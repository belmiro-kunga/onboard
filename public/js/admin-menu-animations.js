/**
 * Animações do Menu Admin
 * Sistema de animações para interface administrativa
 */

class AdminMenuAnimations {
    constructor() {
        this.currentPath = window.location.pathname;
        this.init();
    }

    init() {
        this.setupMenuAnimations();
        this.setupPageTransitions();
        this.setupActiveMenuDetection();
        this.setupSubmenuToggle();
        this.setupNotificationAnimations();
        this.setupCardAnimations();
        this.setupButtonAnimations();
        this.hideInitialLoader();
    }

    /**
     * Configurar animações do menu
     */
    setupMenuAnimations() {
        const menuItems = document.querySelectorAll('.menu-item');
        
        menuItems.forEach(item => {
            // Adicionar classes de animação
            item.classList.add('transition-all', 'duration-300', 'ease-in-out');
            
            // Efeito hover
            item.addEventListener('mouseenter', (e) => {
                this.animateMenuHover(e.target, true);
            });
            
            item.addEventListener('mouseleave', (e) => {
                this.animateMenuHover(e.target, false);
            });
            
            // Efeito click
            item.addEventListener('click', (e) => {
                this.animateMenuClick(e.target);
            });
        });
    }

    /**
     * Animar hover do menu
     */
    animateMenuHover(element, isEntering) {
        const icon = element.querySelector('.menu-icon');
        const text = element.querySelector('.menu-text');
        
        if (isEntering) {
            element.style.transform = 'translateX(4px)';
            element.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
            element.style.boxShadow = '0 4px 12px rgba(59, 130, 246, 0.15)';
            
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
                icon.style.color = '#3b82f6';
            }
            
            if (text) {
                text.style.fontWeight = '600';
                text.style.letterSpacing = '0.5px';
            }
        } else {
            if (!element.classList.contains('active')) {
                element.style.transform = '';
                element.style.backgroundColor = '';
                element.style.boxShadow = '';
                
                if (icon) {
                    icon.style.transform = '';
                    icon.style.color = '';
                }
                
                if (text) {
                    text.style.fontWeight = '';
                    text.style.letterSpacing = '';
                }
            }
        }
    }

    /**
     * Animar click do menu
     */
    animateMenuClick(element) {
        // Remover active de outros itens
        document.querySelectorAll('.menu-item.active').forEach(item => {
            item.classList.remove('active');
        });
        
        // Adicionar active ao item clicado
        element.classList.add('active');
        
        // Animação de click
        element.style.transform = 'scale(0.95)';
        setTimeout(() => {
            element.style.transform = 'translateX(8px)';
        }, 100);
        
        // Salvar no localStorage
        localStorage.setItem('activeMenuItem', element.dataset.menu || '');
    }

    /**
     * Detectar menu ativo baseado na URL
     */
    setupActiveMenuDetection() {
        const menuItems = document.querySelectorAll('.menu-item[data-menu]');
        
        menuItems.forEach(item => {
            const menuPath = item.dataset.menu;
            
            if (this.currentPath.includes(menuPath)) {
                item.classList.add('active');
                this.animateActiveMenu(item);
            }
        });
        
        // Restaurar do localStorage se não houver match
        const savedActiveMenu = localStorage.getItem('activeMenuItem');
        if (savedActiveMenu && !document.querySelector('.menu-item.active')) {
            const savedItem = document.querySelector(`[data-menu="${savedActiveMenu}"]`);
            if (savedItem) {
                savedItem.classList.add('active');
                this.animateActiveMenu(savedItem);
            }
        }
    }

    /**
     * Animar menu ativo
     */
    animateActiveMenu(element) {
        const icon = element.querySelector('.menu-icon');
        const text = element.querySelector('.menu-text');
        
        element.style.background = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
        element.style.color = 'white';
        element.style.transform = 'translateX(8px)';
        element.style.boxShadow = '0 8px 25px rgba(59, 130, 246, 0.3)';
        element.style.borderRadius = '0 25px 25px 0';
        
        if (icon) {
            icon.style.transform = 'scale(1.2)';
            icon.style.color = '#fbbf24';
            icon.style.filter = 'drop-shadow(0 0 8px rgba(251, 191, 36, 0.5))';
        }
        
        if (text) {
            text.style.fontWeight = '700';
            text.style.textShadow = '0 0 10px rgba(255,255,255,0.3)';
        }
        
        // Adicionar indicador lateral
        this.addActiveIndicator(element);
    }

    /**
     * Adicionar indicador de menu ativo
     */
    addActiveIndicator(element) {
        // Remover indicadores existentes
        document.querySelectorAll('.active-indicator').forEach(indicator => {
            indicator.remove();
        });
        
        const indicator = document.createElement('div');
        indicator.className = 'active-indicator';
        indicator.style.cssText = `
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #fbbf24;
            border-radius: 2px 0 0 2px;
            animation: pulse-glow 2s infinite;
        `;
        
        element.style.position = 'relative';
        element.appendChild(indicator);
    }

    /**
     * Configurar toggle de submenu
     */
    setupSubmenuToggle() {
        const submenuToggles = document.querySelectorAll('[data-submenu-toggle]');
        
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = toggle.dataset.submenuToggle;
                const submenu = document.getElementById(targetId);
                
                if (submenu) {
                    this.toggleSubmenu(submenu, toggle);
                }
            });
        });
    }

    /**
     * Toggle submenu com animação
     */
    toggleSubmenu(submenu, toggle) {
        const isOpen = submenu.classList.contains('open');
        const icon = toggle.querySelector('.submenu-arrow');
        
        if (isOpen) {
            submenu.classList.remove('open');
            submenu.style.maxHeight = '0';
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            submenu.classList.add('open');
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            if (icon) icon.style.transform = 'rotate(180deg)';
            
            // Animar itens do submenu
            const items = submenu.querySelectorAll('.submenu-item');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.style.transform = 'translateX(0)';
                    item.style.opacity = '1';
                }, index * 100);
            });
        }
    }

    /**
     * Configurar transições de página
     */
    setupPageTransitions() {
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            mainContent.classList.add('page-transition');
            
            // Animar entrada
            setTimeout(() => {
                mainContent.classList.add('loaded');
            }, 100);
        }
        
        // Interceptar links para animação de saída
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.hostname === window.location.hostname) {
                    this.animatePageExit();
                }
            });
        });
    }

    /**
     * Animar saída da página
     */
    animatePageExit() {
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            mainContent.style.opacity = '0';
            mainContent.style.transform = 'translateY(-20px)';
        }
    }

    /**
     * Configurar animações de notificação
     */
    setupNotificationAnimations() {
        const notificationBadges = document.querySelectorAll('.notification-badge');
        
        notificationBadges.forEach(badge => {
            badge.style.animation = 'bounce-notification 2s infinite';
        });
        
        // Animar novas notificações
        this.observeNotifications();
    }

    /**
     * Observar novas notificações
     */
    observeNotifications() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach((node) => {
                        if (node.classList && node.classList.contains('notification')) {
                            this.animateNewNotification(node);
                        }
                    });
                }
            });
        });
        
        const notificationContainer = document.querySelector('.notifications-container');
        if (notificationContainer) {
            observer.observe(notificationContainer, { childList: true });
        }
    }

    /**
     * Animar nova notificação
     */
    animateNewNotification(notification) {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            notification.style.transition = 'all 0.5s ease';
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);
    }

    /**
     * Configurar animações de cards
     */
    setupCardAnimations() {
        const cards = document.querySelectorAll('.admin-card, .card');
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
            
            // Hover animation
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
                card.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '';
            });
        });
    }

    /**
     * Configurar animações de botões
     */
    setupButtonAnimations() {
        const buttons = document.querySelectorAll('.btn, button');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.createRippleEffect(e, button);
            });
        });
    }

    /**
     * Criar efeito ripple nos botões
     */
    createRippleEffect(event, button) {
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    /**
     * Esconder loader inicial
     */
    hideInitialLoader() {
        const loader = document.getElementById('initial-loader');
        const app = document.getElementById('app');
        
        if (loader && app) {
            setTimeout(() => {
                loader.style.opacity = '0';
                app.style.opacity = '1';
                
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }, 1000);
        }
    }

    /**
     * Mostrar animação de sucesso
     */
    showSuccessAnimation(element) {
        element.classList.add('success-animation');
        setTimeout(() => {
            element.classList.remove('success-animation');
        }, 600);
    }

    /**
     * Mostrar animação de erro
     */
    showErrorAnimation(element) {
        element.classList.add('error-animation');
        setTimeout(() => {
            element.classList.remove('error-animation');
        }, 600);
    }
}

// Adicionar estilos de animação CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            opacity: 1;
            box-shadow: 0 0 5px #fbbf24;
        }
        50% {
            opacity: 0.7;
            box-shadow: 0 0 20px #fbbf24, 0 0 30px #fbbf24;
        }
    }
    
    @keyframes bounce-notification {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-3px);
        }
        60% {
            transform: translateY(-1px);
        }
    }
`;
document.head.appendChild(style);

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.adminMenuAnimations = new AdminMenuAnimations();
});

// Exportar para uso global
window.AdminMenuAnimations = AdminMenuAnimations;