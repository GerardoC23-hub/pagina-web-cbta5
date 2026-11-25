<?php
$base        = '';
$pagina      = 'inicio';
$title       = 'CBTa No. 5 · Huejutla de Reyes, Hgo.';
$description = 'Sitio oficial del CBTa No. 5 de Huejutla de Reyes, Hidalgo: oferta educativa, nuevo ingreso, noticias, vida estudiantil y contacto.';
$styles      = [];

// Datos que muestra la página
require __DIR__ . '/backend/publico/inicio-datos.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">

  <!-- Hero: se desliza solo, sin flechas ni puntos -->
  <section class="pt-4" aria-label="Destacados">
    <div class="container">
<?php if ($slides): ?>
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="hover">
        <div class="carousel-inner">
<?php $isFirst = true; ?>
<?php foreach ($slides as $slide): ?>

          <div class="carousel-item<?= $isFirst ? ' active' : '' ?>">
            <div class="card p-4 p-lg-5"><div class="row g-4 g-lg-5 align-items-center">
              <div class="col-lg-6 d-flex flex-column align-items-start gap-3">
<?php if ($slide['etiqueta']): ?>
                <span class="badge rounded-pill bg-gold"><?= htmlspecialchars($slide['etiqueta'], ENT_QUOTES, 'UTF-8') ?></span>
<?php endif; ?>
<?php $titleTag = $isFirst ? 'h1' : 'h2'; ?>
                <<?= $titleTag ?> class="<?= $isFirst ? 'mb-0' : 'h1 mb-0' ?>"><?= htmlspecialchars($slide['titulo'], ENT_QUOTES, 'UTF-8') ?></<?= $titleTag ?>>
