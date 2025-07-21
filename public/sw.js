/**
 * Service Worker para HCP Onboarding System
 * Versão melhorada para PWA com experiência nativa
 */

// Service Worker para HCP Onboarding
const CACHE_NAME = 'hcp-onboarding-v1.0.0';
const STATIC_CACHE = 'hcp-static-v1.0.0';
const DYNAMIC_CACHE = 'hcp-dynamic-v1.0.0';

// URLs para cache estático
const STATIC_URLS = [
    '/',
    '/offline.html',
    '/css/app.css',
    '/js/app.js',
    '/images/hcp-logo.png',
    '/images/hcp-building.jpg',
    '/manifest.json'
];

// Estratégias de cache
const CACHE_STRATEGIES = {
    // Cache First para recursos estáticos
    CACHE_FIRST: 'cache-first',
    // Network First para dados dinâmicos
    NETWORK_FIRST: 'network-first',
    // Stale While Revalidate para recursos que podem ser atualizados
    STALE_WHILE_REVALIDATE: 'stale-while-revalidate'
};

// Instalação do Service Worker
self.addEventListener('install', event => {
    console.log('[SW] Installing Service Worker...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('[SW] Caching static assets');
                return cache.addAll(STATIC_URLS);
            })
            .then(() => {
                console.log('[SW] Static assets cached successfully');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('[SW] Error caching static assets:', error);
            })
    );
});

// Ativação do Service Worker
self.addEventListener('activate', event => {
    console.log('[SW] Activating Service Worker...');
    
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('[SW] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('[SW] Service Worker activated');
                return self.clients.claim();
            })
    );
});

// Interceptação de requisições
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Ignorar requisições para APIs externas
    if (url.origin !== self.location.origin) {
        return;
    }
    
    // Estratégia baseada no tipo de recurso
    if (isStaticAsset(request)) {
        event.respondWith(cacheFirst(request));
    } else if (isApiRequest(request)) {
        event.respondWith(networkFirst(request));
    } else {
        event.respondWith(staleWhileRevalidate(request));
    }
});

// Verificar se é um recurso estático
function isStaticAsset(request) {
    const staticExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.woff', '.woff2'];
    const url = new URL(request.url);
    
    return staticExtensions.some(ext => url.pathname.endsWith(ext)) ||
           STATIC_URLS.includes(url.pathname);
}

// Verificar se é uma requisição de API
function isApiRequest(request) {
    const url = new URL(request.url);
    return url.pathname.startsWith('/api/') || 
           url.pathname.startsWith('/auth/') ||
           request.method === 'POST' ||
           request.method === 'PUT' ||
           request.method === 'DELETE';
}

// Estratégia Cache First
async function cacheFirst(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('[SW] Cache First error:', error);
        return new Response('Offline - Recurso não disponível', { status: 503 });
    }
}

// Estratégia Network First
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.log('[SW] Network failed, trying cache:', error);
        
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Retornar página offline para navegação
        if (request.mode === 'navigate') {
            return caches.match('/offline.html');
        }
        
        return new Response('Offline - Dados não disponíveis', { status: 503 });
    }
}

// Estratégia Stale While Revalidate
async function staleWhileRevalidate(request) {
    const cache = await caches.open(DYNAMIC_CACHE);
    const cachedResponse = await cache.match(request);
    
    const fetchPromise = fetch(request).then(networkResponse => {
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    }).catch(error => {
        console.error('[SW] Network error:', error);
        return cachedResponse;
    });
    
    return cachedResponse || fetchPromise;
}

// Background Sync para requisições offline
self.addEventListener('sync', event => {
    console.log('[SW] Background sync triggered:', event.tag);
    
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    try {
        // Sincronizar dados offline
        const offlineData = await getOfflineData();
        
        for (const data of offlineData) {
            try {
                await fetch(data.url, {
                    method: data.method,
                    headers: data.headers,
                    body: data.body
                });
                
                // Remover dados sincronizados
                await removeOfflineData(data.id);
            } catch (error) {
                console.error('[SW] Background sync error:', error);
            }
        }
    } catch (error) {
        console.error('[SW] Background sync failed:', error);
    }
}

// Push Notifications
self.addEventListener('push', event => {
    console.log('[SW] Push notification received');
    
    const options = {
        body: event.data ? event.data.text() : 'Nova notificação do HCP Onboarding',
        icon: '/images/icons/icon-192x192.png',
        badge: '/images/icons/badge-72x72.png',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Ver Detalhes',
                icon: '/images/icons/checkmark.png'
            },
            {
                action: 'close',
                title: 'Fechar',
                icon: '/images/icons/xmark.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('HCP Onboarding', options)
    );
});

// Clique em notificação push
self.addEventListener('notificationclick', event => {
    console.log('[SW] Notification clicked:', event.action);
    
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    } else if (event.action === 'close') {
        // Apenas fechar a notificação
    } else {
        // Ação padrão - abrir a aplicação
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Fechar notificação
self.addEventListener('notificationclose', event => {
    console.log('[SW] Notification closed');
    
    // Track notification close
    if (typeof trackEvent === 'function') {
        trackEvent('notification_closed', {
            notification_id: event.notification.data?.primaryKey
        });
    }
});

// Mensagens do cliente
self.addEventListener('message', event => {
    console.log('[SW] Message received:', event.data);
    
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
    
    if (event.data && event.data.type === 'CACHE_URLS') {
        event.waitUntil(
            caches.open(DYNAMIC_CACHE)
                .then(cache => cache.addAll(event.data.urls))
        );
    }
});

// Funções auxiliares para dados offline
async function getOfflineData() {
    // Implementar lógica para obter dados salvos offline
    return [];
}

async function removeOfflineData(id) {
    // Implementar lógica para remover dados sincronizados
    console.log('[SW] Removing offline data:', id);
}

// Health check
self.addEventListener('fetch', event => {
    if (event.request.url.includes('/sw-health')) {
        event.respondWith(
            new Response(JSON.stringify({
                status: 'healthy',
                version: CACHE_NAME,
                timestamp: new Date().toISOString()
            }), {
                headers: { 'Content-Type': 'application/json' }
            })
        );
    }
});