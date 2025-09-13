// Service Worker for TaRL Assessment System
// Version: 1.0.0

const CACHE_NAME = 'tarl-v1';
const RUNTIME_CACHE = 'tarl-runtime-v1';
const DATA_CACHE = 'tarl-data-v1';

// Critical assets to cache for offline use
const STATIC_ASSETS = [
  '/offline.html',
  '/build/assets/app-CTTiNE_I.css',
  '/build/assets/app-C4KU62kP.js',
  '/manifest.json',
  // Khmer fonts
  'https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Khmer&display=swap'
];

// Pages to pre-cache for offline access
const OFFLINE_PAGES = [
  '/dashboard',
  '/students',
  '/assessments',
  '/reports/my-students'
];

// Install event - cache critical assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Caching critical assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(name => name !== CACHE_NAME && name !== DATA_CACHE && name !== RUNTIME_CACHE)
          .map(name => caches.delete(name))
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip cross-origin requests
  if (url.origin !== location.origin) {
    return;
  }

  // Handle API requests with cache-first strategy for GET requests
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      caches.open(DATA_CACHE).then(cache => {
        return fetch(request)
          .then(response => {
            // Cache successful GET requests
            if (request.method === 'GET' && response.status === 200) {
              cache.put(request, response.clone());
            }
            return response;
          })
          .catch(() => {
            // Return cached data if offline
            return cache.match(request);
          });
      })
    );
    return;
  }

  // Handle static assets with cache-first strategy
  if (request.method === 'GET') {
    event.respondWith(
      caches.match(request)
        .then(cached => {
          const fetched = fetch(request)
            .then(response => {
              const clonedResponse = response.clone();
              
              caches.open(RUNTIME_CACHE)
                .then(cache => cache.put(request, clonedResponse));
              
              return response;
            })
            .catch(() => cached);

          return cached || fetched;
        })
        .catch(() => {
          // Return offline page for navigation requests
          if (request.mode === 'navigate') {
            return caches.match('/offline.html');
          }
        })
    );
  }
});

// Background sync for offline form submissions
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-assessments') {
    event.waitUntil(syncAssessments());
  }
});

// Sync offline assessments when connection is restored
async function syncAssessments() {
  const cache = await caches.open('offline-assessments');
  const requests = await cache.keys();
  
  return Promise.all(
    requests.map(async (request) => {
      const response = await cache.match(request);
      const data = await response.json();
      
      try {
        const result = await fetch(request.url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data.csrf_token
          },
          body: JSON.stringify(data)
        });
        
        if (result.ok) {
          await cache.delete(request);
          // Notify user of successful sync
          self.registration.showNotification('TaRL System', {
            body: 'Offline assessments synced successfully',
            icon: '/images/icon-192x192.png',
            badge: '/images/icon-72x72.png'
          });
        }
      } catch (error) {
        console.error('Sync failed:', error);
      }
    })
  );
}

// Message handler for cache updates
self.addEventListener('message', (event) => {
  if (event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
  
  if (event.data.action === 'clearCache') {
    caches.keys().then(names => {
      names.forEach(name => {
        if (name === DATA_CACHE) {
          caches.delete(name);
        }
      });
    });
  }
});

// Periodic background sync for data freshness
self.addEventListener('periodicsync', (event) => {
  if (event.tag === 'update-data') {
    event.waitUntil(updateCachedData());
  }
});

async function updateCachedData() {
  const cache = await caches.open(DATA_CACHE);
  
  // Update critical data endpoints
  const endpoints = [
    '/api/dashboard/stats',
    '/api/students',
    '/api/assessments'
  ];
  
  return Promise.all(
    endpoints.map(endpoint => 
      fetch(endpoint)
        .then(response => {
          if (response.ok) {
            return cache.put(endpoint, response);
          }
        })
        .catch(console.error)
    )
  );
}