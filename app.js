if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('sw.js')
        .then(function() {
            console.log('ServiceWorker Registrado!');
        })
        .catch(function(err) {
            console.log('ServiceWorker No registrado', err);
        });
    });
  }
