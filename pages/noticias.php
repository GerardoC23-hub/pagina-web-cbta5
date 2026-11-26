<?php
$base        = '';
$pagina      = 'noticias';
$title       = 'Noticias y avisos · CBTa No. 5';
$description = 'Avisos, eventos y logros del CBTa No. 5 de Huejutla de Reyes, Hidalgo.';
$styles      = [];

// Lo que la persona escribió en el buscador y el filtro (vacío si no hay nada)
$search   = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Noticias de ejemplo (viven en un archivo aparte porque el detalle también las usa)
$news = require __DIR__ . '/includes/news-data.php';

// Color de la etiqueta de cada categoría
$badgeColors = [
    'Evento' => 'bg-info-subtle text-info-emphasis',
    'Aviso'  => 'bg-warning-subtle text-warning-emphasis',
    'Logro'  => 'bg-success-subtle text-success-emphasis',
];

// Se queda solo con las noticias que coinciden con la categoría y con lo escrito en el buscador
$results = [];
foreach ($news as $item) {
    $matchesCategory = $category === '' || $item['category'] === $category;
    $matchesText     = $search === '' || stripos($item['title'] . ' ' . $item['summary'], $search) !== false;

    if ($matchesCategory && $matchesText) {
        $results[] = $item;
    }
}
$total = count($results);

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">

  <!-- Encabezado de la página -->
  <section class="pt-5 pb-4" aria-labelledby="news-title">
    <div class="container">
      <h1 id="news-title">Noticias y avisos</h1>
      <p class="lead text-body-secondary mb-0">Avisos, eventos y logros del plantel.</p>
    </div>
  </section>

  <!-- Buscador y filtro por categoría -->
  <section class="pb-3" aria-label="Buscar noticias">
    <div class="container">
      <form class="row g-3 align-items-end" method="get" action="noticias.php">

        <div class="col-md-6">
          <label class="form-label" for="search">Buscar noticias</label>
          <input class="form-control" type="search" id="search" name="search" placeholder="Buscar por palabra…" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label" for="category">Categoría</label>
          <select class="form-select" id="category" name="category">
            <option value="">Todas las categorías</option>
            <option value="Evento"<?= $category === 'Evento' ? ' selected' : '' ?>>Evento</option>
            <option value="Aviso"<?= $category === 'Aviso' ? ' selected' : '' ?>>Aviso</option>
            <option value="Logro"<?= $category === 'Logro' ? ' selected' : '' ?>>Logro</option>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-center gap-3">
          <button class="btn btn-primary" type="submit">Buscar</button>
          <a class="link-success fw-bold" href="noticias.php">Limpiar filtros</a>
        </div>

      </form>
    </div>
  </section>

  <!-- Contador y tarjetas -->
  <section class="pb-5" aria-label="Publicaciones">
    <div class="container">

      <p class="small text-body-secondary" role="status"><?= $total ?> <?= $total === 1 ? 'publicación' : 'publicaciones' ?></p>

<?php if ($total === 0): ?>
      <div class="alert alert-info mb-0">No se encontraron publicaciones con esos datos. Prueba con otra búsqueda o limpia los filtros.</div>
<?php else: ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
<?php foreach ($results as $item): ?>
        <article class="col">
          <div class="card card-hover h-100 p-3">
            <img class="photo mb-3" src="assets/img/placeholder.svg" width="640" height="360" alt="Foto por agregar">

            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
              <span class="badge rounded-pill <?= $badgeColors[$item['category']] ?? 'bg-secondary-subtle text-secondary-emphasis' ?>"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
              <small class="text-body-secondary"><?= htmlspecialchars($item['date'], ENT_QUOTES, 'UTF-8') ?></small>
            </div>

            <h2 class="h5"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="text-body-secondary"><?= htmlspecialchars($item['summary'], ENT_QUOTES, 'UTF-8') ?></p>

            <a class="stretched-link card-link fw-bold mt-auto text-decoration-none" href="noticia.php?id=<?= (int) $item['id'] ?>">Leer más</a>
          </div>
        </article>
<?php endforeach; ?>
      </div>
<?php endif; ?>

    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
