<?php
$base        = '../';
$pagina      = 'productos';
$title       = 'Productos · CBTa No. 5';
$description = 'Productos que genera el plantel del CBTa No. 5 de Huejutla de Reyes. Venta en el plantel, sin venta en línea.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/../backend/publico/productos-datos.php';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>

<main id="principal">
  <div class="container">

    <div class="pt-4">
      <h1 class="mb-0">Productos</h1>
      <p class="lead fs-5 fw-normal text-body-secondary mt-2 mb-0">Productos que genera el plantel. Sin venta en línea.</p>
    </div>

    <div class="alert alert-info d-flex align-items-center gap-2 my-4" role="alert">
      <svg class="flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
      </svg>
<?php if ($phone): ?>
      <div>Los productos se venden en el plantel. Los precios se confirman directamente: llama al <strong><?= h($phone) ?></strong> o visítanos.</div>
<?php else: ?>
      <div>Los productos se venden en el plantel. Los precios se confirman directamente: visítanos.</div>
<?php endif; ?>
    </div>

<?php if (!$products): ?>
    <div class="alert alert-secondary">Todavía no hay productos publicados.</div>
<?php else: ?>
    <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 list-unstyled">
<?php foreach ($products as $product): ?>
      <li class="col">
        <article class="card h-100 p-3">
          <img class="photo" src="<?= h(siteFile($product['imagen'], $base)) ?>" width="640" height="360" alt="<?= h($product['nombre']) ?>" loading="lazy">
          <h2 class="card-title h5 fw-bold mt-3 mb-2"><?= h($product['nombre']) ?></h2>
          <p class="card-text text-body-secondary mb-3"><?= $product['price_text'] !== '' ? 'Precio: ' . h($product['price_text']) . ' · ' : '' ?>Venta en el plantel</p>
          <div class="mt-auto">
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-<?= (int) $product['id_producto'] ?>">Ver detalle</button>
          </div>
        </article>
      </li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>

  </div>
</main>

<!-- Ventana de detalle (una por producto) -->
<?php foreach ($products as $product): ?>
<div class="modal fade" id="modal-<?= (int) $product['id_producto'] ?>" tabindex="-1" aria-labelledby="modal-title-<?= (int) $product['id_producto'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title h5 fw-bold" id="modal-title-<?= (int) $product['id_producto'] ?>"><?= h($product['nombre']) ?></h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <img class="photo mb-3" src="<?= h(siteFile($product['imagen'], $base)) ?>" width="640" height="360" alt="<?= h($product['nombre']) ?>">
        <p class="text-body-secondary">
          Producto elaborado por estudiantes en las prácticas del plantel.<br>
          <?= h($product['descripcion']) ?>
        </p>
        <ul class="text-body-secondary mb-0">
<?php if ($product['price_text'] !== ''): ?>
          <li>Precio: <?= h($product['price_text']) ?></li>
<?php endif; ?>
          <li>Venta en el plantel</li>
          <li>Sin venta en línea</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
