// Dynamic Service Worker for TaRL Assessment System
// Version: 1.0.0 - Automatically detects Vite asset paths

const CACHE_NAME = 'tarl-v1';
const RUNTIME_CACHE = 'tarl-runtime-v1';
const DATA_CACHE = 'tarl-data-v1';

// Dynamic asset detection
let STATIC_ASSETS = [
  '/offline.html',
  '/manifest.json',
  // Khmer fonts
  'https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Khmer&display=swap'
];

// Dynamically load Vite manifest and get correct asset paths
async function loadViteAssets() {
  try {
    const manifestResponse = await fetch('/build/manifest.json');
    const manifest = await manifestResponse.json();
    
    // Extract CSS and JS file paths
    if (manifest['resources/css/app.css']) {
      STATIC_ASSETS.push('/build/' + manifest['resources/css/app.css'].file);
    }
    if (manifest['resources/js/app.js']) {
      STATIC_ASSETS.push('/build/' + manifest['resources/js/app.js'].file);
    }
    
    console.log('Service Worker: Loaded Vite assets', STATIC_ASSETS);
  } catch (error) {
    console.warn('Service Worker: Could not load Vite manifest, using fallback assets', error);
    // Fallback to current known assets
    STATIC_ASSETS.push('/build/assets/app-CTTiNE_I.css');
    STATIC_ASSETS.push('/build/assets/app-C4KU62kP.js');
  }
}

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
    loadViteAssets().then(() => {
      return caches.open(CACHE_NAME)
        .then(cache => {
          console.log('Service Worker: Caching critical assets', STATIC_ASSETS);
          return cache.addAll(STATIC_ASSETS);
        });
    }).then(() => self.skipWaiting())
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

// Fetch event - improved navigation handling
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip cross-origin requests
  if (url.origin !== location.origin) {
    return;
  }

  // CRITICAL: Handle navigation requests (page loads) - NETWORK FIRST
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then(response => {
          // Always cache successful navigation responses
          if (response.ok) {
            caches.open(RUNTIME_CACHE)
              .then(cache => cache.put(request, response.clone()));
          }
          return response;
        })
        .catch(() => {
          // Only return cached version if completely offline
          return caches.match(request)
            .then(cached => cached || caches.match('/offline.html'));
        })
    );
    return;
  }

  // Handle API requests with network-first strategy
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      fetch(request)
        .then(response => {
          // Cache successful GET requests
          if (request.method === 'GET' && response.status === 200) {
            caches.open(DATA_CACHE)
              .then(cache => cache.put(request, response.clone()));
          }
          return response;
        })
        .catch(() => {
          // Return cached data only if offline
          if (request.method === 'GET') {
            return caches.open(DATA_CACHE)
              .then(cache => cache.match(request));
          }
          // For POST/PUT/DELETE, let it fail naturally
          throw new Error('Network error and no cache available');
        })
    );
    return;
  }

  // Handle static assets (CSS, JS, images) with cache-first strategy
  if (request.destination === 'style' || 
      request.destination === 'script' || 
      request.destination === 'image' ||
      url.pathname.startsWith('/build/') ||
      url.pathname.startsWith('/images/') ||
      url.pathname.includes('.css') ||
      url.pathname.includes('.js') ||
      url.pathname.includes('.png') ||
      url.pathname.includes('.jpg') ||
      url.pathname.includes('.svg')) {
    
    event.respondWith(
      caches.match(request)
        .then(cached => {
          if (cached) {
            return cached;
          }
          
          return fetch(request)
            .then(response => {
              if (response.ok) {
                const clonedResponse = response.clone();
                caches.open(RUNTIME_CACHE)
                  .then(cache => cache.put(request, clonedResponse));
              }
              return response;
            });
        })
    );
    return;
  }

  // For all other requests, just let them pass through normally
  // This prevents the service worker from interfering with form submissions, etc.
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

console.log('🔧 Dynamic Service Worker loaded - will auto-detect Vite assets');