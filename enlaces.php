<?php
$base        = '';
$pagina      = 'enlaces';
$title       = 'Enlaces de interés · CBTa No. 5';
$description = 'Enlaces oficiales y fuentes de información del CBTa No. 5 de Huejutla de Reyes, Hidalgo.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">

    <!-- Encabezado de la página -->
    <h1 class="mb-2">Enlaces de interés</h1>
    <p class="lead text-body-secondary mb-0">Hipervínculos oficiales y fuentes de la información de este sitio.</p>

    <!-- Del plantel -->
    <section class="section pb-0" aria-labelledby="links-plantel">
      <h2 class="section-title mb-4" id="links-plantel">Del plantel</h2>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">X: @CBTA5HUEJUTLA</h3>
            <p class="text-body-secondary">Cuenta oficial del plantel con avisos y actividades.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="https://x.com/CBTA5HUEJUTLA" target="_blank" rel="noopener">Abrir enlace<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
          </article>
        </div>

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">Sitio anterior del plantel (cbta5.net)</h3>
            <p class="text-body-secondary">Antecedentes históricos y oferta académica. Puede estar desactualizado.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="https://cbta5.net" target="_blank" rel="noopener">Abrir enlace<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
          </article>
        </div>

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">Cómo llegar (Google Maps)</h3>
            <p class="text-body-secondary">Boulevard Adolfo López Mateos S/N, Col. Aviación Civil, C.P. 43000, Huejutla de Reyes, Hidalgo.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="https://www.google.com/maps/search/?api=1&amp;query=Boulevard+Adolfo+L%C3%B3pez+Mateos+Huejutla+de+Reyes+Hidalgo" target="_blank" rel="noopener">Abrir enlace<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
          </article>
        </div>

      </div>
    </section>

    <!-- Institucionales -->
    <section class="section pb-0" aria-labelledby="links-institucionales">
      <h2 class="section-title mb-4" id="links-institucionales">Institucionales</h2>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">DGETAyCM</h3>
            <p class="text-body-secondary">Dirección General de Educación Tecnológica Agropecuaria y Ciencias del Mar.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="https://dgetaycm.sep.gob.mx" target="_blank" rel="noopener">Abrir enlace<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
          </article>
        </div>

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">Secretaría de Educación Pública</h3>
            <p class="text-body-secondary">Información y trámites de la SEP.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="https://www.gob.mx/sep" target="_blank" rel="noopener">Abrir enlace<span class="visually-hidden"> (se abre en una pestaña nueva)</span></a>
          </article>
        </div>

      </div>
    </section>

    <!-- Dentro de este sitio -->
    <section class="section pb-0" aria-labelledby="links-sitio">
      <h2 class="section-title mb-4" id="links-sitio">Dentro de este sitio</h2>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">Nuevo ingreso</h3>
            <p class="text-body-secondary">Requisitos, fechas y proceso.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="<?= $base ?>nuevo-ingreso.php">Ir</a>
          </article>
        </div>

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">Oferta educativa</h3>
            <p class="text-body-secondary">Las carreras técnicas del plantel.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="<?= $base ?>oferta.php">Ir</a>
          </article>
        </div>

        <div class="col">
          <article class="card card-hover h-100 p-3">
            <h3 class="h5">Contacto</h3>
            <p class="text-body-secondary">Teléfono, correo y formulario.</p>
            <a class="card-link stretched-link fw-bold mt-auto" href="<?= $base ?>contacto.php">Ir</a>
          </article>
        </div>

      </div>
    </section>

  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
