<?php
require __DIR__ . '/../includes/panel-auth.php';

// Datos que muestra la pantalla (paso 3: contraseña nueva; solo si el código ya se verificó)
$step = 3;
require __DIR__ . '/../backend/admin/acceso/recuperar-datos.php';

// Configuración de la página
$base      = '../';
$title     = 'Nueva contraseña · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$cardTitle = 'Nueva contraseña';
$cardText  = 'Código correcto. Crea tu contraseña nueva.';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/admin-card-start.php';
?>
    <form action="../backend/admin/acceso/restablecer-guardar.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="new-password">Nueva contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= isset($errors['new_password']) ? ' is-invalid' : '' ?>" id="new-password" name="new_password" type="password" minlength="8" pattern="(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" autocomplete="new-password" aria-describedby="new-password-hint new-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="new-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="form-text" id="new-password-hint">Mínimo 8, con mayúscula, número y símbolo.</div>
        <div class="invalid-feedback fw-semibold" id="new-password-error"><?= h($errors['new_password'] ?? 'Mínimo 8: mayúscula, número y símbolo.') ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="confirm-password">Confirmar contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= isset($errors['confirm_password']) ? ' is-invalid' : '' ?>" id="confirm-password" name="confirm_password" type="password" autocomplete="new-password" aria-describedby="confirm-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="confirm-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="invalid-feedback fw-semibold" id="confirm-password-error"><?= h($errors['confirm_password'] ?? 'Las contraseñas no coinciden.') ?></div>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">Guardar contraseña</button>
        <a class="btn btn-outline-primary" href="../backend/admin/acceso/recuperar-cancelar.php">Cancelar</a>
      </div>
    </form>
<?php require __DIR__ . '/../includes/admin-card-end.php'; ?>

<script src="<?= $base ?>assets/js/password-toggle.js"></script>
</body>
</html>
