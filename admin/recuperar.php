<?php
require __DIR__ . '/../includes/panel-auth.php';

// Datos que muestra la pantalla (paso 1: correo)
$step = 1;
require __DIR__ . '/../backend/admin/acceso/recuperar-datos.php';

// Configuración de la página
$base      = '../';
$title     = 'Recuperar contraseña · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$cardTitle = 'Recuperar contraseña';
$cardText  = 'Escribe el correo de tu usuario.';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/admin-card-start.php';
?>
    <form action="../backend/admin/acceso/recuperar-guardar.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="recovery-email">Correo electrónico *</label>
        <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="recovery-email" name="email" type="email" autocomplete="email" value="<?= h($email) ?>" aria-describedby="recovery-email-error" required>
        <div class="invalid-feedback fw-semibold" id="recovery-email-error"><?= h($errors['email'] ?? 'Correo no válido.') ?></div>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">Enviar código</button>
        <a class="btn btn-outline-primary" href="../backend/admin/acceso/recuperar-cancelar.php">Cancelar</a>
      </div>
    </form>

    <p class="small text-body-secondary mt-3 mb-0">Si el correo está registrado, recibirás un código de 6 números que vence en 10 minutos.</p>
<?php require __DIR__ . '/../includes/admin-card-end.php'; ?>

</body>
</html>
