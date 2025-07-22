import './bootstrap';
import './theme-toggle';
import Alpine from 'alpinejs';

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();

/**
 * Sistema de Toast Notifications
 */
window.showToast = function(type, message, duration = 5000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `
        flex items-center p-4 mb-4 text-sm rounded-hcp-lg shadow-hcp-lg
        transform transition-all duration-300 ease-in-out
        translate-x-full opacity-0
        ${getToastClasses(type)}
    `;

    toast.innerHTML = `
        <div class="flex items-center">
            ${getToastIcon(type)}
            <div class="ml-3 text-sm font-medium">${message}</div>
        </div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-hcp p-1.5 hover:bg-black hover:bg-opacity-10 focus:ring-2 focus:ring-gray-300" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    // Animar entrada
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    });

    // Auto remover
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, duration);
};

function getToastClasses(type) {
    const classes = {
        success: 'text-green-800 bg-green-50 dark:bg-green-800 dark:text-green-200 border border-green-200 dark:border-green-700',
        error: 'text-red-800 bg-red-50 dark:bg-red-800 dark:text-red-200 border border-red-200 dark:border-red-700',
        warning: 'text-yellow-800 bg-yellow-50 dark:bg-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-700',
        info: 'text-blue-800 bg-blue-50 dark:bg-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700',
    };
    return classes[type] || classes.info;
}

function getToastIcon(type) {
    const icons = {
        success: `<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>`,
        error: `<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>`,
        warning: `<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>`,
        info: `<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>`,
    };
    return icons[type] || icons.info;
}

/**
 * Sistema de Loading States
 */
window.showLoading = function(element, text = 'Carregando...') {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    
    if (!element) return;
    
    element.disabled = true;
    element.dataset.originalText = element.textContent;
    element.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        ${text}
    `;
};

window.hideLoading = function(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    
    if (!element) return;
    
    element.disabled = false;
    element.textContent = element.dataset.originalText || 'Enviar';
    delete element.dataset.originalText;
};

/**
 * Utilitários para formulários
 */
window.submitForm = function(form, options = {}) {
    if (typeof form === 'string') {
        form = document.querySelector(form);
    }
    
    if (!form) return;
    
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton?.textContent;
    
    // Mostrar loading
    if (submitButton) {
        showLoading(submitButton, options.loadingText || 'Enviando...');
    }
    
    // Fazer requisição
    const formData = new FormData(form);
    const url = form.action || window.location.href;
    const method = form.method || 'POST';
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Operação realizada com sucesso!');
            if (options.onSuccess) {
                options.onSuccess(data);
            }
        } else {
            showToast('error', data.message || 'Erro ao processar solicitação.');
            if (options.onError) {
                options.onError(data);
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('error', 'Erro de conexão. Tente novamente.');
        if (options.onError) {
            options.onError(error);
        }
    })
    .finally(() => {
        // Esconder loading
        if (submitButton) {
            hideLoading(submitButton);
        }
        if (options.onComplete) {
            options.onComplete();
        }
    });
};

/**
 * Utilitários para modais
 */
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
};

/**
 * Utilitários para animações
 */
window.animateElement = function(element, animation, duration = 600) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    
    if (!element) return;
    
    element.style.animationDuration = `${duration}ms`;
    element.classList.add(`animate-${animation}`);
    
    setTimeout(() => {
        element.classList.remove(`animate-${animation}`);
    }, duration);
};

/**
 * Inicialização quando DOM estiver pronto
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Sistema HCP Onboarding inicializado');
    
    // Configurar CSRF token para requisições AJAX
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
    
    // Event listeners globais
    document.addEventListener('click', function(e) {
        // Auto-close dropdowns
        if (!e.target.closest('[data-dropdown]')) {
            document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // ESC para fechar modais
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.flex').forEach(modal => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
            document.body.classList.remove('overflow-hidden');
        }
    });
});

/**
 * Service Worker para PWA (se disponível)
 */
// Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
                
                // Forçar atualização do Service Worker
                registration.update();
                
                // Verificar se há uma nova versão
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // Nova versão disponível
                            console.log('Nova versão do Service Worker disponível');
                            
                            // Opcional: Mostrar notificação para o usuário
                            if (confirm('Uma nova versão está disponível. Deseja atualizar?')) {
                                newWorker.postMessage({ type: 'SKIP_WAITING' });
                                window.location.reload();
                            }
                        }
                    });
                });
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}