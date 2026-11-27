<?php
$base        = '../';
$pagina      = 'servicios';
$title       = 'Servicios · CBTa No. 5';
$description = 'Trámites y apoyos que ofrece el CBTa No. 5 de Huejutla de Reyes a su comunidad.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/../backend/publico/servicios-datos.php';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>

<main id="principal">
  <div class="container">

    <div class="pt-4">
      <h1 class="mb-0">Servicios</h1>
      <p class="lead fs-5 fw-normal text-body-secondary mt-2 mb-0">Trámites y apoyos que ofrece el plantel a su comunidad.</p>
    </div>

<?php if (!$services): ?>
    <div class="alert alert-info mt-4">Todavía no hay servicios publicados.</div>
<?php else: ?>
    <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 list-unstyled mt-1">
<?php foreach ($services as $service): ?>
      <li class="col">
<?php require __DIR__ . '/../includes/service-card.php'; ?>
      </li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>

  </div>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
