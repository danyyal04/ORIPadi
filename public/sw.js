// PadiGuard AI — Service Worker v1.0
// Cache-first for static assets, network-first for API calls

const CACHE_NAME    = 'padiguard-v1.0.6';
const OFFLINE_URL   = '/offline.html';

const PRECACHE_ASSETS = [
    '/',
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

// ── Install: pre-cache shell ──────────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(PRECACHE_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// ── Activate: clean old caches ────────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(keyList =>
                Promise.all(
                    keyList
                        .filter(key => key !== CACHE_NAME)
                        .map(key => caches.delete(key))
                )
            )
            .then(() => self.clients.claim())
    );
});

// ── Fetch strategy ────────────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET & API/analyze requests (always network for those)
    if (request.method !== 'GET' || url.pathname === '/analyze' || url.pathname === '/download-pdf') {
        return; // let browser handle normally
    }

    // External (CDN, fonts, APIs) — network-first, no cache
    if (!url.origin.includes(self.location.origin)) {
        event.respondWith(
            fetch(request).catch(() => new Response('', { status: 503 }))
        );
        return;
    }

    // App shell — cache-first with network fallback
    event.respondWith(
        caches.match(request)
            .then(cachedResponse => {
                if (cachedResponse) return cachedResponse;

                return fetch(request)
                    .then(networkResponse => {
                        // Only cache successful GET responses
                        if (networkResponse && networkResponse.status === 200) {
                            const cloned = networkResponse.clone();
                            caches.open(CACHE_NAME).then(cache => cache.put(request, cloned));
                        }
                        return networkResponse;
                    })
                    .catch(() => {
                        // Offline fallback for navigation
                        if (request.mode === 'navigate') {
                            return caches.match('/') || new Response(
                                '<h1>PadiGuard AI</h1><p>Anda sedang luar talian. Sila semak sambungan internet anda.</p>',
                                { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                            );
                        }
                        return new Response('', { status: 503 });
                    });
            })
    );
});

// ── Background sync placeholder ───────────────────────────────────────
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
