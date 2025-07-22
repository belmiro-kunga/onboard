<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="bg-purple-500/20 p-2 rounded-xl">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white">Análise de Engajamento</h3>
        </div>
        <div class="flex items-center space-x-2">
            <select id="engagement-period" class="bg-slate-700/50 border border-slate-600 text-white rounded-lg px-3 py-1 text-sm">
                <option value="7">7 dias</option>
                <option value="30" selected>30 dias</option>
                <option value="90">90 dias</option>
            </select>
            <button id="refresh-engagement" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Frequência de Login -->
        <div class="bg-slate-800/50 rounded-xl p-4">
            <h4 class="text-white font-semibold mb-4">Frequência de Login</h4>
            <div id="login-frequency" class="space-y-3">
                <!-- Dados carregados via JavaScript -->
                <div class="animate-pulse">
                    <div class="h-4 bg-slate-700 rounded mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded"></div>
                </div>
            </div>
        </div>

        <!-- Usuários Mais Ativos -->
        <div class="bg-slate-800/50 rounded-xl p-4">
            <h4 class="text-white font-semibold mb-4">Top 5 Mais Ativos</h4>
            <div id="most-active-users" class="space-y-3">
                <!-- Dados carregados via JavaScript -->
                <div class="animate-pulse">
                    <div class="h-4 bg-slate-700 rounded mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded"></div>
                </div>
            </div>
        </div>

        <!-- Usuários Menos Ativos -->
        <div class="bg-slate-800/50 rounded-xl p-4">
            <h4 class="text-white font-semibold mb-4">Precisam de Atenção</h4>
            <div id="least-active-users" class="space-y-3">
                <!-- Dados carregados via JavaScript -->
                <div class="animate-pulse">
                    <div class="h-4 bg-slate-700 rounded mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Atividade -->
    <div class="mt-6 bg-slate-800/50 rounded-xl p-4">
        <h4 class="text-white font-semibold mb-4">Atividade dos Últimos 30 Dias</h4>
        <div class="h-64 flex items-end justify-between space-x-1" id="activity-chart">
            <!-- Gráfico simples com barras -->
            <div class="animate-pulse flex space-x-1 h-full w-full">
                @for($i = 0; $i < 30; $i++)
                    <div class="bg-slate-700 rounded-t flex-1" style="height: {{ rand(20, 100) }}%"></div>
                @endfor
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadEngagementData();
    
    document.getElementById('refresh-engagement').addEventListener('click', loadEngagementData);
    document.getElementById('engagement-period').addEventListener('change', loadEngagementData);
});

function loadEngagementData() {
    const period = document.getElementById('engagement-period').value;
    
    // Carregar dados de engajamento
    fetch(`{{ route('admin.users.engagement-analysis') }}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            updateLoginFrequency(data.users_by_login_frequency);
            updateMostActiveUsers(data.most_active_users);
            updateLeastActiveUsers(data.least_active_users);
        })
        .catch(error => {
            console.error('Erro ao carregar dados de engajamento:', error);
        });
    
    // Carregar dados do gráfico
    fetch(`{{ route('admin.users.chart-data') }}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            updateActivityChart(data);
        })
        .catch(error => {
            console.error('Erro ao carregar gráfico:', error);
        });
}

function updateLoginFrequency(data) {
    const container = document.getElementById('login-frequency');
    
    const items = [
        { label: 'Login Diário', count: data.daily, color: 'bg-green-500', textColor: 'text-green-300' },
        { label: 'Login Semanal', count: data.weekly, color: 'bg-blue-500', textColor: 'text-blue-300' },
        { label: 'Login Mensal', count: data.monthly, color: 'bg-yellow-500', textColor: 'text-yellow-300' },
        { label: 'Nunca Logaram', count: data.never, color: 'bg-red-500', textColor: 'text-red-300' }
    ];
    
    container.innerHTML = items.map(item => `
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 ${item.color} rounded-full"></div>
                <span class="text-slate-300 text-sm">${item.label}</span>
            </div>
            <span class="${item.textColor} font-semibold">${item.count}</span>
        </div>
    `).join('');
}

function updateMostActiveUsers(users) {
    const container = document.getElementById('most-active-users');
    
    if (!users || users.length === 0) {
        container.innerHTML = '<p class="text-slate-400 text-sm text-center py-4">Nenhum dado disponível</p>';
        return;
    }
    
    container.innerHTML = users.map((user, index) => `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    ${index + 1}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">${user.name}</p>
                <p class="text-slate-400 text-xs">${user.completed_modules || 0} módulos completados</p>
            </div>
            <div class="text-green-400 text-xs font-semibold">
                ${user.completed_modules || 0}
            </div>
        </div>
    `).join('');
}

function updateLeastActiveUsers(users) {
    const container = document.getElementById('least-active-users');
    
    if (!users || users.length === 0) {
        container.innerHTML = '<p class="text-slate-400 text-sm text-center py-4">Nenhum dado disponível</p>';
        return;
    }
    
    container.innerHTML = users.map(user => `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&color=FFFFFF&background=EF4444" alt="">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">${user.name}</p>
                <p class="text-slate-400 text-xs">${user.completed_modules || 0} módulos completados</p>
            </div>
            <button class="text-blue-400 hover:text-blue-300 text-xs font-medium transition-colors" onclick="sendEngagementEmail('${user.id}')">
                Engajar
            </button>
        </div>
    `).join('');
}

function updateActivityChart(data) {
    const container = document.getElementById('activity-chart');
    
    if (!data.data || data.data.length === 0) {
        container.innerHTML = '<p class="text-slate-400 text-center">Nenhum dado disponível para o período selecionado</p>';
        return;
    }
    
    const maxValue = Math.max(...data.data);
    
    container.innerHTML = data.data.map((value, index) => {
        const height = maxValue > 0 ? (value / maxValue) * 100 : 0;
        const color = value > 0 ? 'bg-gradient-to-t from-blue-600 to-blue-400' : 'bg-slate-700';
        
        return `
            <div class="flex-1 flex flex-col items-center group cursor-pointer">
                <div class="${color} rounded-t transition-all duration-300 group-hover:from-blue-500 group-hover:to-blue-300 w-full" 
                     style="height: ${height}%" 
                     title="${data.labels[index]}: ${value} usuários">
                </div>
                <span class="text-xs text-slate-400 mt-1 transform rotate-45 origin-left">${data.labels[index]}</span>
            </div>
        `;
    }).join('');
}

function sendEngagementEmail(userId) {
    if (confirm('Enviar email de engajamento para este usuário?')) {
        // Implementar envio de email
        fetch(`/admin/users/${userId}/send-engagement-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Email de engajamento enviado com sucesso!');
            } else {
                alert('Erro ao enviar email. Tente novamente.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar email. Tente novamente.');
        });
    }
}
</script>