<?php $base = $base ?? ''; ?>
<footer class="bg-brand text-white border-top border-4 border-gold mt-5 pt-4">
  <div class="container">
    <div class="row gy-4 align-items-center text-center text-md-start">

      <div class="col-12 col-md-4 text-md-start">
        <a class="link-light" href="https://www.gob.mx/" target="_blank" rel="noopener" aria-label="Gobierno de México (se abre en una pestaña nueva)">
          <img height="84" src="<?= $base ?>assets/img/logos/gobierno-de-mexico-blanco.svg" alt="Gobierno de México">
        </a>
      </div>

      <div class="col-12 col-md-4 text-center">
        <a class="link-light" href="<?= $base ?>index.php" aria-label="CBTa No. 5, ir al inicio">
          <img height="150" src="<?= $base ?>assets/img/logos/escudo-hidalgo.png" alt="Escudo del Estado de Hidalgo">
        </a>
      </div>

      <div class="col-12 col-md-4">
        <h2 class="h5 mb-3">Contacto</h2>
        <address class="mb-0">
        <ul class="list-unstyled d-inline-block d-md-block text-start mb-0">
          <li class="d-flex gap-3 mb-2"><img class="flex-shrink-0" src="<?= $base ?>assets/img/icons/location.svg" width="22" height="22" alt=""><span>Boulevard Adolfo López Mateos S/N, Col. Aviación Civil, C.P. 43000, Huejutla de Reyes, Hidalgo</span></li>
          <li class="d-flex gap-3 mb-2"><img class="flex-shrink-0" src="<?= $base ?>assets/img/icons/phone.svg" width="22" height="22" alt=""><a class="link-light fw-semibold" href="tel:7898960065">789 896 0065</a></li>
          <li class="d-flex gap-3 mb-2"><img class="flex-shrink-0" src="<?= $base ?>assets/img/icons/email.svg" width="22" height="22" alt=""><a class="link-light fw-semibold text-break" href="mailto:hgo.cbta005.direccion@dgetaycm.sems.gob.mx">hgo.cbta005.direccion@dgetaycm.sems.gob.mx</a></li>
          <li class="d-flex gap-3 mb-2"><img class="flex-shrink-0" src="<?= $base ?>assets/img/icons/hours.svg" width="22" height="22" alt=""><span>Lunes a viernes, 8:00 a 14:00 h (Hora de atención)</span></li>
          <li class="d-flex gap-3 mb-2"><img class="flex-shrink-0" src="<?= $base ?>assets/img/icons/link.svg" width="22" height="22" alt=""><a class="link-light fw-semibold text-break" href="https://x.com/CBTA5HUEJUTLA" target="_blank" rel="noopener">X (Twitter): @CBTA5HUEJUTLA<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a></li>
        </ul>
        </address>
      </div>

    </div>
    <p class="border-top border-light border-opacity-25 mt-4 mb-0 py-3 text-center small">© 2026 CBTa No. 5 · Huejutla de Reyes, Hidalgo, México. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php foreach (($scripts ?? []) as $script): ?>
<script src="<?= $base ?>assets/js/<?= $script ?>"></script>
<?php endforeach; ?>
</body>
</html>
