<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

$base = '../../';

// Resumen de ejemplo
$summary = [
  ['number' => 1, 'label' => 'mensajes y solicitudes nuevos'],
  ['number' => 0, 'label' => 'noticias en borrador'],
  ['number' => 8, 'label' => 'documentos'],
];

// Datos de ejemplo del diseño
$products = [
  ['name' => 'Productos agrícolas', 'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
  ['name' => 'Abono',               'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
  ['name' => 'Plantas',             'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
  ['name' => 'Conejos',             'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
  ['name' => 'Huevo',               'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
  ['name' => 'Codorniz',            'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
  ['name' => 'Queso',               'price' => 'Desde $25', 'availability' => 'Venta en el plantel'],
];

$title     = 'Productos · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$panelPage = 'productos';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="fw-bold mb-2">Gestionar contenido</h1>
      <p class="text-secondary mb-4">Crea, edita, publica y elimina contenido. Lo publicado se ve de inmediato en el sitio.</p>

      <div class="row g-3 mb-4">
<?php foreach ($summary as $item): ?>
        <div class="col-12 col-md-4 col-xl-3">
          <div class="rounded bg-success-subtle p-3 h-100">
            <p class="h2 fw-bold mb-0"><?= $item['number'] ?></p>
            <p class="small fw-bold mb-0"><?= $item['label'] ?></p>
          </div>
        </div>
<?php endforeach; ?>
      </div>

      <nav aria-label="Módulos de contenido">
        <ul class="nav nav-underline border-bottom mb-4">
          <li class="nav-item"><a class="nav-link text-secondary" href="noticias.php">Noticias y avisos</a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="productos.php">Productos</a></li>
          <li class="nav-item"><a class="nav-link text-secondary" href="clubes.php">Clubes</a></li>
          <li class="nav-item"><a class="nav-link text-secondary" href="actividades.php">Actividades</a></li>
          <li class="nav-item"><a class="nav-link text-secondary" href="servicios.php">Servicios</a></li>
        </ul>
      </nav>

      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <p class="small text-secondary mb-0"><?= count($products) ?> productos</p>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#product-modal">Nuevo producto</button>
      </div>

      <div class="table-responsive border rounded bg-white">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">Producto</th>
              <th scope="col">Precio</th>
              <th scope="col">Disponibilidad</th>
              <th scope="col">Estado</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($products as $product): ?>
            <tr>
              <th scope="row"><?= $product['name'] ?></th>
              <td><?= $product['price'] ?></td>
              <td><?= $product['availability'] ?></td>
              <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Publicado</span></td>
              <td>
                <div class="d-flex flex-wrap gap-2">
                  <button class="btn btn-outline-primary" type="button">Editar</button>
                  <button class="btn btn-outline-primary" type="button">Despublicar</button>
                  <button class="btn btn-danger" type="button">Eliminar</button>
                </div>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Ventana del formulario: se abre con "Nuevo producto" -->
      <div class="modal fade" id="product-modal" tabindex="-1" aria-labelledby="product-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
          <form class="modal-content">

            <div class="modal-header">
              <h2 class="modal-title h4 fw-bold" id="product-modal-title">Nuevo: producto</h2>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
              <p class="small text-body-secondary">Los campos marcados con * son obligatorios.</p>

              <div class="mb-3">
                <label class="form-label" for="product-name">Nombre del producto *</label>
                <input class="form-control" id="product-name" type="text" maxlength="100">
              </div>

              <div class="mb-3">
                <label class="form-label" for="product-description">Descripción *</label>
                <textarea class="form-control" id="product-description" rows="4"></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label" for="product-price">Precio *</label>
                <input class="form-control" id="product-price" type="text" maxlength="50" value="Por confirmar">
                <div class="form-text">Puedes escribir «Por confirmar».</div>
              </div>

              <div class="mb-3">
                <label class="form-label" for="product-availability">Disponibilidad *</label>
                <input class="form-control" id="product-availability" type="text" maxlength="100" value="Venta en el plantel">
              </div>

              <div class="mb-3">
                <label class="form-label" for="product-status">Estado *</label>
                <select class="form-select" id="product-status">
                  <option value="draft">Borrador</option>
                  <option value="published">Publicado</option>
                </select>
              </div>
            </div>

            <div class="modal-footer justify-content-start">
              <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Guardar</button>
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>