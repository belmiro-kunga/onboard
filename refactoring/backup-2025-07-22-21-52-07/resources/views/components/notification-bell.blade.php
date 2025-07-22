@props(['unreadCount' => 0])

<div class="relative" x-data="notificationBell()">
    <!-- Notification Bell Button -->
    <button 
        @click="toggleDropdown"
        class="relative p-2 text-hcp-secondary-600 hover:text-hcp-secondary-900 dark:text-hcp-secondary-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hcp-500 rounded-full"
        aria-label="Notificações"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        <!-- Unread Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        @click.away="isOpen = false"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-hcp-secondary-800 rounded-lg shadow-lg py-1 z-50 overflow-hidden"
        style="max-height: 90vh; overflow-y: auto;"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-hcp-secondary-900 dark:text-white">Notificações</h3>
            <div class="flex space-x-2">
                @if($unreadCount > 0)
                    <button 
                        @click="markAllAsRead"
                        class="text-xs text-hcp-500 hover:text-hcp-600 dark:hover:text-hcp-400 font-medium"
                    >
                        Marcar todas como lidas
                    </button>
                @endif
                <a href="{{ route('notifications.index') }}" class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-secondary-900 dark:hover:text-white">
                    Ver todas
                </a>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="px-4 py-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-hcp-500"></div>
            <p class="mt-2 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400">Carregando notificações...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && notifications.length === 0" class="px-4 py-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-hcp-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="mt-2 text-sm font-medium text-hcp-secondary-900 dark:text-white">Nenhuma notificação</p>
            <p class="mt-1 text-sm text-hcp-secondary-500 dark:text-hcp-secondary-400">Você não tem notificações não lidas.</p>
        </div>

        <!-- Notification List -->
        <div x-show="!loading && notifications.length > 0" class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="px-4 py-3 hover:bg-hcp-secondary-50 dark:hover:bg-hcp-secondary-700 border-b border-hcp-secondary-200 dark:border-hcp-secondary-700 last:border-b-0 transition-colors duration-150 ease-in-out">
                    <div class="flex items-start">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-hcp-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <h4 class="text-sm font-medium text-hcp-secondary-900 dark:text-white" x-text="notification.title"></h4>
                                <p class="ml-2 text-xs text-hcp-secondary-500 dark:text-hcp-secondary-400" x-text="notification.created_at"></p>
                            </div>
                            <p class="mt-1 text-sm text-hcp-secondary-600 dark:text-hcp-secondary-400" x-text="notification.message"></p>
                            
                            <!-- Actions -->
                            <div class="mt-2 flex items-center justify-between">
                                <div>
                                    <a 
                                        x-show="notification.link"
                                        :href="notification.link"
                                        class="text-xs text-hcp-500 hover:text-hcp-600 dark:hover:text-hcp-400 font-medium"
                                    >
                                        Ver detalhes
                                    </a>
                                </div>
                                <div class="flex space-x-2">
                                    <button 
                                        @click="markAsRead(notification.id)"
                                        x-show="!notification.read_at"
                                        class="text-xs text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-secondary-900 dark:hover:text-white"
                                    >
                                        Marcar como lida
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function notificationBell() {
        return {
            isOpen: false,
            loading: true,
            unreadCount: {{ $unreadCount }},
            notifications: [],
            
            init() {
                this.fetchNotifications();
            },
            
            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.fetchNotifications();
                }
            },
            
            fetchNotifications() {
                this.loading = true;
                
                fetch('/api/notifications/latest')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.notifications || [];
                        this.unreadCount = data.unread_count || 0;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching notifications:', error);
                        this.loading = false;
                    });
            },
            
            markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update notification in the list
                        const index = this.notifications.findIndex(n => n.id === id);
                        if (index !== -1) {
                            this.notifications[index].read_at = new Date().toISOString();
                        }
                        
                        // Update unread count
                        this.unreadCount = data.unread_count;
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            },
            
            markAllAsRead() {
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
                        // Mark all notifications as read
                        this.notifications.forEach(notification => {
                            notification.read_at = new Date().toISOString();
                        });
                        
                        // Update unread count
                        this.unreadCount = 0;
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                });
            }
        };
    }
</script>
@endpush