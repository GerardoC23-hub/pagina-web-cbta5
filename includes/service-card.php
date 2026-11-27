<?php
// Tarjeta de un servicio. Necesita $service (sus datos) y $base
?>
<article class="card card-hover h-100 p-3">
  <img class="photo" src="<?= h(siteFile($service['imagen'], $base)) ?>" width="640" height="360" alt="<?= h($service['nombre']) ?>" loading="lazy">
  <span class="badge rounded-pill bg-info-subtle text-info-emphasis align-self-start d-inline-flex align-items-center gap-1 mt-3">
    <img src="<?= $base ?>assets/img/icons/calendar.svg" width="14" height="14" alt="">Servicio
  </span>
  <h2 class="card-title h5 fw-bold mt-2 mb-2"><a class="stretched-link text-reset text-decoration-none" href="servicio-detalle.php?id=<?= (int) $service['id_actividad'] ?>"><?= h($service['nombre']) ?></a></h2>
  <p class="card-text text-body-secondary mb-2"><?= h($service['descripcion']) ?></p>
<?php if ($service['horario']): ?>
  <p class="small d-flex gap-2 mb-3">
    <svg class="flex-shrink-0 mt-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
      <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
    </svg>
    <span><?= h($service['horario']) ?></span>
  </p>
<?php endif; ?>
  <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver detalle</span>
</article>
