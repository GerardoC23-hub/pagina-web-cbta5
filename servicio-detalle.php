<?php
$services = require __DIR__ . '/includes/services-data.php';

// Si el servicio no existe, regresa a la lista
$currentId = $_GET['id'] ?? '';
if (!isset($services[$currentId])) {
    header('Location: servicios.php');
    exit;
}
$current = $services[$currentId];
$others  = array_diff_key($services, [$currentId => true]);

$base        = '';
$pagina      = 'servicios';
$title       = $current['name'] . ' · CBTa No. 5';
$description = $current['description'];
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container">

    <nav class="pt-4" aria-label="Ruta de navegación">
      <ol class="breadcrumb small mb-2">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="servicios.php">Servicios</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $current['name'] ?></li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-lg-7">

        <span class="badge rounded-pill bg-info-subtle text-info-emphasis d-inline-flex align-items-center gap-1">
          <img src="assets/img/icons/calendar.svg" width="14" height="14" alt="">Servicio
        </span>
        <h1 class="mt-2 mb-4"><?= $current['name'] ?></h1>

        <img class="photo mb-4" src="<?= $current['image'] ?>" width="640" height="360" alt="<?= $current['name'] ?> (foto por agregar)">
        <p class="fs-5 text-body-secondary mb-4"><?= $current['description'] ?></p>

        <section class="card p-4 mb-4" aria-labelledby="schedule-title">
          <h2 class="h6 fw-bold mb-2" id="schedule-title">Horario</h2>
          <p class="d-flex gap-2 text-body-secondary mb-0">
            <svg class="flex-shrink-0 mt-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
              <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
            </svg>
            <span><?= $current['schedule'] ?></span>
          </p>
        </section>

        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary" href="solicitudes.php">Hacer una solicitud en línea →</a>
          <a class="btn btn-outline-primary" href="index.php#contact-title">Contactar al plantel</a>
          <a class="btn btn-outline-primary" href="servicios.php">← Volver a servicios</a>
        </div>

      </div>
    </div>

<?php if ($others): ?>
    <section class="section" aria-labelledby="related-title">
      <h2 class="mb-4" id="related-title">También te puede interesar</h2>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 list-unstyled mb-0">
<?php foreach ($others as $id => $service): ?>
        <li class="col">
<?php require __DIR__ . '/includes/service-card.php'; ?>
        </li>
<?php endforeach; ?>
      </ul>
    </section>
<?php endif; ?>

  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
