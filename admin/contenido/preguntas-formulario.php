<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/contenido/preguntas-formulario-datos.php';

// Configuración de la página
$base      = '../../';
$title     = ($isEditing ? 'Editar pregunta' : 'Agregar pregunta') . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'preguntas';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar pregunta' : 'Agregar pregunta' ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/contenido/preguntas-guardar.php" method="post" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Contenido</h2>
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label" for="pregunta">Pregunta *</label>
                <input class="form-control<?= isset($errors['pregunta']) ? ' is-invalid' : '' ?>" id="pregunta" name="pregunta" type="text" minlength="10" maxlength="200" pattern=".*\p{L}.*" value="<?= e($faq['pregunta']) ?>"
                  data-msg-required="Escribe la pregunta." data-msg-min="La pregunta debe tener al menos 10 caracteres." data-msg-pattern="La pregunta debe llevar letras, no solo números o símbolos." required>
                <div class="invalid-feedback"><?= e($errors['pregunta'] ?? 'Escribe la pregunta.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="respuesta">Respuesta *</label>
                <textarea class="form-control<?= isset($errors['respuesta']) ? ' is-invalid' : '' ?>" id="respuesta" name="respuesta" rows="5" minlength="10" maxlength="1000"
                  data-msg-required="Escribe la respuesta." data-msg-min="La respuesta debe tener al menos 10 caracteres." required><?= e($faq['respuesta']) ?></textarea>
                <div class="form-text">De 10 a 1000 caracteres.</div>
                <div class="invalid-feedback"><?= e($errors['respuesta'] ?? 'Escribe la respuesta.') ?></div>
              </div>

              <div class="col-md-3">
                <label class="form-label" for="orden">Orden</label>
                <input class="form-control<?= isset($errors['orden']) ? ' is-invalid' : '' ?>" id="orden" name="orden" type="number" min="0" max="999" value="<?= (int) $faq['orden'] ?>" data-msg-range="El orden es un número de 0 a 999.">
                <div class="form-text">Las de número menor salen primero.</div>
                <div class="invalid-feedback"><?= e($errors['orden'] ?? '') ?></div>
              </div>

            </div>
          </div>
        </section>

        <div class="form-check form-switch mb-4">
          <input class="form-check-input" id="visible" name="visible" type="checkbox" role="switch"<?= $faq['visible'] ? ' checked' : '' ?>>
          <label class="form-check-label" for="visible">Visible (se muestra en el sitio)</label>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-primary" type="submit">Guardar</button>
          <a class="btn btn-outline-primary" href="preguntas.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
