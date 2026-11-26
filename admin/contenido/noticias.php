<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/contenido/noticias-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Noticias y avisos · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'noticias';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Noticias y avisos</h1>
      <p class="text-secondary mb-4">Solo las noticias en estado «Publicada» y con fecha de hoy o anterior se ven en el sitio. Imágenes hasta 1 MB (JPG, PNG, WebP), siempre con crédito.</p>

      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
        <form class="d-flex flex-wrap align-items-end gap-2" method="get" aria-label="Filtrar noticias">
          <div>
            <label class="form-label small mb-1" for="categoria">Categoría</label>
            <select class="form-select form-select-sm" id="categoria" name="categoria">
              <option value="">Todas</option>
<?php foreach ($categories as $option): ?>
              <option value="<?= $option ?>"<?= $category === $option ? ' selected' : '' ?>><?= $option ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label small mb-1" for="estado">Estado</label>
            <select class="form-select form-select-sm" id="estado" name="estado">
              <option value="">Todos</option>
<?php foreach ($states as $option): ?>
              <option value="<?= $option ?>"<?= $state === $option ? ' selected' : '' ?>><?= $option ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <button class="btn btn-outline-primary btn-sm" type="submit">Filtrar</button>
<?php if ($category !== '' || $state !== ''): ?>
          <a class="btn btn-link btn-sm" href="noticias.php">Quitar filtros</a>
<?php endif; ?>
        </form>
        <div class="d-flex align-items-center gap-3">
          <p class="text-secondary small mb-0"><?= $total ?> <?= $total === 1 ? 'noticia' : 'noticias' ?></p>
          <a class="btn btn-primary" href="noticias-formulario.php">+ Agregar noticia</a>
        </div>
      </div>

<?php if ($total > 0): ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de noticias y avisos</caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Título</th>
                <th scope="col">Categoría</th>
                <th scope="col">Fecha</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($news as $item): ?>
<?php
if ($item['estado'] === 'Borrador') {
    $status = ['Borrador', 'text-bg-secondary'];
} elseif ($item['fecha_publicacion'] > $today) {
    $status = ['Programada', 'text-bg-warning'];
} else {
    $status = ['Publicada', 'text-bg-success'];
}
?>
              <tr>
                <th class="fw-bold" scope="row">
                  <?= e($item['titulo']) ?>
<?php if ($item['solo_alumnos']): ?>
                  <span class="d-block small fw-normal text-secondary">Solo para alumnos</span>
<?php endif; ?>
                </th>
                <td><?= e($item['categoria']) ?></td>
                <td class="text-nowrap"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></td>
                <td><span class="badge rounded-pill <?= $status[1] ?>"><?= $status[0] ?></span></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="noticias-formulario.php?id=<?= (int) $item['id_publicacion'] ?>">Editar</a>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $item['id_publicacion'], 'name' => $item['titulo']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                  </div>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php else: ?>
      <div class="alert alert-info" role="status">No hay noticias<?= ($category !== '' || $state !== '') ? ' con esos filtros' : ' todavía. Usa «Agregar noticia» para crear la primera' ?>.</div>
<?php endif; ?>

      <!-- Ventana: confirmar eliminar -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="../../backend/admin/contenido/noticias-eliminar.php" method="post">
            <input type="hidden" name="id">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar noticia?</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">Vas a eliminar «<strong data-fill="name"></strong>» y su imagen. Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </div>
          </form>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
