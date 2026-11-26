<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/panel/informacion/personal-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Personal y directorio · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'personal';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Personal y directorio</h1>
      <p class="text-secondary mb-4">Solo se muestran en el sitio las personas autorizadas para publicarse. Fotos hasta 1 MB (JPG, PNG, WebP), siempre con crédito.</p>

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <p class="text-secondary small mb-0"><?= $total ?> <?= $total === 1 ? 'registro' : 'registros' ?></p>
        <a class="btn btn-primary" href="personal-formulario.php">+ Agregar persona</a>
      </div>

<?php if ($total > 0): ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista del personal y directorio</caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Foto</th>
                <th scope="col">Nombre</th>
                <th scope="col">Área</th>
                <th scope="col">Orden</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($people as $person): ?>
<?php $photo = $person['foto'] ? $base . $person['foto'] : $base . 'assets/img/placeholder.svg'; ?>
              <tr>
                <td><img class="rounded-circle object-fit-cover" src="<?= e($photo) ?>" width="48" height="48" alt="<?= $person['foto'] ? e('Foto de ' . $person['nombre']) : 'Foto por agregar' ?>"></td>
                <th class="fw-bold" scope="row">
                  <?= e($person['nombre']) ?>
                  <span class="d-block small fw-normal text-secondary"><?= e($person['cargo']) ?></span>
                </th>
                <td><?= e($person['tipo']) ?></td>
                <td><?= (int) $person['orden'] ?></td>
                <td>
<?php if ($person['autorizado']): ?>
                  <span class="badge rounded-pill text-bg-success">Publicado</span>
<?php else: ?>
                  <span class="badge rounded-pill text-bg-secondary">Sin autorizar</span>
<?php endif; ?>
                </td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="personal-formulario.php?id=<?= (int) $person['id_personal'] ?>">Editar</a>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $person['id_personal'], 'name' => $person['nombre']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                  </div>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php else: ?>
      <div class="alert alert-info" role="status">Todavía no hay personal. Usa «Agregar persona» para crear el primero.</div>
<?php endif; ?>

      <!-- Ventana: confirmar eliminar -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="../../backend/panel/informacion/personal-eliminar.php" method="post">
            <input type="hidden" name="id">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar persona?</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">Vas a eliminar a «<strong data-fill="name"></strong>» y su foto. Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </div>
          </form>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
