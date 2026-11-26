<?php
$base        = '../';
$pagina      = 'noticias';
$title       = 'Noticias y avisos · CBTa No. 5';
$description = 'Avisos, eventos y logros del CBTa No. 5 de Huejutla de Reyes, Hidalgo.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/../backend/publico/noticias-datos.php';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
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
          <input class="form-control" type="search" id="search" name="search" placeholder="Buscar por palabra…" value="<?= h($search) ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label" for="category">Categoría</label>
          <select class="form-select" id="category" name="category">
            <option value="">Todas las categorías</option>
<?php foreach ($categories as $option): ?>
            <option value="<?= h($option) ?>"<?= $category === $option ? ' selected' : '' ?>><?= h($option) ?></option>
<?php endforeach; ?>
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
<?php foreach ($news as $item): ?>
        <article class="col">
          <div class="card card-hover h-100 p-3">
            <img class="photo mb-3" src="<?= h(siteFile($item['imagen'], $base)) ?>" width="640" height="360" alt="<?= h($item['titulo']) ?>" loading="lazy">

            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
              <span class="badge rounded-pill <?= $badgeColors[$item['categoria']] ?? 'bg-secondary-subtle text-secondary-emphasis' ?>"><?= h($item['categoria']) ?></span>
              <small class="text-body-secondary"><?= h(formatDateEs($item['fecha_publicacion'])) ?></small>
            </div>

            <h2 class="h5"><?= h($item['titulo']) ?></h2>
            <p class="text-body-secondary"><?= h($item['resumen']) ?></p>

            <a class="stretched-link card-link fw-bold mt-auto text-decoration-none" href="noticia.php?id=<?= (int) $item['id_publicacion'] ?>">Leer más</a>
          </div>
        </article>
<?php endforeach; ?>
      </div>
<?php endif; ?>

    </div>
  </section>

</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
