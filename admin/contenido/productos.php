<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/contenido/productos-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Productos · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'productos';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="fw-bold mb-2">Gestionar contenido</h1>
      <p class="text-secondary mb-4">Crea, edita, publica y elimina contenido. Lo publicado se ve de inmediato en el sitio.</p>

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
        <p class="small text-secondary mb-0"><?= $total ?> <?= $total === 1 ? 'producto' : 'productos' ?></p>
        <a class="btn btn-primary" href="productos-formulario.php">Nuevo producto</a>
      </div>

<?php if ($total > 0): ?>
      <div class="table-responsive border rounded bg-white">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">Producto</th>
              <th scope="col">Precio</th>
              <th scope="col">Estado</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($products as $product): ?>
            <tr>
              <th scope="row"><?= e($product['nombre']) ?></th>
              <td><?= e($product['price_text']) ?: '—' ?></td>
              <td>
<?php if ($product['disponible']): ?>
                <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Publicado</span>
<?php else: ?>
                <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Borrador</span>
<?php endif; ?>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a class="btn btn-outline-primary" href="productos-formulario.php?id=<?= (int) $product['id_producto'] ?>">Editar</a>
                  <form action="../../backend/admin/contenido/productos-estado.php" method="post">
                    <input type="hidden" name="id" value="<?= (int) $product['id_producto'] ?>">
                    <button class="btn btn-outline-primary" type="submit"><?= $product['disponible'] ? 'Despublicar' : 'Publicar' ?></button>
                  </form>
                  <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $product['id_producto'], 'name' => $product['nombre']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                </div>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
<?php else: ?>
      <div class="alert alert-info" role="status">No hay productos todavía. Usa «Nuevo producto» para crear el primero.</div>
<?php endif; ?>

      <!-- Ventana: confirmar eliminar -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="../../backend/admin/contenido/productos-eliminar.php" method="post">
            <input type="hidden" name="id">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar producto?</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">Vas a eliminar «<strong data-fill="name"></strong>» y su foto. Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </div>
          </form>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
