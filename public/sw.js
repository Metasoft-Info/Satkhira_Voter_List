// Service Worker for সাতক্ষীরা-২ ভোটার তালিকা PWA
// Version 5 - Force cache clear on update
const CACHE_VERSION = 'v5';
const CACHE_NAME = 'satkhira-voter-' + CACHE_VERSION;
const STATIC_CACHE = 'satkhira-static-' + CACHE_VERSION;
const DATA_CACHE = 'satkhira-data-' + CACHE_VERSION;

// Files to cache immediately (offline.html is self-contained now)
const STATIC_FILES = [
    '/',
    '/manifest.json',
    '/offline.html'
];

// Install Service Worker - Skip waiting to activate immediately
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker ' + CACHE_VERSION + '...');
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            console.log('[SW] Caching static files');
            return cache.addAll(STATIC_FILES);
        })
    );
    // Force immediate activation
    self.skipWaiting();
});

// Activate Service Worker - Clear ALL old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker ' + CACHE_VERSION + '...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    // Delete ALL caches that don't match current version
                    if (!cacheName.includes(CACHE_VERSION)) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            // Force all clients to use this service worker
            return self.clients.claim();
        })
    );
});

// Fetch Handler - Network first, fallback to cache
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip admin routes
    if (url.pathname.startsWith('/admin')) return;

    // For API requests - Network first, cache fallback
    if (url.pathname.startsWith('/api') || url.pathname === '/search') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone and cache the response
                    const responseClone = response.clone();
                    caches.open(DATA_CACHE).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(request);
                })
        );
        return;
    }

    // For static files - Cache first, network fallback
    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
                // Update cache in background
                fetch(request).then((response) => {
                    caches.open(STATIC_CACHE).then((cache) => {
                        cache.put(request, response);
                    });
                });
                return cachedResponse;
            }

            return fetch(request)
                .then((response) => {
                    // Cache the new response
                    const responseClone = response.clone();
                    caches.open(STATIC_CACHE).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    // Return offline page for navigation requests
                    if (request.mode === 'navigate') {
                        return caches.match('/offline.html');
                    }
                });
        })
    );
});

// Listen for messages from the main thread
self.addEventListener('message', (event) => {
    if (event.data.type === 'CACHE_VOTERS') {
        console.log('[SW] Caching voter data...');
        // This will be handled by IndexedDB in the main thread
    }
});
