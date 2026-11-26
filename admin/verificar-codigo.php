<?php
require __DIR__ . '/../includes/panel-auth.php';

// Datos que muestra la pantalla (paso 2: código)
$step = 2;
require __DIR__ . '/../backend/admin/acceso/recuperar-datos.php';

// Configuración de la página
$base      = '../';
$title     = 'Verificar código · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$cardTitle = 'Verificar código';
$cardText  = 'Escribe el código de 6 números que enviamos a tu correo.';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/admin-card-start.php';
?>
    <form action="../backend/admin/acceso/verificar-guardar.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="code">Código del correo *</label>
        <input class="form-control<?= isset($errors['code']) ? ' is-invalid' : '' ?>" id="code" name="code" type="text" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code" aria-describedby="code-error" required>
        <div class="invalid-feedback fw-semibold" id="code-error"><?= h($errors['code'] ?? 'El código tiene 6 números.') ?></div>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">Verificar código</button>
        <a class="btn btn-outline-primary" href="../backend/admin/acceso/recuperar-cancelar.php">Cancelar</a>
      </div>
    </form>

    <p class="small text-body-secondary mt-3 mb-0">El código vence en 10 minutos. Si venció, <a href="recuperar.php">pide uno nuevo</a>.</p>
<?php require __DIR__ . '/../includes/admin-card-end.php'; ?>

</body>
</html>
