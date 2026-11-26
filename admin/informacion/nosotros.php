<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/informacion/nosotros-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Nosotros · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'nosotros';

// Largo mínimo, largo máximo y alto de cada caja de texto
$limits = ['mision' => [20, 600, 4], 'vision' => [20, 600, 4], 'valores' => [20, 600, 4], 'historia' => [50, 2000, 8]];

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Nosotros</h1>
      <p class="text-secondary mb-4">Textos de misión, visión, valores e historia que se muestran en la página Nosotros.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/informacion/nosotros-guardar.php" method="post" novalidate>

        <section class="card mb-4" aria-labelledby="texts-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="texts-title">Textos del plantel</h2>
            <div class="row g-3">

<?php foreach ($limits as $key => [$min, $max, $rows]): ?>
<?php if (isset($texts[$key])): ?>
              <div class="col-12">
                <label class="form-label" for="<?= $key ?>"><?= e($texts[$key]['titulo']) ?> *</label>
                <textarea class="form-control<?= isset($errors[$key]) ? ' is-invalid' : '' ?>" id="<?= $key ?>" name="contenido[<?= $key ?>]" rows="<?= $rows ?>" minlength="<?= $min ?>" maxlength="<?= $max ?>" required><?= e($texts[$key]['contenido']) ?></textarea>
                <div class="form-text">De <?= $min ?> a <?= $max ?> caracteres.</div>
                <div class="invalid-feedback"><?= e($errors[$key] ?? $texts[$key]['titulo'] . ": de $min a $max caracteres.") ?></div>
              </div>
<?php endif; ?>
<?php endforeach; ?>

            </div>
          </div>
        </section>

        <button class="btn btn-primary" type="submit">Guardar cambios</button>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
