<?php
require __DIR__ . '/../includes/panel-auth.php';
panel_require_login([], true);

// Si ya no tiene que cambiarla, entra al panel
if (empty($_SESSION['panel_must_change_pw'])) {
    header('Location: index.php');
    exit;
}

// Errores del intento anterior (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

// Configuración de la página
$base     = '../';
$title    = 'Cambiar contraseña · Panel de administración';
$styles   = ['pages/panel.css'];
$withSite = false;
$noindex  = true;

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="panel-change-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="panel-change-title">Crea tu contraseña</h1>
    <p class="text-body-secondary mb-3">Tu contraseña es temporal. Cámbiala para continuar.</p>

<?php if (isset($errors['form'])): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errors['form'], ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

    <form action="../backend/admin/acceso/cambiar-contrasena-guardar.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="new-password">Nueva contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" id="new-password" name="new_password" type="password"
                 minlength="8" pattern="(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" autocomplete="new-password"
                 aria-describedby="new-password-hint new-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="new-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="form-text" id="new-password-hint">Mínimo 8 caracteres, con mayúscula, número y símbolo (por ejemplo ! # $ %).</div>
        <div class="invalid-feedback fw-semibold" id="new-password-error"><?= htmlspecialchars($errors['password'] ?? 'Mínimo 8: mayúscula, número y símbolo.', ENT_QUOTES, 'UTF-8') ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="confirm-password">Confirmar contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= isset($errors['confirm']) ? ' is-invalid' : '' ?>" id="confirm-password" name="confirm_password" type="password"
                 autocomplete="new-password" aria-describedby="confirm-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="confirm-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="invalid-feedback fw-semibold" id="confirm-password-error"><?= htmlspecialchars($errors['confirm'] ?? 'Las contraseñas no coinciden.', ENT_QUOTES, 'UTF-8') ?></div>
      </div>

      <button class="btn btn-primary w-100" type="submit">Guardar contraseña</button>
    </form>

    <p class="small text-body-secondary mt-3 mb-0"><a href="logout.php">Cerrar sesión</a></p>
  </section>

</main>

<script src="<?= $base ?>assets/js/password-toggle.js"></script>
</body>
</html>
