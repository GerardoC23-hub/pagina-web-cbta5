<?php
$base        = '';
$pagina      = 'galeria';
$title       = 'Galería · CBTa No. 5';
$description = 'Fotos y videos del CBTa No. 5 de Huejutla de Reyes, con crédito de cada autor.';
$styles      = ['pages/gallery.css'];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container">

    <div class="page-header">
      <h1 class="page-title">Galería</h1>
      <p class="page-lead">Fotos y videos reales del plantel, con crédito.</p>

      <nav aria-label="Filtrar por álbum">
        <ul class="filter-bar">
          <li><a class="filter-link active" href="galeria.php" aria-current="true">Todos</a></li>
          <li><a class="filter-link" href="galeria.php">Graduación</a></li>
          <li><a class="filter-link" href="galeria.php">Nuevo ingreso</a></li>
          <li><a class="filter-link" href="galeria.php">Eventos</a></li>
        </ul>
      </nav>
    </div>

    <p class="gallery-count" aria-live="polite">3 elementos</p>

    <ul class="gallery-grid">

      <li>
      <article class="card-item gallery-card">
        <button class="gallery-open" type="button" data-bs-toggle="modal" data-bs-target="#viewer" aria-label="Ver foto: Graduación 2025">
          <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Graduación 2025 (foto por agregar)" loading="lazy">
        </button>
        <div class="card-meta"><span class="tag tag-ok">Graduación</span><time datetime="2025-07-18">18 de julio de 2025</time></div>
        <h2 class="card-title">Graduación 2025</h2>
        <p class="card-text">La generación concluyó su bachillerato.</p>
        <p class="photo-credit mt-auto">Crédito: Zunoticia</p>
      </article>
      </li>

      <li>
      <article class="card-item gallery-card">
        <button class="gallery-open" type="button" data-bs-toggle="modal" data-bs-target="#viewer" aria-label="Ver foto: Día del Estudiante">
          <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Día del Estudiante (foto por agregar)" loading="lazy">
        </button>
        <div class="card-meta"><span class="tag tag-info">Eventos</span><time datetime="2026-05-22">22 de mayo de 2026</time></div>
        <h2 class="card-title">Día del Estudiante</h2>
        <p class="card-text">Participación en el festejo municipal.</p>
        <p class="photo-credit mt-auto">Crédito: Zunoticia</p>
      </article>
      </li>

      <li>
      <article class="card-item gallery-card">
        <button class="gallery-open" type="button" data-bs-toggle="modal" data-bs-target="#viewer" aria-label="Ver video: Recorrido por el plantel">
          <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Recorrido por el plantel (portada del video por agregar)" loading="lazy">
          <span class="play-badge" aria-hidden="true"></span>
        </button>
        <div class="card-meta"><span class="tag tag-warn">Nuevo ingreso</span><time datetime="2026-01-22">22 de enero de 2026</time></div>
        <h2 class="card-title">Recorrido por el plantel</h2>
        <p class="card-text">Conoce las instalaciones.</p>
        <p class="photo-credit mt-auto">Crédito: Por confirmar</p>
      </article>
      </li>

    </ul>
  </div>
</main>

<!-- Visor (ventana de Bootstrap) -->
<div class="modal fade viewer" id="viewer" tabindex="-1" aria-labelledby="viewer-title" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="viewer-title">Visor de galería</h2>
        <button type="button" class="viewer-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Graduación 2025 (foto por agregar)">
        <p class="viewer-caption">
          <strong>Graduación 2025</strong>
          <span>Graduación · Crédito: Zunoticia</span>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-main" disabled>← Anterior</button>
        <span class="viewer-position">1 de 3</span>
        <button type="button" class="btn-outline-main">Siguiente →</button>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
