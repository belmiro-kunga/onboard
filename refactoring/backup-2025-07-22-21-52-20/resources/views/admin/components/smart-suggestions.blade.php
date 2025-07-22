<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="bg-amber-500/20 p-2 rounded-xl">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white">Sugestões Inteligentes</h3>
        </div>
        <button id="refresh-suggestions" class="text-slate-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>

    <div id="suggestions-container" class="space-y-4">
        <!-- Sugestões serão carregadas via JavaScript -->
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-amber-400"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSuggestions();
    
    document.getElementById('refresh-suggestions').addEventListener('click', function() {
        loadSuggestions();
    });
});

function loadSuggestions() {
    const container = document.getElementById('suggestions-container');
    
    // Mostrar loading
    container.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-amber-400"></div>
        </div>
    `;
    
    fetch('{{ route("admin.users.action-suggestions") }}')
        .then(response => response.json())
        .then(suggestions => {
            if (suggestions.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="bg-green-500/20 p-4 rounded-xl inline-block mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-slate-300 font-medium">Tudo está funcionando perfeitamente!</p>
                        <p class="text-slate-400 text-sm mt-1">Não há ações recomendadas no momento.</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = suggestions.map(suggestion => {
                const iconColor = {
                    'warning': 'text-amber-400',
                    'info': 'text-blue-400',
                    'security': 'text-red-400',
                    'success': 'text-green-400'
                }[suggestion.type] || 'text-slate-400';
                
                const bgColor = {
                    'warning': 'bg-amber-500/10 border-amber-500/20',
                    'info': 'bg-blue-500/10 border-blue-500/20',
                    'security': 'bg-red-500/10 border-red-500/20',
                    'success': 'bg-green-500/10 border-green-500/20'
                }[suggestion.type] || 'bg-slate-500/10 border-slate-500/20';
                
                const priorityBadge = suggestion.priority === 'high' 
                    ? '<span class="bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded-full">Alta Prioridade</span>'
                    : suggestion.priority === 'medium'
                    ? '<span class="bg-yellow-500/20 text-yellow-300 text-xs px-2 py-1 rounded-full">Média Prioridade</span>'
                    : '<span class="bg-blue-500/20 text-blue-300 text-xs px-2 py-1 rounded-full">Baixa Prioridade</span>';
                
                return `
                    <div class="border ${bgColor} rounded-xl p-4 hover:bg-opacity-20 transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${getSuggestionIcon(suggestion.type)}
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-white font-semibold">${suggestion.title}</h4>
                                    ${priorityBadge}
                                </div>
                                <p class="text-slate-300 text-sm mb-3">${suggestion.message}</p>
                                <button class="bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm px-4 py-2 rounded-lg transition-colors">
                                    ${suggestion.action}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Erro ao carregar sugestões:', error);
            container.innerHTML = `
                <div class="text-center py-8">
                    <div class="bg-red-500/20 p-4 rounded-xl inline-block mb-4">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-slate-300 font-medium">Erro ao carregar sugestões</p>
                    <p class="text-slate-400 text-sm mt-1">Tente novamente em alguns instantes.</p>
                </div>
            `;
        });
}

function getSuggestionIcon(type) {
    const icons = {
        'warning': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>',
        'info': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'security': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        'success': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    };
    return icons[type] || icons['info'];
}
</script>