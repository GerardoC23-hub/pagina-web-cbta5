<?php
$base   = '';
$pagina = 'noticias';
$styles = [];

// Noticias de ejemplo (el mismo archivo que usa la lista)
$news = require __DIR__ . '/includes/news-data.php';

// Color de la etiqueta de cada categoría
$badgeColors = [
    'Evento' => 'bg-info-subtle text-info-emphasis',
    'Aviso'  => 'bg-warning-subtle text-warning-emphasis',
    'Logro'  => 'bg-success-subtle text-success-emphasis',
];

// Busca la noticia que pide la dirección (noticia.php?id=3)
$id    = (int) ($_GET['id'] ?? 0);
$item  = null;
foreach ($news as $candidate) {
    if ($candidate['id'] === $id) {
        $item = $candidate;
    }
}

if ($item === null) {
    http_response_code(404);
    $title = 'Noticia no encontrada · CBTa No. 5';
} else {
    $title       = $item['title'] . ' · CBTa No. 5';
    $description = $item['summary'];
}

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-4 pb-5">

    <!-- Ruta de navegación -->
    <nav class="small mb-3" aria-label="Ruta de navegación">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="noticias.php">Noticias y avisos</a></li>
<?php if ($item !== null): ?>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></li>
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
      <span class="badge rounded-pill <?= $badgeColors[$item['category']] ?? 'bg-secondary-subtle text-secondary-emphasis' ?>"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
      <small class="text-body-secondary"><?= htmlspecialchars($item['date'], ENT_QUOTES, 'UTF-8') ?></small>
    </div>

    <h1 class="mb-4"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h1>

    <div class="row">
      <div class="col-lg-8">
        <img class="photo mb-4" src="assets/img/placeholder.svg" width="640" height="360" alt="Foto por agregar">

        <p class="lead text-body-secondary"><?= htmlspecialchars($item['summary'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><?= htmlspecialchars($item['content'], ENT_QUOTES, 'UTF-8') ?></p>

<?php if ($item['source'] !== ''): ?>
        <p class="small text-body-secondary">Fuente: <strong><?= htmlspecialchars($item['source'], ENT_QUOTES, 'UTF-8') ?></strong></p>
<?php endif; ?>

        <a class="btn btn-outline-primary mt-3" href="noticias.php">Volver a noticias y avisos</a>
      </div>
    </div>
<?php endif; ?>

  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
