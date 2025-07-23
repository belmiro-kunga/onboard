/**
 * ===== SISTEMA DE TOGGLE PARA CURSOS - VERSÃO SIMPLIFICADA =====
 * 
 * Este script gerencia a funcionalidade de ativar/desativar cursos
 * com animações e feedback visual
 */

document.addEventListener('DOMContentLoaded', function() {
    // Adicionar animação aos toggles
    const toggles = document.querySelectorAll('.toggle-switch');
    
    toggles.forEach(toggle => {
        const form = toggle.closest('form');
        if (!form) return;
        
        // Adicionar efeito de animação ao submeter o formulário
        form.addEventListener('submit', function() {
            // Adicionar classe de animação ao toggle
            toggle.classList.add('toggle-animate');
            
            // Remover classe após a animação
            setTimeout(() => {
                toggle.classList.remove('toggle-animate');
            }, 500);
        });
    });
    
    // Adicionar estilos CSS para as animações
    const style = document.createElement('style');
    style.textContent = `
        @keyframes togglePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .toggle-animate {
            animation: togglePulse 0.5s ease-in-out;
        }
        
        /* Melhorias visuais para o toggle */
        .toggle-switch {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        
        .toggle-switch .peer {
            transition: all 0.3s ease-in-out;
        }
        
        .toggle-switch .absolute {
            transition: all 0.3s ease-in-out;
        }
    `;
    document.head.appendChild(style);
    
    // Função de notificação simples
    window.showNotification = function(message, type = 'success') {
        console.log(`${type}: ${message}`);
        
        // Criar elemento de notificação
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${type === 'success' 
                        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>'
                    }
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Remover após 3 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    };
});