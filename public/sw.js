// Service Worker for সাতক্ষীরা-২ ভোটার তালিকা PWA
const CACHE_NAME = 'satkhira-voter-v3';
const STATIC_CACHE = 'satkhira-static-v3';
const DATA_CACHE = 'satkhira-data-v3';

// Files to cache immediately (offline.html is self-contained now)
const STATIC_FILES = [
    '/',
    '/manifest.json',
    '/offline.html'
];

// Install Service Worker
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker v3...');
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            console.log('[SW] Caching static files');
            return cache.addAll(STATIC_FILES);
        })
    );
    self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker v3...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    // Delete all old caches (v1, v2, etc.)
                    if (cacheName !== STATIC_CACHE && cacheName !== DATA_CACHE && cacheName !== CACHE_NAME) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
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
