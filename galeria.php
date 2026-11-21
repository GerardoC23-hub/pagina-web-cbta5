<?php
$base        = '';
$pagina      = 'galeria';
$title       = 'Galería · CBTa No. 5';
$description = 'Fotos y videos del CBTa No. 5 de Huejutla de Reyes, con crédito de cada autor.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container">

    <div class="pt-4">
      <h1 class="mb-0">Galería</h1>
      <p class="lead fs-5 fw-normal text-body-secondary mt-2 mb-0">Fotos y videos reales del plantel, con crédito.</p>

      <nav aria-label="Filtrar por álbum">
        <ul class="d-flex flex-wrap gap-2 list-unstyled mt-4 mb-0">
          <li><a class="btn btn-outline-primary active" href="galeria.php" aria-current="true">Todos</a></li>
          <li><a class="btn btn-outline-primary" href="galeria.php">Graduación</a></li>
          <li><a class="btn btn-outline-primary" href="galeria.php">Nuevo ingreso</a></li>
          <li><a class="btn btn-outline-primary" href="galeria.php">Eventos</a></li>
        </ul>
      </nav>
    </div>

    <p class="small text-body-secondary my-3" aria-live="polite">3 elementos</p>

    <ul class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 list-unstyled">

      <li class="col">
      <article class="card card-hover h-100 p-3">
        <button class="btn border-0 p-0 position-relative w-100" type="button" data-bs-toggle="modal" data-bs-target="#viewer" aria-label="Ver foto: Graduación 2025">
          <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Graduación 2025 (foto por agregar)" loading="lazy">
        </button>
        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Graduación</span><time datetime="2025-07-18">18 de julio de 2025</time></div>
        <h2 class="card-title h5 fw-bold mt-2 mb-2">Graduación 2025</h2>
        <p class="card-text text-body-secondary mb-3">La generación concluyó su bachillerato.</p>
        <p class="small text-body-secondary mt-auto mb-0">Crédito: Zunoticia</p>
      </article>
      </li>

      <li class="col">
      <article class="card card-hover h-100 p-3">
        <button class="btn border-0 p-0 position-relative w-100" type="button" data-bs-toggle="modal" data-bs-target="#viewer" aria-label="Ver foto: Día del Estudiante">
          <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Día del Estudiante (foto por agregar)" loading="lazy">
        </button>
        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-info-subtle text-info-emphasis">Eventos</span><time datetime="2026-05-22">22 de mayo de 2026</time></div>
        <h2 class="card-title h5 fw-bold mt-2 mb-2">Día del Estudiante</h2>
        <p class="card-text text-body-secondary mb-3">Participación en el festejo municipal.</p>
        <p class="small text-body-secondary mt-auto mb-0">Crédito: Zunoticia</p>
      </article>
      </li>

      <li class="col">
      <article class="card card-hover h-100 p-3">
        <button class="btn border-0 p-0 position-relative w-100" type="button" data-bs-toggle="modal" data-bs-target="#viewer" aria-label="Ver video: Recorrido por el plantel">
          <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Recorrido por el plantel (portada del video por agregar)" loading="lazy">
          <img class="position-absolute top-50 start-50 translate-middle bg-white rounded-circle shadow-sm p-3" src="assets/img/icons/play.svg" width="64" height="64" alt="">
        </button>
        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Nuevo ingreso</span><time datetime="2026-01-22">22 de enero de 2026</time></div>
        <h2 class="card-title h5 fw-bold mt-2 mb-2">Recorrido por el plantel</h2>
        <p class="card-text text-body-secondary mb-3">Conoce las instalaciones.</p>
        <p class="small text-body-secondary mt-auto mb-0">Crédito: Por confirmar</p>
      </article>
      </li>

    </ul>
  </div>
</main>

<!-- Visor (ventana de Bootstrap) -->
<div class="modal fade" id="viewer" tabindex="-1" aria-labelledby="viewer-title" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title h5 fw-bold" id="viewer-title">Visor de galería</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Graduación 2025 (foto por agregar)">
        <p class="mt-3 mb-0">
          <strong class="d-block h5 fw-bold mb-1">Graduación 2025</strong>
          <small class="text-body-secondary">Graduación · Crédito: Zunoticia</small>
        </p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-primary" disabled>← Anterior</button>
        <span class="fw-bold">1 de 3</span>
        <button type="button" class="btn btn-outline-primary">Siguiente →</button>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
