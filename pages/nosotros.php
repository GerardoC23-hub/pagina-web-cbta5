<?php
$base        = '../';
$pagina      = 'nosotros';
$title       = 'CBTa No. 5 · Huejutla de Reyes, Hgo.';
$description = 'Conócenos: historia, misión, visión y valores del CBTa No. 5 de Huejutla de Reyes, Hidalgo.';
$styles      = ['pages/about.css'];

// Datos que muestra la pantalla
require __DIR__ . '/../backend/publico/nosotros-datos.php';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>


<main id="principal">

  <!-- Encabezado de la página -->
  <section class="bg-brand text-white border-bottom border-4 border-gold" aria-labelledby="about-title">
    <div class="container py-5">
      <h1 class="text-white mb-2" id="about-title">Nosotros </h1>
      <p class="lead mb-0">Conoce la historia, la misión y los valores del plantel.</p>
    </div>
  </section>

  <!-- Historia y datos del plantel -->
  <section class="section" aria-labelledby="who-title">
    <div class="container">
      <div class="row g-4 g-lg-5">

<?php if (isset($about['historia'])): ?>
        <div class="col-lg-7">
          <h2 class="section-title" id="who-title"><?= h($about['historia']['titulo']) ?></h2>
          <p><?= nl2br(h($about['historia']['contenido'])) ?></p>
        </div>
<?php endif; ?>

<?php if (isset($about['nombre_oficial']) || isset($about['lema'])): ?>
        <div class="col-lg-5">
          <dl class="bg-success-subtle rounded p-4 mb-0">
<?php if (isset($about['nombre_oficial'])): ?>
            <dt class="small text-uppercase text-success"><?= h($about['nombre_oficial']['titulo']) ?></dt>
            <dd class="fw-medium"><?= h($about['nombre_oficial']['contenido']) ?></dd>
<?php endif; ?>
<?php if (isset($about['lema'])): ?>
            <dt class="small text-uppercase text-success"><?= h($about['lema']['titulo']) ?></dt>
            <dd class="fw-medium mb-0"><?= h($about['lema']['contenido']) ?></dd>
<?php endif; ?>
          </dl>
        </div>
<?php endif; ?>

      </div>
    </div>
  </section>

  <!-- Misión y visión -->
  <section class="pb-5" aria-label="Misión y visión">
    <div class="container">
      <div class="row g-4">

        <div class="col-md-6">
          <article class="card mission-card h-100 p-4">
            <span class="rounded-circle bg-success-subtle text-success d-inline-flex p-3 mb-3 align-self-start" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 4h7a3 3 0 0 1 3 3v13a2 2 0 0 0-2-2H2z"></path>
                <path d="M22 4h-7a3 3 0 0 0-3 3v13a2 2 0 0 1 2-2h8z"></path>
              </svg>
            </span>
            <h2 class="h4"><?= h($about['mision']['titulo'] ?? '') ?></h2>
            <p class="mb-0"><?= nl2br(h($about['mision']['contenido'] ?? '')) ?></p>
          </article>
        </div>

        <div class="col-md-6">
          <article class="card vision-card h-100 p-4">
            <span class="rounded-circle bg-danger-subtle text-danger d-inline-flex p-3 mb-3 align-self-start" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </span>
            <h2 class="h4"><?= h($about['vision']['titulo'] ?? '') ?></h2>
            <p class="mb-0"><?= nl2br(h($about['vision']['contenido'] ?? '')) ?></p>
          </article>
        </div>

      </div>
    </div>
  </section>

<?php if (isset($about['valores'])): ?>
  <!-- Valores -->
  <section class="pb-5" aria-labelledby="values-title">
    <div class="container">
      <h2 class="section-title" id="values-title"><?= h($about['valores']['titulo']) ?></h2>
      <p class="mb-0"><?= nl2br(h($about['valores']['contenido'])) ?></p>
    </div>
  </section>
<?php endif; ?>

  <!-- Enlace al directorio -->
  <section aria-label="Personal y directorio">
    <div class="container">
      <div class="card p-4 flex-row flex-wrap align-items-center justify-content-between gap-3">
        <div>
          <h2 class="h4 mb-1">Personal y directorio</h2>
          <p class="text-body-secondary mb-0">Directivos, docentes y departamentos del plantel.</p>
        </div>
        <a class="btn btn-outline-primary" href="personal.php">Ver directorio</a>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>