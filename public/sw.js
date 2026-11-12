// FrontalData PWA & Aggressive Asset Service Worker v1.2.0
const STATIC_CACHE_NAME = 'frontaldata-static-v1.2.0';
const IMAGE_CACHE_NAME = 'frontaldata-images-v1.2.0';

const STATIC_ASSETS = [
  '/',
  '/manifest.json',
  '/css/style.css',
  '/config.css',
  '/composer.js'
];

// Pre-cache all heavy generated pictures, banners, 3D icons, and brand graphics
const IMAGE_PRECACHE = [
  '/images/logo.png',
  '/images/smfavicon.png',
  '/images/favicon.ico',
  '/images/hero_showcase.jpg',
  '/images/banners/sme_data.jpg',
  '/images/services/data.jpg',
  '/images/services/airtime.jpg',
  '/images/services/tv.jpg',
  '/images/services/electricity.jpg',
  '/images/services/exam.jpg',
  '/images/services/betting.jpg',
  '/images/services/agent.jpg',
  '/images/bonus.png',
  '/images/mtn.png',
  '/images/airtel.png',
  '/images/glo.png',
  '/images/9mobile.png',
  '/images/dstv.png',
  '/images/gotv.png',
  '/images/startimes.png',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-512x512.png',
  '/images/icons/icon-maskable-512x512.png',
  '/images/icons/apple-touch-icon.png',
  // Matching /img/ paths for legacy blade references
  '/img/logo.png',
  '/img/hero_showcase.jpg',
  '/img/banners/sme_data.jpg',
  '/img/services/data.jpg',
  '/img/services/airtime.jpg',
  '/img/services/tv.jpg',
  '/img/services/electricity.jpg',
  '/img/services/exam.jpg',
  '/img/services/betting.jpg',
  '/img/services/agent.jpg',
  '/img/bonus.png'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    Promise.all([
      // Precache app shell static files
      caches.open(STATIC_CACHE_NAME).then((cache) => {
        return cache.addAll(STATIC_ASSETS).catch((err) => {
          console.warn('Static pre-cache partial notice:', err);
        });
      }),
      // Precache all generated heavy pictures and icons
      caches.open(IMAGE_CACHE_NAME).then((cache) => {
        return Promise.all(
          IMAGE_PRECACHE.map((url) => {
            return fetch(url, { cache: 'reload' })
              .then((response) => {
                if (response.ok) {
                  return cache.put(url, response);
                }
              })
              .catch((err) => {
                console.warn('Image pre-cache skip for:', url, err);
              });
          })
        );
      })
    ]).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== STATIC_CACHE_NAME && key !== IMAGE_CACHE_NAME) {
            console.log('Clearing old service worker cache:', key);
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const request = event.request;
  const url = new URL(request.url);

  // Skip non-GET requests, chrome-extension, and non-http(s)
  if (request.method !== 'GET' || !url.protocol.startsWith('http')) {
    return;
  }

  // 1. Navigation requests: Network-First (keeps live wallet balance & transaction feeds fresh)
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => response)
        .catch(() => caches.match('/') || caches.match('/dashboard'))
    );
    return;
  }

  // 2. Heavy Images & Graphics: CACHE-FIRST (Instant 0ms disk response, background network revalidation)
  const isImage = 
    url.pathname.startsWith('/images/') ||
    url.pathname.startsWith('/img/') ||
    /\.(png|jpe?g|webp|svg|gif|ico|avif)$/i.test(url.pathname);

  if (isImage) {
    event.respondWith(
      caches.open(IMAGE_CACHE_NAME).then((cache) => {
        return cache.match(request).then((cachedResponse) => {
          if (cachedResponse) {
            // Background revalidation: fetch newest version without blocking UI
            fetch(request).then((networkResponse) => {
              if (networkResponse && networkResponse.status === 200) {
                cache.put(request, networkResponse.clone());
              }
            }).catch(() => {/* offline or network jitter */});

            return cachedResponse;
          }

          // Not in cache: fetch from network and store permanently in IMAGE_CACHE
          return fetch(request).then((networkResponse) => {
            if (networkResponse && networkResponse.status === 200) {
              cache.put(request, networkResponse.clone());
            }
            return networkResponse;
          });
        });
      })
    );
    return;
  }

  // 3. Static Assets (CSS, JS, Fonts): Cache-First with fallback
  const isStatic = 
    url.pathname.startsWith('/css/') ||
    /\.(css|js|woff2?|ttf|eot)$/i.test(url.pathname);

  if (isStatic) {
    event.respondWith(
      caches.open(STATIC_CACHE_NAME).then((cache) => {
        return cache.match(request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }
          return fetch(request).then((networkResponse) => {
            if (networkResponse && networkResponse.status === 200) {
              cache.put(request, networkResponse.clone());
            }
            return networkResponse;
          });
        });
      })
    );
    return;
  }

  // 4. Default: Stale-While-Revalidate
  event.respondWith(
    caches.match(request).then((cachedResponse) => {
      const fetchPromise = fetch(request).then((networkResponse) => {
        if (networkResponse && networkResponse.status === 200) {
          caches.open(STATIC_CACHE_NAME).then((cache) => {
            cache.put(request, networkResponse.clone());
          });
        }
        return networkResponse;
      }).catch(() => cachedResponse);

      return cachedResponse || fetchPromise;
    })
  );
});
