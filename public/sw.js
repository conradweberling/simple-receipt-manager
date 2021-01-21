/******/ (() => { // webpackBootstrap
/*!****************************!*\
  !*** ./resources/js/sw.js ***!
  \****************************/
var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = ['/srm/public/offline', '/srm/public/css/app.css', '/srm/public/js/app.js', '/srm/public/images/icons/receipt-128x128.png', '/srm/public/images/icons/receipt-512x512.png'];
self.addEventListener("install", function (event) {
  event.waitUntil(caches.open(staticCacheName).then(function (cache) {
    return cache.addAll(filesToCache);
  }));
});
self.addEventListener('activate', function (event) {
  event.waitUntil(caches.keys().then(function (cacheNames) {
    return Promise.all(cacheNames.filter(function (cacheName) {
      return cacheName.startsWith("pwa-");
    }).filter(function (cacheName) {
      return cacheName !== staticCacheName;
    }).map(function (cacheName) {
      return caches["delete"](cacheName);
    }));
  }));
});
self.addEventListener("fetch", function (event) {
  event.respondWith(caches.match(event.request).then(function (response) {
    return response || fetch(event.request);
  })["catch"](function () {
    return caches.match('offline');
  }));
});
/******/ })()
;