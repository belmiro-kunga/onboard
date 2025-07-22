/**
 * Sistema de Notificações em Tempo Real
 * Gerencia notificações via WebSockets e fallback para polling
 */

class NotificationManager {
    constructor() {
        this.isConnected = false;
        this.retryCount = 0;
        this.maxRetries = 5;
        this.pollingInterval = null;
        this.lastNotificationId = null;
        
        this.init();
    }

    init() {
        // Tentar conectar via WebSocket primeiro
        if (window.Echo) {
            this.connectWebSocket();
        } else {
            // Fallback para polling
            this.startPolling();
        }
        
        // Configurar listeners para eventos de notificação
        this.setupEventListeners();
    }

    connectWebSocket() {
        try {
            // Escutar notificações do usuário atual
            if (window.HCP.user) {
                window.Echo.private(`user.${window.HCP.user.id}`)
                    .listen('.notification.received', (data) => {
                        this.handleNewNotification(data);
                    });
                
                this.isConnected = true;
                console.log('WebSocket conectado para notificações');
            }
        } catch (error) {
            console.warn('Erro ao conectar WebSocket, usando polling:', error);
            this.startPolling();
        }
    }

    startPolling() {
        // Polling a cada 30 segundos como fallback
        this.pollingInterval = setInterval(() => {
            this.checkForNewNotifications();
        }, 30000);
        
        console.log('Polling iniciado para notificações');
    }

    async checkForNewNotifications() {
        try {
            const response = await fetch('/api/notifications/latest');
            const data = await response.json();
            
            if (data.notifications && data.notifications.length > 0) {
                const latestNotification = data.notifications[0];
                
                // Verificar se é uma nova notificação
                if (this.lastNotificationId !== latestNotification.id) {
                    this.handleNewNotification(latestNotification);
                    this.lastNotificationId = latestNotification.id;
                }
            }
            
            // Atualizar contador
            this.updateNotificationCount(data.unread_count);
            
        } catch (error) {
            console.error('Erro ao verificar novas notificações:', error);
        }
    }

    handleNewNotification(notification) {
        // Mostrar toast
        this.showToast(notification);
        
        // Atualizar contador no sino
        this.updateNotificationBell();
        
        // Reproduzir som (se permitido)
        this.playNotificationSound();
        
        // Vibrar (mobile)
        this.vibrate();
    }
} 
   showToast(notification) {
        // Verificar se já existe um container de toast
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 flex flex-col space-y-4';
            document.body.appendChild(container);
        }
        
        // Criar elemento do toast
        const toast = document.createElement('div');
        toast.className = `max-w-sm bg-white dark:bg-hcp-secondary-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 ease-out opacity-0 translate-y-2`;
        
        // Definir cor baseada no tipo
        let bgColor, iconHtml;
        switch (notification.type) {
            case 'success':
                bgColor = 'bg-green-50 dark:bg-green-900/20';
                iconHtml = `<svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                break;
            case 'warning':
                bgColor = 'bg-yellow-50 dark:bg-yellow-900/20';
                iconHtml = `<svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`;
                break;
            case 'error':
                bgColor = 'bg-red-50 dark:bg-red-900/20';
                iconHtml = `<svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                break;
            case 'achievement':
                bgColor = 'bg-purple-50 dark:bg-purple-900/20';
                iconHtml = `<svg class="h-6 w-6 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>`;
                break;
            default:
                bgColor = 'bg-blue-50 dark:bg-blue-900/20';
                iconHtml = `<svg class="h-6 w-6 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
        }
        
        // Conteúdo do toast
        toast.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        ${iconHtml}
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-hcp-secondary-900 dark:text-white">${notification.title}</p>
                        <p class="mt-1 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">${notification.message}</p>
                        ${notification.link ? `<a href="${notification.link}" class="mt-2 text-xs text-hcp-500 hover:text-hcp-600 dark:hover:text-hcp-400 font-medium">Ver detalhes</a>` : ''}
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="bg-white dark:bg-transparent rounded-md inline-flex text-hcp-secondary-400 hover:text-hcp-secondary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Adicionar toast ao container
        container.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
        }, 10);
        
        // Configurar botão de fechar
        const closeButton = toast.querySelector('button');
        closeButton.addEventListener('click', () => {
            this.removeToast(toast, container);
        });
        
        // Auto remover após 5 segundos
        setTimeout(() => {
            this.removeToast(toast, container);
        }, 5000);
    }

    removeToast(toast, container) {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-2');
        
        setTimeout(() => {
            toast.remove();
            
            // Remover container se vazio
            if (container.children.length === 0) {
                container.remove();
            }
        }, 300);
    }

    updateNotificationBell() {
        // Atualizar contador no sino de notificações
        fetch('/api/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                this.updateNotificationCount(data.unread_count);
            })
            .catch(error => {
                console.error('Erro ao atualizar contador de notificações:', error);
            });
    }

    updateNotificationCount(count) {
        // Atualizar todos os elementos com contador de notificações
        const counters = document.querySelectorAll('[data-notification-count]');
        counters.forEach(counter => {
            counter.textContent = count > 99 ? '99+' : count;
            counter.style.display = count > 0 ? 'block' : 'none';
        });
        
        // Atualizar Alpine.js se disponível
        if (window.Alpine) {
            window.Alpine.store('notifications', { unreadCount: count });
        }
    }

    playNotificationSound() {
        // Reproduzir som de notificação (se permitido pelo usuário)
        try {
            const audio = new Audio('/sounds/notification.mp3');
            audio.volume = 0.3;
            audio.play().catch(() => {
                // Ignorar erro se não conseguir reproduzir
            });
        } catch (error) {
            // Ignorar erro de áudio
        }
    }

    vibrate() {
        // Vibrar em dispositivos móveis
        if ('vibrate' in navigator) {
            navigator.vibrate([200, 100, 200]);
        }
    }

    setupEventListeners() {
        // Listener para quando a página fica visível novamente
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && !this.isConnected) {
                this.checkForNewNotifications();
            }
        });
        
        // Listener para reconexão de rede
        window.addEventListener('online', () => {
            if (!this.isConnected) {
                this.init();
            }
        });
    }

    destroy() {
        // Limpar polling
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        
        // Desconectar WebSocket
        if (window.Echo && this.isConnected) {
            window.Echo.leaveChannel(`user.${window.HCP.user.id}`);
        }
    }
}

// Inicializar gerenciador de notificações quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    if (window.HCP.user) {
        window.notificationManager = new NotificationManager();
    }
});

// Função global para mostrar toast (compatibilidade)
window.showToast = function(type, message, title = null) {
    const notification = {
        type: type,
        title: title || 'Notificação',
        message: message
    };
    
    if (window.notificationManager) {
        window.notificationManager.showToast(notification);
    }
};

export default NotificationManager;