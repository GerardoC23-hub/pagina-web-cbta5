<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/contenido/preguntas-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Preguntas frecuentes · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'preguntas';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Preguntas frecuentes</h1>
      <p class="text-secondary mb-4">Las preguntas visibles se muestran en la página «Preguntas frecuentes», de menor a mayor orden.</p>

      <div class="d-flex flex-wrap justify-content-end align-items-center gap-3 mb-3">
        <p class="text-secondary small mb-0"><?= $total ?> <?= $total === 1 ? 'pregunta' : 'preguntas' ?></p>
        <a class="btn btn-primary" href="preguntas-formulario.php">+ Agregar pregunta</a>
      </div>

<?php if ($total > 0): ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de preguntas frecuentes</caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Pregunta</th>
                <th scope="col">Orden</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($faqs as $faq): ?>
              <tr>
                <th class="fw-bold" scope="row">
                  <?= e($faq['pregunta']) ?>
                  <span class="d-block small fw-normal text-secondary"><?= e(mb_strimwidth($faq['respuesta'], 0, 110, '…')) ?></span>
                </th>
                <td><?= (int) $faq['orden'] ?></td>
                <td><span class="badge rounded-pill <?= $faq['visible'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $faq['visible'] ? 'Visible' : 'Oculta' ?></span></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="preguntas-formulario.php?id=<?= (int) $faq['id_pregunta'] ?>">Editar</a>
                    <form action="../../backend/admin/contenido/preguntas-visibilidad.php" method="post">
                      <input type="hidden" name="id" value="<?= (int) $faq['id_pregunta'] ?>">
                      <button class="btn btn-outline-primary btn-sm" type="submit"><?= $faq['visible'] ? 'Ocultar' : 'Mostrar' ?></button>
                    </form>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $faq['id_pregunta'], 'name' => $faq['pregunta']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                  </div>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php else: ?>
      <div class="alert alert-info" role="status">Todavía no hay preguntas. Usa «Agregar pregunta» para crear la primera.</div>
<?php endif; ?>

      <!-- Ventana: confirmar eliminar -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="../../backend/admin/contenido/preguntas-eliminar.php" method="post">
            <input type="hidden" name="id">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar pregunta?</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">Vas a eliminar «<strong data-fill="name"></strong>». Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </div>
          </form>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
