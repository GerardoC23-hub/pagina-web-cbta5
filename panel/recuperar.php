<?php
$base        = '../';
$title       = 'Recuperar contraseña · Panel de administración';
$styles      = ['pages/panel.css'];
$withSite    = false;
$noindex     = true;

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="panel-recover-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="panel-recover-title">Recuperar contraseña</h1>
    <p class="text-body-secondary mb-3">Escribe el correo de tu usuario.</p>

    <form action="#" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="recovery-email">Correo electrónico *</label>
        <input class="form-control" id="recovery-email" name="email" type="email"
               autocomplete="email" aria-describedby="recovery-email-error" required>
        <div class="invalid-feedback fw-semibold" id="recovery-email-error">Escribe un correo válido, por ejemplo nombre@correo.com.</div>
      </div>

      <button class="btn btn-primary w-100" type="submit">Enviar enlace</button>
    </form>

    <p class="small text-body-secondary mt-3 mb-0">Si el correo está registrado, recibirás un enlace que sirve una sola vez y vence en 30 minutos. El mensaje es el mismo aunque el correo no exista. Si no tienes correo registrado, pide a un administrador que restablezca tu cuenta.</p>
    <p class="small text-body-secondary mt-3 mb-0"><a href="login.php">Volver al inicio de sesión</a></p>
  </section>

  <p class="d-flex align-items-center gap-2 small text-body-secondary mb-0"><img src="<?= $base ?>assets/img/icons/lock-gray.svg" width="16" height="16" alt="">Acceso exclusivo para personal autorizado del plantel.</p>

</main>

</body>
</html>
