<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/informacion/legales-formulario-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Editar ' . $legal['titulo'] . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'legales';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Editar texto legal</h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/informacion/legales-guardar.php" method="post" novalidate>
        <input type="hidden" name="clave" value="<?= e($legal['clave']) ?>">

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Texto</h2>
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label" for="titulo">Título *</label>
                <input class="form-control<?= isset($errors['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" type="text" minlength="5" maxlength="120" pattern=".*\p{L}.*" value="<?= e($legal['titulo']) ?>"
                  data-msg-required="Escribe el título." data-msg-min="El título debe tener al menos 5 caracteres." data-msg-pattern="El título debe llevar letras, no solo números o símbolos." required>
                <div class="invalid-feedback"><?= e($errors['titulo'] ?? 'Escribe el título.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="contenido">Contenido *</label>
                <textarea class="form-control font-monospace<?= isset($errors['contenido']) ? ' is-invalid' : '' ?>" id="contenido" name="contenido" rows="20" minlength="100" maxlength="10000"
                  data-msg-required="Escribe el texto." data-msg-min="El texto debe tener al menos 100 caracteres." required><?= e($legal['contenido']) ?></textarea>
                <div class="form-text">De 100 a 10000 caracteres.</div>
                <div class="invalid-feedback"><?= e($errors['contenido'] ?? 'Escribe el texto.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="help-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="help-title">Cómo escribir el texto</h2>
            <ul class="mb-0">
              <li>Una línea que empieza con <code>## </code> abre una sección. Ejemplo: <code>## Uso del sitio</code></li>
              <li>Una línea que empieza con <code>- </code> es un punto de una lista.</li>
              <li>Una línea en blanco separa los párrafos.</li>
              <li>Un enlace se escribe <code>[texto](enlace)</code>. Si no empieza con http, va desde la raíz del sitio. Ejemplo: <code>[Contacto](pages/contacto.php)</code></li>
            </ul>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-primary" type="submit">Guardar</button>
          <a class="btn btn-outline-primary" href="legales.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
