const CACHE_NAME = "labor-pwa-v1";
const OFFLINE_URL = "/offline.html";

// Install event - cache essential files
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then((cache) => {
                return cache
                    .addAll([
                        "/",
                        "/offline.html",
                        "/manifest.webmanifest",
                        "/img/lb-blue.svg",
                    ])
                    .catch((err) => {
                        console.warn("Cache addAll error:", err);
                    });
            })
            .then(() => self.skipWaiting()),
    );
});

// Activate event - clean old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => {
                return Promise.all(
                    keys
                        .filter((key) => key !== CACHE_NAME)
                        .map((key) => caches.delete(key)),
                );
            })
            .then(() => self.clients.claim()),
    );
});

// Fetch event - network first, fallback to cache
self.addEventListener("fetch", (event) => {
    const { request } = event;

    // Skip non-GET requests
    if (request.method !== "GET") return;

    // Handle navigation requests (page loads)
    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response.status === 404) {
                        return caches.match(OFFLINE_URL);
                    }
                    // Cache successful responses
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches
                        .match(request)
                        .then((cached) => cached || caches.match(OFFLINE_URL));
                }),
        );
        return;
    }

    // Handle API and other requests - cache first, network fallback
    event.respondWith(
        caches.match(request).then((cached) => {
            return (
                cached ||
                fetch(request)
                    .then((response) => {
                        // Only cache successful responses
                        if (response.status === 200) {
                            const responseClone = response.clone();
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(request, responseClone);
                            });
                        }
                        return response;
                    })
                    .catch(() => {
                        // Return offline page if network fails
                        return caches.match(OFFLINE_URL);
                    })
            );
        }),
    );
});
