/**
 * ===== SISTEMA DE TOGGLE PARA CURSOS =====
 * 
 * Este script gerencia a funcionalidade de ativar/desativar cursos
 * com animações e feedback visual
 */

document.addEventListener('DOMContentLoaded', function() {
    // Adicionar animação aos toggles
    const toggles = document.querySelectorAll('.toggle-switch');
    
    toggles.forEach(toggle => {
        const input = toggle.querySelector('input[type="checkbox"]');
        const track = toggle.querySelector('.peer');
        const slider = toggle.querySelector('.absolute');
        
        if (!toggle.closest('form')) return;
        
        // Adicionar efeito de animação ao submeter o formulário
        toggle.closest('form').addEventListener('submit', function() {
            // Adicionar classes de animação
            if (track) track.classList.add('toggle-pulse');
            if (slider) slider.classList.add('toggle-bounce');
            
            // Remover classes após a animação
            setTimeout(() => {
                if (track) track.classList.remove('toggle-pulse');
                if (slider) slider.classList.remove('toggle-bounce');
            }, 500);
        });
    });
    
    // Função de notificação
    window.showNotification = function(message, type = 'success') {
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
    
    // Adicionar estilos CSS para as animações
    const style = document.createElement('style');
    style.textContent = `
        @keyframes togglePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @keyframes toggleBounce {
            0% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
            100% { transform: translateY(0); }
        }
        
        .toggle-pulse {
            animation: togglePulse 0.5s ease-in-out;
        }
        
        .toggle-bounce {
            animation: toggleBounce 0.5s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});