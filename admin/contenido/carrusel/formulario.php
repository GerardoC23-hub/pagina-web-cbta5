<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../../backend/admin/contenido/carrusel-formulario-datos.php';

// Configuración de la página
$base      = '../../../';
$title     = ($isEditing ? 'Editar slide' : 'Agregar slide') . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'carrusel';

require __DIR__ . '/../../../includes/head.php';
require __DIR__ . '/../../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar slide' : 'Agregar slide' ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../../backend/admin/contenido/carrusel-guardar.php" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Texto del slide</h2>
            <div class="row g-3">

              <div class="col-md-4">
                <label class="form-label" for="etiqueta">Etiqueta</label>
                <input class="form-control" id="etiqueta" name="etiqueta" type="text" maxlength="60" value="<?= e($slide['etiqueta']) ?>">
                <div class="form-text">Texto corto arriba del título, por ejemplo «Nuevo ingreso».</div>
              </div>

              <div class="col-md-8">
                <label class="form-label" for="titulo">Título *</label>
                <input class="form-control<?= isset($errors['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" type="text" minlength="3" maxlength="150" value="<?= e($slide['titulo']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['titulo'] ?? 'Título: de 3 a 150 caracteres.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="descripcion">Descripción</label>
                <input class="form-control" id="descripcion" name="descripcion" type="text" maxlength="255" value="<?= e($slide['descripcion']) ?>">
                <div class="form-text">Una frase corta. Máximo 255 caracteres.</div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="image-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="image-title">Imagen</h2>
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label" for="imagen">Imagen<?= $isEditing ? '' : ' *' ?></label>
<?php if ($isEditing && $slide['imagen']): ?>
                <img class="d-block rounded mb-2" src="<?= e(siteFile($slide['imagen'], $base)) ?>" width="192" height="108" alt="Imagen actual del slide">
<?php endif; ?>
                <input class="form-control<?= isset($errors['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="1"<?= $isEditing ? '' : ' required' ?>>
                <div class="form-text">Formatos: JPG, PNG o WebP. Máximo 1 MB.<?= $isEditing ? ' Déjala vacía para conservar la actual.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['imagen'] ?? 'Selecciona una imagen.') ?></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="imagen_alt">Descripción de la imagen (texto alternativo) *</label>
                <input class="form-control<?= isset($errors['imagen_alt']) ? ' is-invalid' : '' ?>" id="imagen_alt" name="imagen_alt" type="text" minlength="8" maxlength="150" value="<?= e($slide['imagen_alt']) ?>" required>
                <div class="form-text">Describe qué se ve, para personas que usan lector de pantalla.</div>
                <div class="invalid-feedback"><?= e($errors['imagen_alt'] ?? 'Describe la imagen (mínimo 8 caracteres).') ?></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="imagen_credito">Crédito *</label>
                <input class="form-control<?= isset($errors['imagen_credito']) ? ' is-invalid' : '' ?>" id="imagen_credito" name="imagen_credito" type="text" maxlength="120" value="<?= e($slide['imagen_credito']) ?>" required>
                <div class="form-text">Quién tomó la foto.</div>
                <div class="invalid-feedback"><?= e($errors['imagen_credito'] ?? 'Escribe el autor de la foto.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="publish-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="publish-title">Publicación</h2>
            <div class="row g-3">

              <div class="col-md-4">
                <label class="form-label" for="fecha_inicio">Mostrar desde</label>
                <input class="form-control" id="fecha_inicio" name="fecha_inicio" type="date" value="<?= e($slide['fecha_inicio']) ?>">
                <div class="form-text">Opcional.</div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="fecha_fin">Mostrar hasta</label>
                <input class="form-control<?= isset($errors['fecha_fin']) ? ' is-invalid' : '' ?>" id="fecha_fin" name="fecha_fin" type="date" value="<?= e($slide['fecha_fin']) ?>">
                <div class="form-text">Opcional.</div>
                <div class="invalid-feedback"><?= e($errors['fecha_fin'] ?? '') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="orden">Orden</label>
                <input class="form-control" id="orden" name="orden" type="number" min="0" max="999" value="<?= (int) $slide['orden'] ?>">
                <div class="form-text">El número más bajo sale primero.</div>
              </div>

              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" id="visible" name="visible" type="checkbox" value="1"<?= $slide['visible'] ? ' checked' : '' ?>>
                  <label class="form-check-label" for="visible">Visible en el inicio</label>
                </div>
              </div>

            </div>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-3">
          <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Agregar slide' ?></button>
          <a class="btn btn-outline-primary" href="index.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../../includes/panel-layout-end.php'; ?>
