<!-- Toast Notification Component -->
<div id="toast-container" class="fixed top-4 right-4 z-[9998] space-y-2 pointer-events-none">
    <!-- Toasts will be dynamically added here -->
</div>

<style>
/* Toast Notification Styles */
.toast-notification {
    /* Ensure proper stacking */
    z-index: 9998;
    
    /* Smooth animations */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Mobile optimizations */
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
}

/* Toast entrance animation */
@keyframes toast-slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes toast-slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Toast types */
.toast-notification[data-type="success"] {
    border-left: 4px solid #10b981;
}

.toast-notification[data-type="error"] {
    border-left: 4px solid #ef4444;
}

.toast-notification[data-type="info"] {
    border-left: 4px solid #3b82f6;
}

.toast-notification[data-type="warning"] {
    border-left: 4px solid #f59e0b;
}

/* Mobile responsive */
@media (max-width: 640px) {
    #toast-container {
        top: env(safe-area-inset-top, 1rem);
        right: 1rem;
        left: 1rem;
    }
    
    .toast-notification {
        max-width: none;
        margin-bottom: 0.5rem;
    }
}

/* Dark mode adjustments */
[data-theme="dark"] .toast-notification {
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(20px);
    border-color: rgba(148, 163, 184, 0.2);
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .toast-notification {
        border-width: 2px;
    }
    
    .toast-notification[data-type="success"] {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .toast-notification[data-type="error"] {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .toast-notification[data-type="info"] {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .toast-notification[data-type="warning"] {
        border-color: #f59e0b;
        background: #fffbeb;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .toast-notification {
        transition: none;
    }
    
    .toast-notification * {
        animation: none !important;
        transition: none !important;
    }
}

/* Landscape mode adjustments */
@media (orientation: landscape) and (max-height: 500px) {
    #toast-container {
        top: 0.5rem;
    }
    
    .toast-notification {
        padding: 0.75rem;
    }
    
    .toast-notification h4 {
        font-size: 0.875rem;
    }
    
    .toast-notification p {
        font-size: 0.75rem;
    }
}

/* Focus styles for accessibility */
.toast-notification button:focus {
    outline: 2px solid #0ea5e9;
    outline-offset: 2px;
}

/* Swipe to dismiss */
.toast-notification {
    position: relative;
    overflow: hidden;
}

.toast-notification::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    pointer-events: none;
}

[data-theme="dark"] .toast-notification::before {
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.05) 50%, transparent 100%);
}

.toast-notification.swiping::before {
    transform: translateX(100%);
}
</style>

<script>
// Toast Notification JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Global toast function
    window.showToast = function(type, message, duration = 4000) {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
        const id = Date.now() + Math.random();
        const toast = createToast(id, type, message);
        
        container.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            removeToast(id);
        }, duration);
        
        // Haptic feedback
        hapticFeedback();
        
        return id;
    };
    
    function createToast(id, type, message) {
        const toast = document.createElement('div');
        toast.className = `toast-notification bg-white dark:bg-hcp-secondary-800 rounded-xl shadow-2xl border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-4 max-w-sm pointer-events-auto backdrop-blur-lg transform translate-x-full opacity-0 transition-all duration-300`;
        toast.setAttribute('data-toast-id', id);
        toast.setAttribute('data-type', type);
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'polite');
        
        // Add type-specific styling
        const typeClasses = {
            success: 'border-green-200 dark:border-green-700',
            error: 'border-red-200 dark:border-red-700',
            info: 'border-blue-200 dark:border-blue-700',
            warning: 'border-yellow-200 dark:border-yellow-700'
        };
        
        toast.classList.add(typeClasses[type] || typeClasses.info);
        
        toast.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center ${getIconBgClass(type)}">
                        ${getIcon(type)}
                    </div>
                    <h4 class="font-semibold text-hcp-secondary-900 dark:text-white">${getTitle(type)}</h4>
                </div>
                <button onclick="removeToast(${id})" 
                        class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-hcp-secondary-400 hover:text-hcp-secondary-600 dark:hover:text-hcp-secondary-300 transition-colors duration-200"
                        aria-label="Fechar notificação">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-3">${message}</p>
            <div class="w-full bg-hcp-secondary-200 dark:bg-hcp-secondary-600 rounded-full h-1 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-300 ease-linear ${getProgressClass(type)}" style="width: 100%"></div>
            </div>
        `;
        
        // Add swipe to dismiss functionality
        addSwipeToDismiss(toast, id);
        
        return toast;
    }
    
    function getIconBgClass(type) {
        const classes = {
            success: 'bg-green-100 dark:bg-green-900',
            error: 'bg-red-100 dark:bg-red-900',
            info: 'bg-blue-100 dark:bg-blue-900',
            warning: 'bg-yellow-100 dark:bg-yellow-900'
        };
        return classes[type] || classes.info;
    }
    
    function getIcon(type) {
        const icons = {
            success: '<svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
            error: '<svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',
            info: '<svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
            warning: '<svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
        };
        return icons[type] || icons.info;
    }
    
    function getTitle(type) {
        const titles = {
            success: 'Sucesso',
            error: 'Erro',
            info: 'Informação',
            warning: 'Aviso'
        };
        return titles[type] || titles.info;
    }
    
    function getProgressClass(type) {
        const classes = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };
        return classes[type] || classes.info;
    }
    
    function removeToast(id) {
        const toast = document.querySelector(`[data-toast-id="${id}"]`);
        if (toast) {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }
    
    function hapticFeedback() {
        if ('vibrate' in navigator) {
            navigator.vibrate(50);
        }
    }
    
    function addSwipeToDismiss(toast, id) {
        let startX = 0;
        let currentX = 0;
        
        toast.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        }, { passive: true });
        
        toast.addEventListener('touchmove', function(e) {
            currentX = e.touches[0].clientX;
            const deltaX = currentX - startX;
            
            if (deltaX > 50) {
                toast.classList.add('swiping');
            } else {
                toast.classList.remove('swiping');
            }
        }, { passive: true });
        
        toast.addEventListener('touchend', function() {
            const deltaX = currentX - startX;
            
            if (deltaX > 100) {
                removeToast(id);
            }
            
            toast.classList.remove('swiping');
        });
    }
    
    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const toasts = document.querySelectorAll('.toast-notification');
            if (toasts.length > 0) {
                const lastToast = toasts[toasts.length - 1];
                const id = lastToast.getAttribute('data-toast-id');
                if (id) {
                    removeToast(parseInt(id));
                }
            }
        }
    });
    
    // Auto-hide toasts when page loses focus
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(toast => {
                const id = toast.getAttribute('data-toast-id');
                if (id) {
                    removeToast(parseInt(id));
                }
            });
        }
    });
});

// Toast types for easy access
window.ToastType = {
    SUCCESS: 'success',
    ERROR: 'error',
    INFO: 'info',
    WARNING: 'warning'
};

// Global remove function
window.removeToast = function(id) {
    const toast = document.querySelector(`[data-toast-id="${id}"]`);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }
};

// Example usage:
// showToast(ToastType.SUCCESS, 'Operação realizada com sucesso!');
// showToast(ToastType.ERROR, 'Ocorreu um erro. Tente novamente.');
// showToast(ToastType.INFO, 'Nova funcionalidade disponível!');
// showToast(ToastType.WARNING, 'Atenção: dados não salvos.');
</script> 