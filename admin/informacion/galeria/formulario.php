<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../../backend/admin/informacion/galeria-formulario-datos.php';

// Configuración de la página
$label     = $isPhoto ? 'foto' : 'video';
$base      = '../../../';
$title     = ($isEditing ? 'Editar ' : 'Agregar ') . $label . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'galeria';

require __DIR__ . '/../../../includes/head.php';
require __DIR__ . '/../../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar' : 'Agregar' ?> <?= $label ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../../backend/admin/informacion/galeria-guardar.php" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>
        <input type="hidden" name="tipo" value="<?= e($media['tipo']) ?>">

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Datos de <?= $isPhoto ? 'la foto' : 'el video' ?></h2>
            <div class="row g-3">

              <div class="col-md-8">
                <label class="form-label" for="titulo">Título *</label>
                <input class="form-control<?= isset($errors['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" type="text" minlength="3" maxlength="120" pattern=".*\p{L}.*" value="<?= e($media['titulo']) ?>"
                  data-msg-required="Escribe el título." data-msg-min="El título debe tener al menos 3 caracteres." data-msg-pattern="El título debe llevar letras, no solo números o símbolos." required>
                <div class="invalid-feedback"><?= e($errors['titulo'] ?? 'Escribe el título.') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="id_album">Álbum</label>
                <select class="form-select<?= isset($errors['id_album']) ? ' is-invalid' : '' ?>" id="id_album" name="id_album">
                  <option value="">Sin álbum</option>
<?php foreach ($albums as $album): ?>
                  <option value="<?= (int) $album['id_album'] ?>"<?= (string) $media['id_album'] === (string) $album['id_album'] ? ' selected' : '' ?>><?= e($album['nombre']) ?></option>
<?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= e($errors['id_album'] ?? '') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="descripcion">Descripción</label>
                <input class="form-control<?= isset($errors['descripcion']) ? ' is-invalid' : '' ?>" id="descripcion" name="descripcion" type="text" minlength="3" maxlength="255" pattern=".*\p{L}.*" value="<?= e($media['descripcion']) ?>"
                  data-msg-min="La descripción debe tener al menos 3 caracteres." data-msg-pattern="La descripción debe llevar letras, no solo números o símbolos.">
                <div class="form-text">Texto que se muestra debajo. Máximo 255 caracteres.</div>
                <div class="invalid-feedback"><?= e($errors['descripcion'] ?? '') ?></div>
              </div>

<?php if ($isPhoto): ?>
              <div class="col-12">
                <label class="form-label" for="texto_alt">Texto alternativo *</label>
                <input class="form-control<?= isset($errors['texto_alt']) ? ' is-invalid' : '' ?>" id="texto_alt" name="texto_alt" type="text" minlength="8" maxlength="150" pattern=".*\p{L}.*" value="<?= e($media['texto_alt']) ?>"
                  data-msg-required="Describe lo que se ve en la foto." data-msg-min="El texto alternativo debe tener al menos 8 caracteres." data-msg-pattern="El texto alternativo debe llevar letras, no solo números o símbolos." required>
                <div class="form-text">Describe lo que se ve, para quien usa lector de pantalla.</div>
                <div class="invalid-feedback"><?= e($errors['texto_alt'] ?? 'Describe lo que se ve en la foto.') ?></div>
              </div>
