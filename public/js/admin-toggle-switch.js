/**
 * ===== SISTEMA DE TOGGLE PARA CURSOS =====
 * 
 * Este script gerencia a funcionalidade de ativar/desativar cursos
 * com animações e feedback visual via AJAX
 */

document.addEventListener('DOMContentLoaded', function() {
    // Seleciona todos os toggles na página
    const toggles = document.querySelectorAll('.toggle-switch');
    
    toggles.forEach(toggle => {
        const courseId = toggle.dataset.courseId;
        const url = toggle.dataset.url;
        const input = toggle.querySelector('input[type="checkbox"]');
        const track = toggle.querySelector('.peer');
        const slider = toggle.querySelector('.absolute');
        const statusText = toggle.nextElementSibling;
        const loadingSpinner = toggle.querySelector('.loading-spinner');
        
        if (!input || !url) return;
        
        // Adiciona evento de clique ao input
        input.addEventListener('change', function(e) {
            e.preventDefault();
            
            // Desabilita o toggle durante a requisição
            input.disabled = true;
            
            // Mostra o spinner de loading
            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
                loadingSpinner.classList.add('flex');
            }
            
            // Adiciona animação ao toggle
            if (track) {
                track.classList.add('toggle-animate');
            }
            
            if (slider) {
                slider.classList.add('toggle-bounce');
            }
            
            // Obtém o token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Faz a requisição AJAX
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                // Atualiza o estado do toggle de acordo com a resposta
                input.checked = data.is_active;
                
                // Atualiza o texto de status
                if (statusText) {
                    statusText.textContent = data.is_active ? 'Ativo' : 'Inativo';
                    statusText.className = `ml-2 text-xs font-medium status-text ${data.is_active ? 'text-green-600' : 'text-gray-500'}`;
                }
                
                // Atualiza a classe do track
                if (track) {
                    if (data.is_active) {
                        track.classList.add('bg-green-500');
                    } else {
                        track.classList.remove('bg-green-500');
                    }
                }
                
                // Atualiza a posição do slider
                if (slider) {
                    if (data.is_active) {
                        slider.classList.add('translate-x-5');
                    } else {
                        slider.classList.remove('translate-x-5');
                    }
                }
                
                // Mostra notificação de sucesso
                showNotification(data.message, 'success');
            })
            .catch(error => {
                console.error('Erro:', error);
                
                // Reverte o estado do toggle em caso de erro
                input.checked = !input.checked;
                
                // Mostra notificação de erro
                showNotification('Erro ao alterar status do curso', 'error');
            })
            .finally(() => {
                // Reabilita o toggle
                input.disabled = false;
                
                // Esconde o spinner de loading
                if (loadingSpinner) {
                    loadingSpinner.classList.add('hidden');
                    loadingSpinner.classList.remove('flex');
                }
                
                // Remove as classes de animação
                if (track) {
                    setTimeout(() => track.classList.remove('toggle-animate'), 500);
                }
                
                if (slider) {
                    setTimeout(() => slider.classList.remove('toggle-bounce'), 500);
                }
            });
        });
    });
    
    // Função para mostrar notificações
    window.showNotification = function(message, type = 'success') {
        // Remove notificação existente se houver
        const existingNotification = document.querySelector('.notification-toast');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Cria nova notificação
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        
        notification.innerHTML = `
            <div class="flex items-center space-x-3">
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
        
        // Anima a entrada
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 10);
        
        // Remove após 3 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    };
    
    // Adiciona estilos CSS para as animações
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
        
        .toggle-animate {
            animation: togglePulse 0.5s ease-in-out;
        }
        
        .toggle-bounce {
            animation: toggleBounce 0.5s ease-in-out;
        }
        
        .notification-toast {
            transition: all 0.3s ease-in-out;
        }
        
        /* Estilos para o toggle switch */
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
});
