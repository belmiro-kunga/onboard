<x-layouts.employee title="Notificações">
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-hcp-secondary-50 to-hcp-secondary-100 dark:from-hcp-secondary-900 dark:to-hcp-secondary-800 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-hcp-secondary-900 dark:text-white">
                        🔔 Notificações
                    </h1>
                    <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">
                        Gerencie suas notificações e mantenha-se atualizado
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    @if(isset($notifications) && $notifications->where('read_at', null)->count() > 0)
                        <button 
                            id="mark-all-read"
                            class="px-4 py-2 bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors text-sm font-medium"
                        >
                            Marcar todas como lidas
                        </button>
                    @endif
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-grow">
                        <label for="filter" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Filtrar por</label>
                        <select id="filter" class="w-full rounded-lg border-hcp-secondary-300 dark:border-hcp-secondary-600 text-hcp-secondary-700 dark:text-white bg-white dark:bg-hcp-secondary-700 focus:ring-hcp-500 focus:border-hcp-500">
                            <option value="all">Todas as notificações</option>
                            <option value="unread">Não lidas</option>
                            <option value="read">Lidas</option>
                        </select>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-hcp-secondary-700 dark:text-hcp-secondary-300 mb-1">Tipo</label>
                        <select id="type" class="w-full rounded-lg border-hcp-secondary-300 dark:border-hcp-secondary-600 text-hcp-secondary-700 dark:text-white bg-white dark:bg-hcp-secondary-700 focus:ring-hcp-500 focus:border-hcp-500">
                            <option value="all">Todos os tipos</option>
                            <option value="info">Informações</option>
                            <option value="success">Sucesso</option>
                            <option value="warning">Avisos</option>
                            <option value="error">Erros</option>
                            <option value="achievement">Conquistas</option>
                            <option value="level_up">Subida de nível</option>
                            <option value="points">Pontos</option>
                            <option value="module">Módulos</option>
                            <option value="quiz">Quizzes</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div id="notifications-container" class="space-y-4">
                @if(isset($notifications) && $notifications->count() > 0)
                    @foreach($notifications as $notification)
                        <div 
                            class="notification-item bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-sm border border-hcp-secondary-200 dark:border-hcp-secondary-700 p-4 transition-all duration-200 hover:shadow-md {{ $notification->read_at ? '' : 'border-l-4 border-l-hcp-500' }}" 
                            data-id="{{ $notification->id }}" 
                            data-read="{{ $notification->read_at ? 'true' : 'false' }}" 
                            data-type="{{ $notification->type }}"
                        >
                            <div class="flex items-start">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-hcp-secondary-100 text-hcp-secondary-600 dark:bg-hcp-secondary-800 dark:text-hcp-secondary-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM20 4v6h-2V4h2zM4 4v6h2V4H4z" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-hcp-secondary-900 dark:text-white mb-1">
                                                {{ $notification->title }}
                                            </h3>
                                            <p class="text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400 mb-2">
                                                {{ $notification->message }}
                                            </p>
                                            <div class="flex items-center space-x-4 text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400">
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                <span class="capitalize">{{ $notification->type }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$notification->read_at)
                                                <button 
                                                    class="mark-read-btn px-3 py-1 text-xs bg-hcp-500 text-white rounded-lg hover:bg-hcp-600 transition-colors"
                                                    data-id="{{ $notification->id }}"
                                                >
                                                    Marcar como lida
                                                </button>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-600 dark:text-green-400">
                                                    <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Lida
                                                </span>
                                            @endif
                                            
                                            <button 
                                                class="delete-btn px-3 py-1 text-xs bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                                                data-id="{{ $notification->id }}"
                                            >
                                                Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-hcp-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-hcp-secondary-900 dark:text-white">Nenhuma notificação</h3>
                        <p class="mt-1 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">Você não tem notificações no momento.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Mark as read functionality
        document.querySelectorAll('.mark-read-btn').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update notification item
                        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                        notificationItem.dataset.read = 'true';
                        notificationItem.classList.remove('border-l-4', 'border-l-hcp-500');
                        
                        // Replace button with "Lida" text
                        this.outerHTML = `
                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-600 dark:text-green-400">
                                <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Lida
                            </span>
                        `;
                        
                        // Update mark all button visibility
                        const unreadCount = document.querySelectorAll('[data-read="false"]').length;
                        const markAllBtn = document.getElementById('mark-all-read');
                        if (unreadCount === 0 && markAllBtn) {
                            markAllBtn.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    alert('Erro ao marcar notificação como lida. Tente novamente.');
                });
            });
        });

        // Mark all as read functionality
        const markAllBtn = document.getElementById('mark-all-read');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function() {
                fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update all notifications
                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.dataset.read = 'true';
                            item.classList.remove('border-l-4', 'border-l-hcp-500');
                        });
                        
                        // Replace all mark as read buttons
                        document.querySelectorAll('.mark-read-btn').forEach(btn => {
                            btn.outerHTML = `
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-600 dark:text-green-400">
                                    <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Lida
                                </span>
                            `;
                        });
                        
                        // Hide mark all button
                        this.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                    alert('Erro ao marcar todas as notificações como lidas. Tente novamente.');
                });
            });
        }

        // Delete functionality
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                
                if (confirm('Tem certeza que deseja excluir esta notificação?')) {
                    fetch(`/notifications/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove notification from UI
                            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                            notificationItem.remove();
                            
                            // Check if no notifications left
                            const remainingNotifications = document.querySelectorAll('.notification-item');
                            if (remainingNotifications.length === 0) {
                                document.getElementById('notifications-container').innerHTML = `
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-hcp-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-hcp-secondary-900 dark:text-white">Nenhuma notificação</h3>
                                        <p class="mt-1 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">Você não tem notificações no momento.</p>
                                    </div>
                                `;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting notification:', error);
                        alert('Erro ao excluir notificação. Tente novamente.');
                    });
                }
            });
        });
    </script>
    @endpush
</x-layouts.employee>