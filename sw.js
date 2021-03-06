var CACHE_NAME = 'static-v3';
self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return cache.addAll([
        '/logoscar3/',
        '/logoscar3/index.php',
        '/logoscar3/css/*.css',
        '/logoscar3/js/.js',
        '/logoscar3/manifest.js',
        '/logoscar3/img/*',
        '/logoscar3/less/*'
      ]);
    })
  )
});
self.addEventListener('activate', function activator(event) {
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(keys
        .filter(function (key) {
          return key.indexOf(CACHE_NAME) !== 0;
        })
        .map(function (key) {
          return caches.delete(key);
        })
      );
    })
  );
});
self.addEventListener('fetch', function (event) {
  event.respondWith(
    caches.match(event.request).then(function (cachedResponse) {
      return cachedResponse || fetch(event.request);
    })
  );
});