<?php else: ?>
              <div class="col-12">
                <label class="form-label" for="video_url">Enlace del video (YouTube o Vimeo)</label>
                <input class="form-control<?= isset($errors['video_url']) ? ' is-invalid' : '' ?>" id="video_url" name="video_url" type="url" maxlength="255" pattern="https://(www\.|player\.)?(youtube\.com|youtu\.be|vimeo\.com)/\S+" placeholder="https://www.youtube.com/watch?v=…" value="<?= e($media['video_url']) ?>"
                  data-msg-type="Escribe un enlace completo que empiece con https://." data-msg-pattern="El enlace debe ser de YouTube o Vimeo.">
                <div class="invalid-feedback"><?= e($errors['video_url'] ?? '') ?></div>
              </div>
              <div class="col-12">
                <label class="form-label" for="video">…o sube un archivo MP4</label>
                <input class="form-control<?= isset($errors['video']) ? ' is-invalid' : '' ?>" id="video" name="video" type="file" accept="video/mp4" data-max-mb="10" data-msg-type="El video debe ser MP4.">
                <div class="form-text">Máximo 10 MB. Si escribes un enlace, se usa el enlace.<?= $isEditing && $media['archivo'] !== '' && strpos($media['archivo'], 'https://') !== 0 ? ' Ahora tiene un archivo subido; déjalo vacío para conservarlo.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['video'] ?? '') ?></div>
              </div>
<?php endif; ?>

              <div class="col-md-6">
                <label class="form-label" for="credito">Crédito</label>
                <input class="form-control<?= isset($errors['credito']) ? ' is-invalid' : '' ?>" id="credito" name="credito" type="text" minlength="3" maxlength="120" pattern=".*\p{L}.*" value="<?= e($media['credito']) ?>"
                  data-msg-min="El crédito debe tener al menos 3 caracteres." data-msg-pattern="El crédito debe llevar letras, no solo números o símbolos.">
                <div class="invalid-feedback"><?= e($errors['credito'] ?? '') ?></div>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="fecha">Fecha</label>
                <input class="form-control<?= isset($errors['fecha']) ? ' is-invalid' : '' ?>" id="fecha" name="fecha" type="date" value="<?= e($media['fecha']) ?>" data-msg-type="La fecha no es válida.">
                <div class="invalid-feedback"><?= e($errors['fecha'] ?? '') ?></div>
              </div>
              <div class="col-md-2">
                <label class="form-label" for="orden">Orden</label>
                <input class="form-control<?= isset($errors['orden']) ? ' is-invalid' : '' ?>" id="orden" name="orden" type="number" min="0" max="999" value="<?= (int) $media['orden'] ?>" data-msg-range="El orden es un número de 0 a 999.">
                <div class="invalid-feedback"><?= e($errors['orden'] ?? '') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="image-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="image-title"><?= $isPhoto ? 'Imagen' : 'Portada del video' ?></h2>
<?php
$field   = $isPhoto ? 'imagen' : 'miniatura';
$current = $isPhoto ? $media['archivo'] : $media['miniatura'];
?>
<?php if ($isEditing && $current): ?>
            <img class="d-block rounded mb-2" src="<?= e($base . $current) ?>" width="192" height="128" alt="Imagen actual">
<?php endif; ?>
            <label class="form-label" for="<?= $field ?>"><?= $isPhoto ? 'Imagen' . ($isEditing ? '' : ' *') : 'Portada (opcional)' ?></label>
            <input class="form-control<?= isset($errors[$field]) ? ' is-invalid' : '' ?>" id="<?= $field ?>" name="<?= $field ?>" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="2" data-msg-required="Selecciona una imagen." data-msg-type="La imagen debe ser JPG, PNG o WebP."<?= $isPhoto && !$isEditing ? ' required' : '' ?>>
            <div class="form-text">Formatos: JPG, PNG o WebP. Máximo 2 MB.<?= $isEditing ? ' Déjala vacía para conservar la actual.' : '' ?></div>
            <div class="invalid-feedback"><?= e($errors[$field] ?? ($isPhoto ? 'Selecciona una imagen.' : '')) ?></div>
          </div>
        </section>

        <div class="form-check form-switch mb-4">
          <input class="form-check-input" id="visible" name="visible" type="checkbox" role="switch"<?= $media['visible'] ? ' checked' : '' ?>>
          <label class="form-check-label" for="visible">Publicado (se ve en la galería del sitio)</label>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-primary" type="submit">Guardar</button>
          <a class="btn btn-outline-primary" href="index.php?t=<?= $tab ?>">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../../includes/panel-layout-end.php'; ?>
