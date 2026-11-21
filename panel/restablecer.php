<?php
$base        = '../';
$title       = 'Nueva contraseña · Panel de administración';
$styles      = ['pages/panel.css'];
$withSite    = false;
$noindex     = true;

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="panel-reset-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="panel-reset-title">Nueva contraseña</h1>
    <p class="text-body-secondary mb-3">Crea una contraseña nueva.</p>

    <form action="#" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="new-password">Nueva contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control" id="new-password" name="new_password" type="password"
                 autocomplete="new-password" aria-describedby="new-password-hint new-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="new-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="form-text" id="new-password-hint">Mínimo 8 caracteres, con una mayúscula y un número.</div>
        <div class="invalid-feedback fw-semibold" id="new-password-error">La contraseña necesita 8 caracteres, una mayúscula y un número.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="confirm-password">Confirmar contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control" id="confirm-password" name="confirm_password" type="password"
                 autocomplete="new-password" aria-describedby="confirm-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="confirm-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="invalid-feedback fw-semibold" id="confirm-password-error">Las contraseñas no coinciden.</div>
      </div>

      <button class="btn btn-primary w-100" type="submit">Guardar contraseña</button>
    </form>

    <p class="small text-body-secondary mt-3 mb-0">Al guardarla se cierran tus otras sesiones y el cambio queda en la bitácora. Si el enlace venció, <a href="recuperar.php">pide uno nuevo</a>.</p>
  </section>

  <p class="d-flex align-items-center gap-2 small text-body-secondary mb-0"><img src="<?= $base ?>assets/img/icons/lock-gray.svg" width="16" height="16" alt="">Acceso exclusivo para personal autorizado del plantel.</p>

</main>

<script src="<?= $base ?>assets/js/password-toggle.js"></script>
</body>
</html>
