// gallery.js · visor de la galería: muestra la foto o el video elegido y navega con Anterior / Siguiente
(function () {
  var cards = Array.prototype.slice.call(document.querySelectorAll('[data-viewer]'));
  var modal = document.getElementById('viewer');
  if (!modal || cards.length === 0) { return; }

  var media   = document.getElementById('viewer-media');
  var title   = document.getElementById('viewer-title');
  var caption = document.getElementById('viewer-caption');
  var count   = document.getElementById('viewer-count');
  var prev    = document.getElementById('viewer-prev');
  var next    = document.getElementById('viewer-next');
  var current = 0;

  function show(index) {
    current = index;
    var data = cards[index].dataset;
    var element;

    if (data.kind === 'embed') {
      element = document.createElement('div');
      element.className = 'ratio ratio-16x9';
      var frame = document.createElement('iframe');
      frame.src = data.src;
      frame.title = data.title;
      frame.allowFullscreen = true;
      frame.setAttribute('allow', 'accelerometer; encrypted-media; picture-in-picture');
      element.appendChild(frame);
    } else if (data.kind === 'video') {
      element = document.createElement('video');
      element.className = 'w-100';
      element.controls = true;
      element.src = data.src;
    } else if (data.kind === 'link') {
      element = document.createElement('a');
      element.className = 'btn btn-primary';
      element.href = data.src;
      element.target = '_blank';
      element.rel = 'noopener';
      element.textContent = 'Ver el video';
    } else {
      element = document.createElement('img');
      element.className = 'photo';
      element.src = data.src;
      element.alt = data.alt;
    }
    media.replaceChildren(element);

    title.textContent = data.title;
    caption.textContent = (data.album ? data.album + ' · ' : '') + 'Crédito: ' + (data.credit || 'Por confirmar');
    count.textContent = (index + 1) + ' de ' + cards.length;
    prev.disabled = index === 0;
    next.disabled = index === cards.length - 1;
  }

  cards.forEach(function (card, index) {
    card.addEventListener('click', function () { show(index); });
  });
  prev.addEventListener('click', function () { if (current > 0) { show(current - 1); } });
  next.addEventListener('click', function () { if (current < cards.length - 1) { show(current + 1); } });

  // Al cerrar se vacía el visor para que un video deje de reproducirse
  modal.addEventListener('hidden.bs.modal', function () { media.replaceChildren(); });
})();
