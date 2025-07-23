/**
 * ===== SISTEMA DE TOGGLE PARA CURSOS =====
 * 
 * Este script gerencia a funcionalidade de ativar/desativar cursos
 * com animações e feedback visual
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sistema de Toggle para Cursos
    const toggleSwitches = document.querySelectorAll('.toggle-switch');
    
    toggleSwitches.forEach(toggleSwitch => {
        const input = toggleSwitch.querySelector('.toggle-input');
        const loadingSpinner = toggleSwitch.querySelector('.loading-spinner');
        const statusText = toggleSwitch.parentElement.querySelector('.status-text');
        const courseId = toggleSwitch.dataset.courseId;
        const url = toggleSwitch.dataset.url;
        const toggleTrack = toggleSwitch.querySelector('.peer');
        const toggleSlider = toggleSwitch.querySelector('.toggle-slider');
        
        // Adicionar animação ao toggle
        input.addEventListener('change', async function(e) {
            e.preventDefault();
            
            // Desabilita o toggle durante a requisição
            input.disabled = true;
            loadingSpinner.classList.remove('hidden');
            
            // Adicionar animação ao toggle
            toggleTrack.classList.add('toggle-animation');
            toggleSlider.classList.add('toggle-circle-animation');
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    // Atualiza o estado visual
                    const isActive = data.is_active;
                    input.checked = isActive;
                    
                    // Atualiza o texto de status
                    if (statusText) {
                        statusText.textContent = isActive ? 'Ativo' : 'Inativo';
                        statusText.className = `ml-2 text-xs font-medium status-text ${isActive ? 'text-green-600' : 'text-gray-500'}`;
                    }
                    
                    // Atualiza o estilo do toggle
                    if (isActive) {
                        toggleTrack.classList.add('bg-green-500');
                        toggleTrack.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                        toggleSlider.classList.add('translate-x-5');
                    } else {
                        toggleTrack.classList.remove('bg-green-500');
                        toggleTrack.classList.add('bg-gray-200', 'dark:bg-gray-700');
                        toggleSlider.classList.remove('translate-x-5');
                    }
                    
                    // Atualiza a coluna de status na tabela
                    const row = toggleSwitch.closest('tr');
                    const statusColumn = row.querySelector('td:nth-child(4)');
                    if (statusColumn) {
                        statusColumn.innerHTML = isActive 
                            ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                Ativo
                               </span>`
                            : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                                Inativo
                               </span>`;
                    }
                    
                    // Mostra notificação de sucesso
                    if (typeof showNotification === 'function') {
                        showNotification(
                            `Curso ${isActive ? 'ativado' : 'desativado'} com sucesso!`, 
                            'success'
                        );
                    }
                    
                } else {
                    // Reverte o estado do toggle
                    input.checked = !input.checked;
                    
                    // Atualiza o estilo do toggle
                    if (input.checked) {
                        toggleTrack.classList.add('bg-green-500');
                        toggleTrack.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                        toggleSlider.classList.add('translate-x-5');
                    } else {
                        toggleTrack.classList.remove('bg-green-500');
                        toggleTrack.classList.add('bg-gray-200', 'dark:bg-gray-700');
                        toggleSlider.classList.remove('translate-x-5');
                    }
                    
                    // Mostra notificação de erro
                    if (typeof showNotification === 'function') {
                        showNotification('Erro ao alterar status do curso.', 'error');
                    }
                }
            } catch (error) {
                console.error('Erro:', error);
                
                // Reverte o estado do toggle
                input.checked = !input.checked;
                
                // Atualiza o estilo do toggle
                if (input.checked) {
                    toggleTrack.classList.add('bg-green-500');
                    toggleTrack.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                    toggleSlider.classList.add('translate-x-5');
                } else {
                    toggleTrack.classList.remove('bg-green-500');
                    toggleTrack.classList.add('bg-gray-200', 'dark:bg-gray-700');
                    toggleSlider.classList.remove('translate-x-5');
                }
                
                // Mostra notificação de erro
                if (typeof showNotification === 'function') {
                    showNotification('Erro ao alterar status do curso.', 'error');
                }
            } finally {
                // Habilita o toggle novamente
                input.disabled = false;
                loadingSpinner.classList.add('hidden');
                
                // Remove as classes de animação
                setTimeout(() => {
                    toggleTrack.classList.remove('toggle-animation');
                    toggleSlider.classList.remove('toggle-circle-animation');
                }, 500);
            }
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
        
        @keyframes toggleCircleJump {
            0% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-3px) translateX(0); }
            50% { transform: translateY(0) translateX(0); }
            75% { transform: translateY(-2px) translateX(0); }
            100% { transform: translateY(0) translateX(0); }
        }
        
        .toggle-animation {
            animation: togglePulse 0.5s ease-in-out;
        }
        
        .toggle-circle-animation {
            animation: toggleCircleJump 0.5s ease-in-out;
        }
        
        /* Melhorias visuais para o toggle */
        .toggle-switch .peer {
            transition: all 0.3s ease-in-out;
        }
        
        .toggle-switch .toggle-slider {
            transition: all 0.3s ease-in-out;
        }
        
        .toggle-switch:hover .toggle-slider {
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.6);
        }
    `;
    document.head.appendChild(style);
});