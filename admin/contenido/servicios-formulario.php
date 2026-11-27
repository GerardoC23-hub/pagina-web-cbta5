<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/contenido/servicios-formulario-datos.php';

// Configuración de la página
$base      = '../../';
$title     = ($isEditing ? 'Editar servicio' : 'Nuevo servicio') . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'servicios';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar servicio' : 'Nuevo servicio' ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/contenido/servicios-guardar.php" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="data-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="data-title">Datos del servicio</h2>
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label" for="nombre">Nombre *</label>
                <input class="form-control<?= isset($errors['nombre']) ? ' is-invalid' : '' ?>" id="nombre" name="nombre" type="text" minlength="3" maxlength="120" value="<?= e($record['nombre']) ?>" placeholder="Ej. Orientación educativa" required>
                <div class="invalid-feedback"><?= e($errors['nombre'] ?? 'Nombre: de 3 a 120 caracteres.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="descripcion">Descripción *</label>
                <textarea class="form-control<?= isset($errors['descripcion']) ? ' is-invalid' : '' ?>" id="descripcion" name="descripcion" rows="4" minlength="10" maxlength="255" required><?= e($record['descripcion']) ?></textarea>
                <div class="form-text">Se muestra en la tarjeta y en la página de detalle. De 10 a 255 caracteres.</div>
                <div class="invalid-feedback"><?= e($errors['descripcion'] ?? 'Descripción: de 10 a 255 caracteres.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="horario">Horario de atención *</label>
                <input class="form-control<?= isset($errors['horario']) ? ' is-invalid' : '' ?>" id="horario" name="horario" type="text" minlength="3" maxlength="100" value="<?= e($record['horario']) ?>" required>
                <div class="form-text">Si todavía no se sabe, escribe «Por confirmar».</div>
                <div class="invalid-feedback"><?= e($errors['horario'] ?? 'Horario: de 3 a 100 caracteres.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="photo-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="photo-title">Foto</h2>
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label" for="imagen">Foto (opcional)</label>
<?php if ($record['imagen']): ?>
                <img class="d-block rounded mb-2" src="<?= e($base . $record['imagen']) ?>" width="192" height="108" alt="Foto actual del servicio">
<?php endif; ?>
                <input class="form-control<?= isset($errors['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="1">
                <div class="form-text">JPG, PNG o WebP. Máximo 1 MB.<?= $record['imagen'] ? ' Déjala vacía para conservar la actual.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['imagen'] ?? 'Formato no válido (JPG, PNG o WebP).') ?></div>
<?php if ($record['imagen']): ?>
                <div class="form-check mt-2">
                  <input class="form-check-input" id="quitar_imagen" name="quitar_imagen" type="checkbox" value="1">
                  <label class="form-check-label" for="quitar_imagen">Quitar la foto actual</label>
                </div>
<?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="imagen_credito">Crédito de la foto</label>
                <input class="form-control<?= isset($errors['imagen_credito']) ? ' is-invalid' : '' ?>" id="imagen_credito" name="imagen_credito" type="text" maxlength="120" value="<?= e($record['imagen_credito']) ?>">
                <div class="form-text">Quién la tomó. Obligatorio si hay foto.</div>
                <div class="invalid-feedback"><?= e($errors['imagen_credito'] ?? 'Escribe el autor de la foto.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-3">
          <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Guardar' ?></button>
          <a class="btn btn-outline-primary" href="servicios.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
