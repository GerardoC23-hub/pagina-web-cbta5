<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/contenido/noticias-formulario-datos.php';

// Configuración de la página
$base      = '../../';
$title     = ($isEditing ? 'Editar noticia' : 'Agregar noticia') . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'noticias';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar noticia' : 'Agregar noticia' ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/contenido/noticias-guardar.php" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Contenido</h2>
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label" for="titulo">Título *</label>
                <input class="form-control<?= isset($errors['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" type="text" minlength="5" maxlength="150" value="<?= e($item['titulo']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['titulo'] ?? 'Título: de 5 a 150 caracteres.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="resumen">Resumen *</label>
                <input class="form-control<?= isset($errors['resumen']) ? ' is-invalid' : '' ?>" id="resumen" name="resumen" type="text" minlength="10" maxlength="255" value="<?= e($item['resumen']) ?>" required>
                <div class="form-text">Una o dos frases. Aparece en las tarjetas.</div>
                <div class="invalid-feedback"><?= e($errors['resumen'] ?? 'Resumen: de 10 a 255 caracteres.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="contenido">Contenido *</label>
                <textarea class="form-control<?= isset($errors['contenido']) ? ' is-invalid' : '' ?>" id="contenido" name="contenido" rows="9" minlength="30" maxlength="5000" required><?= e($item['contenido']) ?></textarea>
                <div class="form-text">De 30 a 5000 caracteres.</div>
                <div class="invalid-feedback"><?= e($errors['contenido'] ?? 'Contenido: de 30 a 5000 caracteres.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="image-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="image-title">Imagen</h2>
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label" for="imagen">Imagen</label>
<?php if ($item['imagen']): ?>
                <img class="d-block rounded mb-2" src="<?= e($base . $item['imagen']) ?>" width="192" height="108" alt="Imagen actual de la noticia">
<?php endif; ?>
                <input class="form-control<?= isset($errors['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="1">
                <div class="form-text">JPG, PNG o WebP. Máximo 1 MB.<?= $item['imagen'] ? ' Déjala vacía para conservar la actual.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['imagen'] ?? 'Formato no válido (JPG, PNG o WebP).') ?></div>
<?php if ($item['imagen']): ?>
                <div class="form-check mt-2">
                  <input class="form-check-input" id="quitar_imagen" name="quitar_imagen" type="checkbox" value="1">
                  <label class="form-check-label" for="quitar_imagen">Quitar la imagen actual</label>
                </div>
<?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="imagen_credito">Crédito de la imagen</label>
                <input class="form-control<?= isset($errors['imagen_credito']) ? ' is-invalid' : '' ?>" id="imagen_credito" name="imagen_credito" type="text" maxlength="120" value="<?= e($item['imagen_credito']) ?>">
                <div class="form-text">Quién tomó la foto. Obligatorio si hay imagen.</div>
                <div class="invalid-feedback"><?= e($errors['imagen_credito'] ?? 'Escribe el autor de la imagen.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="publish-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="publish-title">Publicación</h2>
            <div class="row g-3">

              <div class="col-md-4">
                <label class="form-label" for="categoria">Categoría *</label>
                <select class="form-select<?= isset($errors['categoria']) ? ' is-invalid' : '' ?>" id="categoria" name="categoria" required>
<?php foreach ($categories as $option): ?>
                  <option value="<?= $option ?>"<?= $item['categoria'] === $option ? ' selected' : '' ?>><?= $option ?></option>
<?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= e($errors['categoria'] ?? 'Elige una categoría.') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="fecha_publicacion">Fecha *</label>
                <input class="form-control<?= isset($errors['fecha_publicacion']) ? ' is-invalid' : '' ?>" id="fecha_publicacion" name="fecha_publicacion" type="date" value="<?= e($item['fecha_publicacion']) ?>" required>
                <div class="form-text">Con fecha futura se publica ese día.</div>
                <div class="invalid-feedback"><?= e($errors['fecha_publicacion'] ?? 'Fecha no válida.') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="estado">Estado *</label>
                <select class="form-select<?= isset($errors['estado']) ? ' is-invalid' : '' ?>" id="estado" name="estado" required>
<?php foreach ($states as $option): ?>
                  <option value="<?= $option ?>"<?= $item['estado'] === $option ? ' selected' : '' ?>><?= $option ?></option>
<?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= e($errors['estado'] ?? 'Elige un estado.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-3">
          <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Agregar noticia' ?></button>
          <a class="btn btn-outline-primary" href="noticias.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
