// alert-dismiss.js · los avisos de éxito y de error se cierran solos después de unos segundos.
// Si la persona pone el cursor encima, el aviso espera hasta que lo quite.
(function () {
  var SUCCESS_MS = 4000;
  var ERROR_MS   = 7000;

  document.querySelectorAll('.alert-success, .alert-danger').forEach(function (alert) {
    var time = alert.classList.contains('alert-danger') ? ERROR_MS : SUCCESS_MS;
    var timer;

    function close() {
      alert.classList.add('fade');
      alert.classList.remove('show');
      setTimeout(function () { alert.remove(); }, 400);
    }
    function start() { timer = setTimeout(close, time); }

    alert.classList.add('fade', 'show');
    alert.addEventListener('mouseenter', function () { clearTimeout(timer); });
    alert.addEventListener('mouseleave', start);
    start();
  });
})();
