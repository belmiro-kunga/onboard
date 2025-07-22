/**
 * Sistema de Toggle de Tema HCP
 * Gerencia a alternância entre modo claro e escuro
 */

class ThemeToggle {
    constructor() {
        this.theme = this.getStoredTheme() || this.getSystemTheme();
        this.init();
    }

    /**
     * Inicializar o sistema de tema
     */
    init() {
        this.applyTheme(this.theme);
        this.setupEventListeners();
        this.watchSystemTheme();
    }

    /**
     * Obter tema armazenado no localStorage
     */
    getStoredTheme() {
        return localStorage.getItem('hcp-theme');
    }

    /**
     * Obter tema do sistema
     */
    getSystemTheme() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    /**
     * Aplicar tema ao documento
     */
    applyTheme(theme) {
        const root = document.documentElement;
        
        // Remover tema anterior
        root.removeAttribute('data-theme');
        root.classList.remove('dark', 'light');
        
        // Aplicar novo tema
        root.setAttribute('data-theme', theme);
        root.classList.add(theme);
        
        // Atualizar meta theme-color para mobile
        this.updateMetaThemeColor(theme);
        
        // Atualizar ícones do toggle
        this.updateToggleIcons(theme);
        
        // Disparar evento personalizado
        this.dispatchThemeChangeEvent(theme);
    }

    /**
     * Alternar tema
     */
    toggle() {
        const newTheme = this.theme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    /**
     * Definir tema específico
     */
    setTheme(theme) {
        this.theme = theme;
        this.applyTheme(theme);
        this.storeTheme(theme);
    }

    /**
     * Armazenar tema no localStorage
     */
    storeTheme(theme) {
        localStorage.setItem('hcp-theme', theme);
    }

    /**
     * Atualizar cor do tema no meta tag
     */
    updateMetaThemeColor(theme) {
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            const color = theme === 'dark' ? '#0f172a' : '#ffffff';
            metaThemeColor.setAttribute('content', color);
        }
    }

    /**
     * Atualizar ícones dos botões de toggle
     */
    updateToggleIcons(theme) {
        const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
        
        toggleButtons.forEach(button => {
            const sunIcon = button.querySelector('[data-theme-icon="sun"]');
            const moonIcon = button.querySelector('[data-theme-icon="moon"]');
            
            if (sunIcon && moonIcon) {
                if (theme === 'dark') {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
        });
    }

    /**
     * Configurar event listeners
     */
    setupEventListeners() {
        // Botões de toggle
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-theme-toggle]')) {
                e.preventDefault();
                this.toggle();
            }
        });

        // Atalho de teclado (Ctrl/Cmd + Shift + T)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                this.toggle();
            }
        });
    }

    /**
     * Observar mudanças no tema do sistema
     */
    watchSystemTheme() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        mediaQuery.addEventListener('change', (e) => {
            // Só aplicar tema do sistema se não houver preferência armazenada
            if (!this.getStoredTheme()) {
                const systemTheme = e.matches ? 'dark' : 'light';
                this.setTheme(systemTheme);
            }
        });
    }

    /**
     * Disparar evento personalizado de mudança de tema
     */
    dispatchThemeChangeEvent(theme) {
        const event = new CustomEvent('themeChanged', {
            detail: { theme, previousTheme: this.theme }
        });
        document.dispatchEvent(event);
    }

    /**
     * Obter tema atual
     */
    getCurrentTheme() {
        return this.theme;
    }

    /**
     * Verificar se está no modo escuro
     */
    isDark() {
        return this.theme === 'dark';
    }

    /**
     * Verificar se está no modo claro
     */
    isLight() {
        return this.theme === 'light';
    }

    /**
     * Resetar para tema do sistema
     */
    resetToSystem() {
        localStorage.removeItem('hcp-theme');
        const systemTheme = this.getSystemTheme();
        this.setTheme(systemTheme);
    }
}

/**
 * Utilitários para animações de transição de tema
 */
class ThemeTransitions {
    /**
     * Aplicar transição suave ao alternar tema
     */
    static smoothTransition(callback) {
        // Adicionar classe de transição
        document.documentElement.classList.add('theme-transitioning');
        
        // Executar callback
        if (typeof callback === 'function') {
            callback();
        }
        
        // Remover classe após transição
        setTimeout(() => {
            document.documentElement.classList.remove('theme-transitioning');
        }, 300);
    }

    /**
     * Animação de fade para mudança de tema
     */
    static fadeTransition(callback) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black opacity-0 transition-opacity duration-200 z-50 pointer-events-none';
        document.body.appendChild(overlay);

        // Fade in
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-10');
        });

        setTimeout(() => {
            if (typeof callback === 'function') {
                callback();
            }

            // Fade out
            overlay.classList.remove('opacity-10');
            overlay.classList.add('opacity-0');

            setTimeout(() => {
                document.body.removeChild(overlay);
            }, 200);
        }, 100);
    }
}

/**
 * Inicializar sistema de tema quando DOM estiver pronto
 */
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar toggle de tema
    window.themeToggle = new ThemeToggle();
    
    // Adicionar CSS para transições suaves
    const style = document.createElement('style');
    style.textContent = `
        .theme-transitioning * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease !important;
        }
    `;
    document.head.appendChild(style);
    
    console.log('🎨 Sistema de tema HCP inicializado');
});

/**
 * Exportar para uso em outros módulos
 */
export { ThemeToggle, ThemeTransitions };