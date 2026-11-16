<?php $base = $base ?? ''; ?>
<footer class="site-footer">
  <div class="container">
    <div class="row gy-4 align-items-center text-center text-md-start footer-row">

      <div class="col-12 col-md-4 text-md-start">
        <a href="https://www.gob.mx/" target="_blank" rel="noopener" aria-label="Gobierno de México (se abre en una pestaña nueva)">
          <img class="footer-gov-logo" src="<?= $base ?>assets/img/logos/gobierno-de-mexico-blanco.svg" alt="Gobierno de México">
        </a>
      </div>

      <div class="col-12 col-md-4 text-center">
        <a href="<?= $base ?>index.php" aria-label="CBTa No. 5, ir al inicio">
          <img class="footer-crest" src="<?= $base ?>assets/img/logos/escudo-hidalgo.png" alt="Escudo del Estado de Hidalgo">
        </a>
      </div>

      <div class="col-12 col-md-4">
        <h2 class="footer-title">Contacto</h2>
        <address class="contact-address-block">
        <ul class="contact-list list-unstyled">
          <li class="contact-item contact-address"><span>Boulevard Adolfo López Mateos S/N, Col. Aviación Civil, C.P. 43000, Huejutla de Reyes, Hidalgo</span></li>
          <li class="contact-item contact-phone"><a href="tel:7898960065">789 896 0065</a></li>
          <li class="contact-item contact-email"><a href="mailto:hgo.cbta005.direccion@dgetaycm.sems.gob.mx">hgo.cbta005.direccion@dgetaycm.sems.gob.mx</a></li>
          <li class="contact-item contact-hours"><span>Lunes a viernes, 8:00 a 14:00 h</span> (Hora de atención)</li>
          <li class="contact-item contact-social"><a href="https://x.com/CBTA5HUEJUTLA" target="_blank" rel="noopener">X (Twitter): @CBTA5HUEJUTLA<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a></li>
        </ul>
        </address>
      </div>

    </div>
    <p class="footer-legal">© 2026 CBTa No. 5 · Huejutla de Reyes, Hidalgo, México. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php foreach (($scripts ?? []) as $script): ?>
<script src="<?= $base ?>assets/js/<?= $script ?>"></script>
<?php endforeach; ?>
</body>
</html>