<?php if ($slide['descripcion']): ?>
                <p class="lead fs-5 fw-normal text-body-secondary mb-0"><?= htmlspecialchars($slide['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if ($slide['boton_texto'] || $slide['boton2_texto']): ?>
                <div class="d-flex flex-wrap gap-3">
<?php if ($slide['boton_texto']): ?>
                  <a class="btn btn-primary" href="<?= htmlspecialchars($slide['boton_enlace'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($slide['boton_texto'], ENT_QUOTES, 'UTF-8') ?></a>
<?php endif; ?>
<?php if ($slide['boton2_texto']): ?>
                  <a class="btn btn-outline-primary" href="<?= htmlspecialchars($slide['boton2_enlace'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($slide['boton2_texto'], ENT_QUOTES, 'UTF-8') ?></a>
<?php endif; ?>
                </div>
<?php endif; ?>
              </div>
              <div class="col-lg-6">
<?php if ($slide['imagen']): ?>
                <img class="photo photo-4x3" src="uploads/carrusel/<?= htmlspecialchars($slide['imagen'], ENT_QUOTES, 'UTF-8') ?>" width="640" height="360" alt="<?= htmlspecialchars($slide['imagen_alt'], ENT_QUOTES, 'UTF-8') ?>">
                <p class="small text-body-secondary mt-2 mb-0">Foto: <?= htmlspecialchars($slide['imagen_credito'], ENT_QUOTES, 'UTF-8') ?></p>
<?php else: ?>
                <img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="<?= htmlspecialchars($slide['imagen_alt'] ?: 'Foto por agregar', ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
              </div>
            </div></div>
          </div>
<?php $isFirst = false; ?>
<?php endforeach; ?>

        </div>
      </div>
<?php endif; ?>

      <nav aria-label="Accesos directos">
        <ul class="row row-cols-1 row-cols-md-3 g-3 list-unstyled mt-1 mb-0">
          <li class="col">
            <a class="card card-hover h-100 flex-row align-items-center gap-3 p-3 quick-link text-reset text-decoration-none" href="pages/nuevo-ingreso.php">
              <img class="rounded-circle bg-success-subtle p-2 flex-shrink-0" src="assets/img/icons/calendar.svg" width="44" height="44" alt="">
              <span><strong class="d-block">Nuevo ingreso</strong><small class="text-body-secondary">Requisitos y fechas</small></span>
            </a>
          </li>
          <li class="col">
            <a class="card card-hover h-100 flex-row align-items-center gap-3 p-3 quick-link text-reset text-decoration-none" href="pages/documentos.php">
              <img class="rounded-circle bg-success-subtle p-2 flex-shrink-0" src="assets/img/icons/document.svg" width="44" height="44" alt="">
              <span><strong class="d-block">Documentos</strong><small class="text-body-secondary">Convocatorias</small></span>
            </a>
          </li>
          <li class="col">
            <a class="card card-hover h-100 flex-row align-items-center gap-3 p-3 quick-link text-reset text-decoration-none" href="pages/solicitudes.php">
              <img class="rounded-circle bg-success-subtle p-2 flex-shrink-0" src="assets/img/icons/edit.svg" width="44" height="44" alt="">
              <span><strong class="d-block">Solicitudes en línea</strong><small class="text-body-secondary">Trámites sin ir al plantel</small></span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </section>

  <!-- Oferta educativa -->
  <section class="section" aria-labelledby="careers-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="careers-title">Oferta educativa</h2>
          <p class="text-body-secondary mt-1 mb-0">Elige la carrera técnica que se parezca más a ti.</p>
        </div>
        <a class="btn btn-outline-primary" href="pages/oferta.php">Ver toda la oferta</a>
      </div>
<?php if ($careers): ?>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 list-unstyled mb-0">
<?php foreach ($careers as $i => $career): ?>
        <li class="col<?= $i === 3 ? ' d-none d-md-block' : '' ?>">
          <article class="card card-hover h-100 p-4">
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="pages/oferta.php"><?= h($career['nombre']) ?></a></h3>
<?php if ($career['descripcion']): ?>
            <p class="card-text text-body-secondary mb-3"><?= h($career['descripcion']) ?></p>
<?php endif; ?>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p class="text-body-secondary mb-0">La oferta educativa se publicará pronto.</p>
<?php endif; ?>
    </div>
  </section>

  <!-- Nuevo ingreso -->
<?php if ($steps): ?>
  <section class="section bg-body-tertiary" aria-labelledby="admission-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="admission-title">Nuevo ingreso en <?= count($steps) ?> pasos</h2>
          <p class="text-body-secondary mt-1 mb-0"><?= h($admission['ingreso_nota'] ?? 'Las fechas se publicarán aquí.') ?></p>
        </div>
        <a class="btn btn-outline-primary" href="pages/nuevo-ingreso.php">Ver requisitos y fechas</a>
      </div>
      <ol class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 list-unstyled mb-0">
<?php foreach ($steps as $i => $step): ?>
        <li class="col"><div class="card h-100 flex-row gap-3 p-3"><span class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px" aria-hidden="true"><?= $i + 1 ?></span><span><strong class="d-block"><?= h($step['title']) ?></strong><span class="text-body-secondary"><?= h($step['text']) ?></span></span></div></li>
<?php endforeach; ?>
      </ol>
    </div>
  </section>
<?php endif; ?>

  <!-- Noticias -->
  <section class="section" aria-labelledby="news-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="news-title">Noticias y avisos</h2>
          <p class="text-body-secondary mt-1 mb-0">Lo más reciente del plantel.</p>
        </div>
        <a class="btn btn-outline-primary" href="pages/noticias.php">Ver todas</a>
      </div>
<?php if ($news): ?>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 list-unstyled mb-0">
<?php foreach ($news as $post): ?>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="<?= h(siteFile($post['imagen'], '')) ?>" width="640" height="360" alt="<?= h($post['titulo']) ?><?= $post['imagen'] ? '' : ' (foto por agregar)' ?>" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill <?= $newsBadges[$post['categoria']] ?? $newsBadges['Noticia'] ?>"><?= h($post['categoria']) ?></span><time datetime="<?= h($post['fecha_publicacion']) ?>"><?= h(formatDateEs($post['fecha_publicacion'])) ?></time></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="pages/noticias.php"><?= h($post['titulo']) ?></a></h3>
<?php if ($post['resumen']): ?>
            <p class="card-text text-body-secondary mb-3"><?= h($post['resumen']) ?></p>
<?php endif; ?>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Leer más</span>
          </article>
        </li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p class="text-body-secondary mb-0">Todavía no hay noticias publicadas.</p>
<?php endif; ?>
    </div>
  </section>

  <!-- Vida estudiantil -->
  <section class="section bg-body-tertiary" aria-labelledby="life-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="life-title">Vida estudiantil</h2>
          <p class="text-body-secondary mt-1 mb-0">Clubes, actividades y servicios del plantel.</p>
        </div>
        <a class="btn btn-outline-primary" href="pages/clubes.php">Ver todo</a>
      </div>
<?php if ($activities): ?>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 list-unstyled mb-0">
<?php foreach ($activities as $activity): ?>
<?php $activityLink = $activity['tipo'] === 'Servicio' ? 'pages/servicios.php' : 'pages/clubes.php'; ?>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="<?= h(siteFile($activity['imagen'], '')) ?>" width="640" height="360" alt="<?= h($activity['nombre']) ?><?= $activity['imagen'] ? '' : ' (foto por agregar)' ?>" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-body-tertiary text-body-secondary border"><?= h($activity['tipo']) ?></span></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="<?= $activityLink ?>"><?= h($activity['nombre']) ?></a></h3>
<?php if ($activity['descripcion']): ?>
            <p class="card-text text-body-secondary mb-3"><?= h($activity['descripcion']) ?></p>
<?php endif; ?>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver más</span>
          </article>
        </li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p class="text-body-secondary mb-0">Pronto publicaremos los clubes, actividades y servicios.</p>
<?php endif; ?>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
