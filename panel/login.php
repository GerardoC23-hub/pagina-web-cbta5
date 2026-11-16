<?php
$base        = '../';
$title       = 'Panel de administración · CBTa No. 5';
$styles      = ['pages/panel-login.css'];
$withSite    = false;   // no public header/footer on this page
$noindex     = true;    // private area: keep it out of search engines

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login" id="principal">

  <section class="panel-card" aria-labelledby="panel-login-title">
    <div class="panel-brand">
      <img src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div>
        <strong>Panel de administración</strong>
        <span>CBTa No. 5 · Huejutla de Reyes</span>
      </div>
    </div>

    <h1 class="panel-title" id="panel-login-title">Iniciar sesión</h1>
    <p class="panel-lead">Escribe tu usuario y contraseña.</p>

    <form action="#" method="post" novalidate>

      <div class="field">
        <label for="username">Usuario *</label>
        <input class="field-control" id="username" name="username" type="text"
               autocomplete="username" aria-describedby="username-error" required>
        <p class="field-error" id="username-error" hidden>Escribe tu usuario.</p>
      </div>

      <div class="field">
        <label for="password">Contraseña *</label>
        <div class="password-field">
          <input class="field-control" id="password" name="password" type="password"
                autocomplete="current-password" aria-describedby="password-error" required>
          <button class="password-toggle" type="button" data-toggle-password="password" aria-pressed="false">Mostrar</button>
        </div>
        <p class="field-error" id="password-error" hidden>Escribe tu contraseña.</p>
      </div>

      <button class="btn-main btn-block btn-lock" type="submit">Entrar al panel</button>
    </form>

    <p class="panel-note">Después de 5 intentos fallidos el acceso se bloquea 15 minutos. La sesión se cierra tras 30 minutos sin actividad.</p>
  </section>

  <p class="panel-footnote">Acceso exclusivo para personal autorizado del plantel.</p>

</main>

<script src="<?= $base ?>assets/js/password-toggle.js"></script>
</body>
</html>
