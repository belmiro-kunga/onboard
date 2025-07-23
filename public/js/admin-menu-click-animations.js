/**
 * ===== SISTEMA DE ANIMAÇÕES DE CLIQUE PARA MENU ADMIN =====
 * 
 * Este script gerencia todas as animações de clique nos itens do menu administrativo
 * Inclui efeitos de ripple, pulso, brilho e feedback visual
 */

class AdminMenuAnimations {
    constructor() {
        this.menuItems = [];
        this.activeItem = null;
        this.animationQueue = [];
        this.isAnimating = false;
        
        this.init();
    }

    /**
     * Inicializa o sistema de animações
     */
    init() {
        this.setupMenuItems();
        this.bindEvents();
        this.setupKeyboardNavigation();
        
        console.log('🎨 Sistema de animações do menu admin inicializado');
    }

    /**
     * Configura os itens do menu
     */
    setupMenuItems() {
        // Seleciona todos os links do menu
        this.menuItems = document.querySelectorAll('nav a[href]');
        
        // Adiciona classes e atributos necessários
        this.menuItems.forEach((item, index) => {
            item.classList.add('admin-menu-item');
            item.setAttribute('data-menu-index', index);
            
            // Detecta o tipo de menu baseado na URL
            const href = item.getAttribute('href');
            if (href.includes('dashboard')) {
                item.setAttribute('data-menu', 'dashboard');
            } else if (href.includes('users')) {
                item.setAttribute('data-menu', 'users');
            } else if (href.includes('modules')) {
                item.setAttribute('data-menu', 'modules');
            } else if (href.includes('settings')) {
                item.setAttribute('data-menu', 'settings');
            }
            
            // Adiciona ícones e textos com classes específicas
            const icon = item.querySelector('svg');
            const text = item.querySelector('span');
            
            if (icon) icon.classList.add('menu-icon');
            if (text) text.classList.add('menu-text');
        });
    }

    /**
     * Vincula eventos aos itens do menu
     */
    bindEvents() {
        this.menuItems.forEach(item => {
            // Evento de clique
            item.addEventListener('click', (e) => {
                this.handleMenuClick(e, item);
            });
            
            // Evento de mouse down para efeito imediato
            item.addEventListener('mousedown', (e) => {
                this.handleMouseDown(e, item);
            });
            
            // Evento de mouse up
            item.addEventListener('mouseup', (e) => {
                this.handleMouseUp(e, item);
            });
            
            // Eventos de hover
            item.addEventListener('mouseenter', (e) => {
                this.handleHoverEnter(e, item);
            });
            
            item.addEventListener('mouseleave', (e) => {
                this.handleHoverLeave(e, item);
            });
            
            // Eventos de foco para acessibilidade
            item.addEventListener('focus', (e) => {
                this.handleFocus(e, item);
            });
            
            item.addEventListener('blur', (e) => {
                this.handleBlur(e, item);
            });
        });
    }

