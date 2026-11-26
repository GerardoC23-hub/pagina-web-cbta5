<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/informacion/contacto-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Datos de contacto · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'contacto';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Datos de contacto</h1>
      <p class="text-secondary mb-4">Lo que escribas aquí se muestra en el pie de página y en la página de contacto.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/informacion/contacto-guardar.php" method="post" novalidate>

        <section class="card mb-4" aria-labelledby="contact-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="contact-title">Contacto del plantel</h2>
            <div class="row g-3">

<?php if (isset($contact['contacto_direccion'])): ?>
              <div class="col-md-7">
                <label class="form-label" for="direccion">Dirección *</label>
                <input class="form-control<?= isset($errors['contacto_direccion']) ? ' is-invalid' : '' ?>" id="direccion" name="contenido[contacto_direccion]" type="text" minlength="10" maxlength="200" value="<?= e($contact['contacto_direccion']['contenido']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['contacto_direccion'] ?? 'Dirección: de 10 a 200 caracteres.') ?></div>
              </div>
              <div class="col-md-5">
                <label class="form-label" for="direccion-map">Enlace del mapa</label>
                <input class="form-control<?= isset($errors['contacto_direccion_enlace']) ? ' is-invalid' : '' ?>" id="direccion-map" name="enlace[contacto_direccion]" type="url" maxlength="255" placeholder="https://www.google.com/maps?q=…" value="<?= e($contact['contacto_direccion']['enlace']) ?>">
                <div class="invalid-feedback"><?= e($errors['contacto_direccion_enlace'] ?? 'Enlace no válido.') ?></div>
              </div>
<?php endif; ?>

<?php if (isset($contact['contacto_telefono'])): ?>
              <div class="col-md-4">
                <label class="form-label" for="telefono">Teléfono *</label>
                <input class="form-control<?= isset($errors['contacto_telefono']) ? ' is-invalid' : '' ?>" id="telefono" name="contenido[contacto_telefono]" type="tel" maxlength="20" pattern="[0-9\s\(\)\+\-]{8,20}" value="<?= e($contact['contacto_telefono']['contenido']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['contacto_telefono'] ?? 'Teléfono no válido.') ?></div>
              </div>
<?php endif; ?>

<?php if (isset($contact['contacto_correo'])): ?>
              <div class="col-md-8">
                <label class="form-label" for="correo">Correo electrónico *</label>
                <input class="form-control<?= isset($errors['contacto_correo']) ? ' is-invalid' : '' ?>" id="correo" name="contenido[contacto_correo]" type="email" maxlength="120" value="<?= e($contact['contacto_correo']['contenido']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['contacto_correo'] ?? 'Correo no válido.') ?></div>
              </div>
<?php endif; ?>

<?php if (isset($contact['contacto_horario'])): ?>
              <div class="col-12">
                <label class="form-label" for="horario">Horario de atención *</label>
                <input class="form-control<?= isset($errors['contacto_horario']) ? ' is-invalid' : '' ?>" id="horario" name="contenido[contacto_horario]" type="text" minlength="5" maxlength="100" value="<?= e($contact['contacto_horario']['contenido']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['contacto_horario'] ?? 'Horario: de 5 a 100 caracteres.') ?></div>
              </div>
<?php endif; ?>

<?php if (isset($contact['contacto_sitio'])): ?>
              <div class="col-md-5">
                <label class="form-label" for="sitio">Sitio oficial (texto) *</label>
                <input class="form-control<?= isset($errors['contacto_sitio']) ? ' is-invalid' : '' ?>" id="sitio" name="contenido[contacto_sitio]" type="text" minlength="3" maxlength="60" value="<?= e($contact['contacto_sitio']['contenido']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['contacto_sitio'] ?? 'Nombre: de 3 a 60 caracteres.') ?></div>
              </div>
              <div class="col-md-7">
                <label class="form-label" for="sitio-link">Sitio oficial (enlace) *</label>
                <input class="form-control<?= isset($errors['contacto_sitio_enlace']) ? ' is-invalid' : '' ?>" id="sitio-link" name="enlace[contacto_sitio]" type="url" maxlength="255" placeholder="https://cbta5.net" value="<?= e($contact['contacto_sitio']['enlace']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['contacto_sitio_enlace'] ?? 'Enlace no válido.') ?></div>
              </div>
<?php endif; ?>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="social-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-1" id="social-title">Redes sociales</h2>
            <p class="text-secondary small mb-3">Si dejas el enlace vacío, esa red no se muestra en el sitio.</p>
            <div class="row g-3">

<?php foreach ($social as $key => $row): ?>
              <div class="col-md-4">
                <label class="form-label" for="<?= e($key) ?>-name"><?= e($row['titulo']) ?> (usuario)</label>
                <input class="form-control<?= isset($errors[$key]) ? ' is-invalid' : '' ?>" id="<?= e($key) ?>-name" name="contenido[<?= e($key) ?>]" type="text" maxlength="60" placeholder="@usuario" value="<?= e($row['contenido']) ?>">
                <div class="invalid-feedback"><?= e($errors[$key] ?? 'Máximo 60 caracteres.') ?></div>
              </div>
              <div class="col-md-8">
                <label class="form-label" for="<?= e($key) ?>-link"><?= e($row['titulo']) ?> (enlace)</label>
                <input class="form-control<?= isset($errors[$key . '_enlace']) ? ' is-invalid' : '' ?>" id="<?= e($key) ?>-link" name="enlace[<?= e($key) ?>]" type="url" maxlength="255" placeholder="https://…" value="<?= e($row['enlace']) ?>">
                <div class="invalid-feedback"><?= e($errors[$key . '_enlace'] ?? 'Enlace no válido.') ?></div>
              </div>
<?php endforeach; ?>

            </div>
          </div>
        </section>

        <button class="btn btn-primary" type="submit">Guardar cambios</button>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
