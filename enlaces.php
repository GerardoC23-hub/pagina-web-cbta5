<?php
$base        = '';
$pagina      = 'enlaces';
$title       = 'Enlaces de interés · CBTa No. 5';
$description = 'Enlaces oficiales y fuentes de información del CBTa No. 5 de Huejutla de Reyes, Hidalgo.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/backend/publico/enlaces-datos.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">

    <!-- Encabezado de la página -->
    <h1 class="mb-2">Enlaces de interés</h1>
    <p class="lead text-body-secondary mb-0">Hipervínculos oficiales y fuentes de la información de este sitio.</p>

    <section class="section pb-0" aria-label="Enlaces">
<?php if (!$links): ?>
      <div class="alert alert-info mb-0">Todavía no hay enlaces publicados. Vuelve pronto.</div>
<?php else: ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
<?php foreach ($links as $link): ?>
        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h2 class="h5"><?= h($link['titulo']) ?></h2>
<?php if ($link['descripcion']): ?>
            <p class="text-body-secondary"><?= h($link['descripcion']) ?></p>
<?php endif; ?>
            <a class="card-link stretched-link fw-bold mt-auto" href="<?= h($link['url']) ?>" target="_blank" rel="noopener">Abrir enlace<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
          </article>
        </div>
<?php endforeach; ?>
      </div>
<?php endif; ?>
    </section>

  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