    /**
     * Configura navegação por teclado
     */
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Atalhos de teclado para navegação rápida
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case '1':
                        e.preventDefault();
                        this.navigateToMenu(0);
                        break;
                    case '2':
                        e.preventDefault();
                        this.navigateToMenu(1);
                        break;
                    case '3':
                        e.preventDefault();
                        this.navigateToMenu(2);
                        break;
                    case '4':
                        e.preventDefault();
                        this.navigateToMenu(3);
                        break;
                }
            }
            
            // Navegação com setas
            if (document.activeElement && document.activeElement.classList.contains('admin-menu-item')) {
                const currentIndex = parseInt(document.activeElement.getAttribute('data-menu-index'));
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        this.focusNextItem(currentIndex);
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        this.focusPreviousItem(currentIndex);
                        break;
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        this.handleMenuClick(e, document.activeElement);
                        break;
                }
            }
        });
    }

    /**
     * Manipula o clique no menu
     */
    handleMenuClick(e, item) {
        // Previne múltiplos cliques rápidos
        if (this.isAnimating) {
            e.preventDefault();
            return;
        }
        
        this.isAnimating = true;
        
        // Remove estado ativo de outros itens
        this.clearActiveStates();
        
        // Adiciona animações
        this.addRippleEffect(e, item);
        this.addClickPulse(item);
        this.addLoadingState(item);
        
        // Marca como ativo
        item.classList.add('click-active', 'active');
        this.activeItem = item;
        
        // Simula carregamento
        setTimeout(() => {
            this.addSuccessState(item);
            this.isAnimating = false;
        }, 800);
        
        // Log para debug
        const menuType = item.getAttribute('data-menu') || 'unknown';
        console.log(`🎯 Menu clicado: ${menuType}`);
    }

    /**
     * Manipula mouse down
     */
    handleMouseDown(e, item) {
        item.classList.add('click-pulse');
    }

    /**
     * Manipula mouse up
     */
    handleMouseUp(e, item) {
        setTimeout(() => {
            item.classList.remove('click-pulse');
        }, 200);
    }

    /**
     * Manipula entrada do hover
     */
    handleHoverEnter(e, item) {
        if (!item.classList.contains('active')) {
            item.style.transform = 'translateX(4px)';
            item.style.transition = 'transform 0.2s ease-out';
        }
    }

    /**
     * Manipula saída do hover
     */
    handleHoverLeave(e, item) {
        if (!item.classList.contains('active')) {
            item.style.transform = 'translateX(0)';
        }
    }

    /**
     * Manipula foco
     */
    handleFocus(e, item) {
        item.style.boxShadow = '0 0 0 3px rgba(99, 102, 241, 0.3)';
    }

    /**
     * Manipula perda de foco
     */
    handleBlur(e, item) {
        item.style.boxShadow = '';
    }

    /**
     * Adiciona efeito ripple
     */
    addRippleEffect(e, item) {
        // Remove ripple anterior se existir
        item.classList.remove('ripple-active');
        
        // Força reflow
        item.offsetHeight;
        
        // Adiciona novo ripple
        item.classList.add('ripple-active');
        
        // Remove após animação
        setTimeout(() => {
            item.classList.remove('ripple-active');
        }, 600);
    }

    /**
     * Adiciona efeito de pulso
     */
    addClickPulse(item) {
        item.classList.add('click-pulse');
        
        setTimeout(() => {
            item.classList.remove('click-pulse');
        }, 200);
    }

    /**
     * Adiciona estado de carregamento
     */
    addLoadingState(item) {
        item.classList.add('loading');
        
        setTimeout(() => {
            item.classList.remove('loading');
        }, 800);
    }

    /**
     * Adiciona estado de sucesso
     */
    addSuccessState(item) {
        item.classList.add('success');
        
        setTimeout(() => {
            item.classList.remove('success');
        }, 1000);
    }

    /**
     * Remove estados ativos de todos os itens
     */
    clearActiveStates() {
        this.menuItems.forEach(item => {
            item.classList.remove('click-active', 'active', 'loading', 'success');
            item.style.transform = '';
        });
    }

    /**
     * Navega para um item específico do menu
     */
    navigateToMenu(index) {
        if (this.menuItems[index]) {
            const item = this.menuItems[index];
            this.handleMenuClick(new Event('click'), item);
            
            // Simula clique real após animação
            setTimeout(() => {
                item.click();
            }, 300);
        }
    }

    /**
     * Foca no próximo item
     */
    focusNextItem(currentIndex) {
        const nextIndex = (currentIndex + 1) % this.menuItems.length;
        this.menuItems[nextIndex].focus();
    }

    /**
     * Foca no item anterior
     */
    focusPreviousItem(currentIndex) {
        const prevIndex = currentIndex === 0 ? this.menuItems.length - 1 : currentIndex - 1;
        this.menuItems[prevIndex].focus();
    }

    /**
     * Adiciona animação de entrada para novos itens
     */
    addSlideInAnimation() {
        this.menuItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('slide-in');
        });
    }

    /**
     * API pública para controle externo
     */
    getAPI() {
        return {
            // Simula clique em um menu específico
            clickMenu: (menuType) => {
                const item = document.querySelector(`[data-menu="${menuType}"]`);
                if (item) {
                    this.handleMenuClick(new Event('click'), item);
                }
            },
            
            // Destaca um menu específico
            highlightMenu: (menuType) => {
                const item = document.querySelector(`[data-menu="${menuType}"]`);
                if (item) {
                    item.classList.add('active');
                    setTimeout(() => {
                        item.classList.remove('active');
                    }, 2000);
                }
            },
            
            // Reseta todas as animações
            resetAnimations: () => {
                this.clearActiveStates();
                this.isAnimating = false;
            },
            
            // Adiciona animação de entrada
            slideIn: () => {
                this.addSlideInAnimation();
            }
        };
    }
}

// Inicializa quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    const adminMenuAnimations = new AdminMenuAnimations();
    
    // Expõe API globalmente
    window.adminMenuAnimations = adminMenuAnimations.getAPI();
    
    // Adiciona animação de entrada inicial
    setTimeout(() => {
        adminMenuAnimations.addSlideInAnimation();
    }, 100);
});

// Adiciona suporte para hot reload em desenvolvimento
if (module && module.hot) {
    module.hot.accept();
}