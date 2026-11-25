<?php
$base        = '../';
$pagina      = 'galeria';
$title       = 'Galería · CBTa No. 5';
$description = 'Fotos y videos del CBTa No. 5 de Huejutla de Reyes, con crédito de cada autor.';
$styles      = [];
$scripts     = ['gallery.js'];

// Datos que muestra la página
require __DIR__ . '/../backend/publico/galeria-datos.php';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>

<main id="principal">
  <div class="container">

    <div class="pt-4">
      <h1 class="mb-0">Galería</h1>
      <p class="lead fs-5 fw-normal text-body-secondary mt-2 mb-0">Fotos y videos reales del plantel, con crédito.</p>

<?php if ($albums): ?>
      <nav aria-label="Filtrar por álbum">
        <ul class="d-flex flex-wrap gap-2 list-unstyled mt-4 mb-0">
          <li><a class="btn btn-outline-primary<?= $albumId === 0 ? ' active' : '' ?>" href="galeria.php"<?= $albumId === 0 ? ' aria-current="true"' : '' ?>>Todos</a></li>
<?php foreach ($albums as $album): ?>
          <li><a class="btn btn-outline-primary<?= $albumId === (int) $album['id_album'] ? ' active' : '' ?>" href="galeria.php?album=<?= (int) $album['id_album'] ?>"<?= $albumId === (int) $album['id_album'] ? ' aria-current="true"' : '' ?>><?= h($album['nombre']) ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
<?php endif; ?>
    </div>

<?php if ($total === 0): ?>
    <div class="alert alert-info mt-4" role="status">Todavía no hay fotos ni videos publicados. Vuelve pronto.</div>
<?php else: ?>
    <p class="small text-body-secondary my-3" aria-live="polite"><?= $total ?> <?= $total === 1 ? 'elemento' : 'elementos' ?></p>

    <ul class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 list-unstyled">
<?php foreach ($items as $item): ?>
<?php
$isVideo   = $item['tipo'] === 'Video';
$thumbnail = siteFile($isVideo ? $item['miniatura'] : $item['archivo'], $base);
$alt       = $item['texto_alt'] ?: $item['titulo'];
if ($isVideo) {
    // Enlace de YouTube o Vimeo → se inserta en la ventana; un archivo MP4 se reproduce con <video>
    $embed     = videoEmbedUrl($item['archivo']);
    $mediaKind = $embed !== '' ? 'embed' : (preg_match('#\.mp4$#i', $item['archivo']) ? 'video' : 'link');
    $mediaSrc  = $embed !== '' ? $embed : siteFile($item['archivo'], $base);
} else {
    $mediaKind = 'photo';
    $mediaSrc  = $thumbnail;
}
$badge = $item['album'] ? $badgeClasses[$item['id_album'] % count($badgeClasses)] : '';
?>
      <li class="col">
      <article class="card card-hover h-100 p-3">
        <button class="btn border-0 p-0 position-relative w-100" type="button" data-viewer data-bs-toggle="modal" data-bs-target="#viewer"
                data-kind="<?= $mediaKind ?>" data-src="<?= h($mediaSrc) ?>" data-title="<?= h($item['titulo']) ?>" data-alt="<?= h($alt) ?>"
                data-album="<?= h($item['album']) ?>" data-credit="<?= h($item['credito']) ?>" aria-label="Ver <?= $isVideo ? 'video' : 'foto' ?>: <?= h($item['titulo']) ?>">
          <img class="photo photo-4x3" src="<?= h($thumbnail) ?>" width="640" height="360" alt="<?= h($alt) ?>" loading="lazy">
<?php if ($isVideo): ?>
          <img class="position-absolute top-50 start-50 translate-middle bg-white rounded-circle shadow-sm p-3" src="<?= $base ?>assets/img/icons/play.svg" width="64" height="64" alt="">
<?php endif; ?>
        </button>
        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary">
<?php if ($item['album']): ?>
          <span class="badge rounded-pill <?= $badge ?>"><?= h($item['album']) ?></span>
<?php endif; ?>
<?php if ($item['fecha']): ?>
          <time datetime="<?= h($item['fecha']) ?>"><?= h(formatDateEs($item['fecha'])) ?></time>
<?php endif; ?>
        </div>
        <h2 class="card-title h5 fw-bold mt-2 mb-2"><?= h($item['titulo']) ?></h2>
<?php if ($item['descripcion']): ?>
        <p class="card-text text-body-secondary mb-3"><?= h($item['descripcion']) ?></p>
<?php endif; ?>
        <p class="small text-body-secondary mt-auto mb-0">Crédito: <?= h($item['credito'] ?: 'Por confirmar') ?></p>
      </article>
      </li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>
  </div>
</main>

<!-- Visor (ventana de Bootstrap): lo llena assets/js/gallery.js -->
<div class="modal fade" id="viewer" tabindex="-1" aria-labelledby="viewer-title" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title h5 fw-bold" id="viewer-title">Visor de galería</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="viewer-media"></div>
        <p class="mt-3 mb-0"><small class="text-body-secondary" id="viewer-caption"></small></p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-primary" id="viewer-prev">← Anterior</button>
        <span class="fw-bold" id="viewer-count"></span>
        <button type="button" class="btn btn-outline-primary" id="viewer-next">Siguiente →</button>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
