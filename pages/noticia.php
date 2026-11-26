<?php
$base   = '../';
$pagina = 'noticias';
$styles = [];

// Datos que muestra la pantalla
require __DIR__ . '/../backend/publico/noticia-datos.php';

if ($item === null) {
    http_response_code(404);
    $title = 'Noticia no encontrada · CBTa No. 5';
} else {
    $title       = $item['titulo'] . ' · CBTa No. 5';
    $description = (string) $item['resumen'];
}

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>

<main id="principal">
  <div class="container py-4 pb-5">

    <!-- Ruta de navegación -->
    <nav class="small mb-3" aria-label="Ruta de navegación">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="noticias.php">Noticias y avisos</a></li>
<?php if ($item !== null): ?>
        <li class="breadcrumb-item active" aria-current="page"><?= h($item['titulo']) ?></li>
<?php endif; ?>
      </ol>
    </nav>

<?php if ($item === null): ?>
    <!-- Noticia que no existe -->
    <h1>Noticia no encontrada</h1>
    <p class="lead text-body-secondary">La publicación que buscas no existe o ya no está disponible.</p>
    <a class="btn btn-outline-primary" href="noticias.php">Volver a noticias y avisos</a>
<?php else: ?>
    <!-- Categoría y fecha -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
      <span class="badge rounded-pill <?= $badgeColors[$item['categoria']] ?? 'bg-secondary-subtle text-secondary-emphasis' ?>"><?= h($item['categoria']) ?></span>
      <small class="text-body-secondary"><?= h(formatDateEs($item['fecha_publicacion'])) ?></small>
    </div>

    <h1 class="mb-4"><?= h($item['titulo']) ?></h1>

    <div class="row">
      <div class="col-lg-8">
        <img class="photo mb-4" src="<?= h(siteFile($item['imagen'], $base)) ?>" width="640" height="360" alt="<?= h($item['titulo']) ?>">

<?php if ($item['imagen'] && $item['imagen_credito']): ?>
        <p class="small text-body-secondary mt-n3 mb-4">Foto: <?= h($item['imagen_credito']) ?></p>
<?php endif; ?>
<?php if ($item['resumen']): ?>
        <p class="lead text-body-secondary"><?= h($item['resumen']) ?></p>
<?php endif; ?>
        <div><?= nl2br(h($item['contenido'])) ?></div>
        <a class="btn btn-outline-primary mt-3" href="noticias.php">Volver a noticias y avisos</a>
      </div>
    </div>
<?php endif; ?>

  </div>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
