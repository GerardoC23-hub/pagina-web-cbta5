<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

$maxVisible = 5;   // más slides en el inicio se vuelve pesado: se pide ocultar uno antes

$sql       = "SELECT * FROM tblweb_carrusel ORDER BY orden ASC, id_carrusel DESC";
$resultado = mysqli_query($conexion, $sql);
$total     = mysqli_num_rows($resultado);
$today     = date('Y-m-d');

$base      = '../../../';
$title     = 'Carrusel del inicio · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'carrusel';

require __DIR__ . '/../../../includes/head.php';
require __DIR__ . '/../../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Carrusel del inicio</h1>
      <p class="text-secondary mb-4">Slides que se deslizan solos en la página de inicio. Imágenes hasta 1 MB (JPG, PNG, WebP), siempre con texto alternativo y crédito. Máximo <?= $maxVisible ?> slides visibles.</p>

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <p class="text-secondary small mb-0"><?= $total ?> <?= $total === 1 ? 'slide' : 'slides' ?></p>
        <a class="btn btn-primary" href="formulario.php">+ Agregar slide</a>
      </div>

<?php if ($total > 0): ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de slides del carrusel</caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Imagen</th>
                <th scope="col">Título</th>
                <th scope="col">Orden</th>
                <th scope="col">Fechas</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php while ($slide = mysqli_fetch_assoc($resultado)): ?>
<?php
if (!$slide['visible']) {
    $status = ['Oculto', 'text-bg-secondary'];
} elseif (($slide['fecha_inicio'] && $slide['fecha_inicio'] > $today) || ($slide['fecha_fin'] && $slide['fecha_fin'] < $today)) {
    $status = ['Fuera de fechas', 'text-bg-warning'];
} else {
    $status = ['Visible', 'text-bg-success'];
}
$image = $slide['imagen'] ? $base . 'uploads/carrusel/' . $slide['imagen'] : $base . 'assets/img/placeholder.svg';
?>
              <tr>
                <td><img class="rounded" src="<?= e($image) ?>" width="96" height="54" alt="<?= e($slide['imagen_alt'] ?: 'Foto por agregar') ?>"></td>
                <th class="fw-bold" scope="row">
                  <?= e($slide['titulo']) ?>
<?php if ($slide['etiqueta']): ?>
                  <span class="d-block small fw-normal text-secondary"><?= e($slide['etiqueta']) ?></span>
<?php endif; ?>
                </th>
                <td><?= (int) $slide['orden'] ?></td>
                <td class="small text-nowrap">
<?php if ($slide['fecha_inicio'] || $slide['fecha_fin']): ?>
                  <?= $slide['fecha_inicio'] ? date('d/m/Y', strtotime($slide['fecha_inicio'])) : '…' ?> – <?= $slide['fecha_fin'] ? date('d/m/Y', strtotime($slide['fecha_fin'])) : '…' ?>
<?php else: ?>
                  Sin fechas
<?php endif; ?>
                </td>
                <td><span class="badge rounded-pill <?= $status[1] ?>"><?= $status[0] ?></span></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="formulario.php?id=<?= (int) $slide['id_carrusel'] ?>">Editar</a>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $slide['id_carrusel'], 'name' => $slide['titulo']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                  </div>
                </td>
              </tr>
<?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php else: ?>
      <div class="alert alert-info" role="status">Todavía no hay slides. Usa «Agregar slide» para crear el primero; mientras tanto el inicio no muestra carrusel.</div>
<?php endif; ?>

      <!-- Ventana: confirmar eliminar -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="eliminar.php" method="post">
            <input type="hidden" name="id">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar slide?</h2>
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

<?php require __DIR__ . '/../../../includes/panel-layout-end.php'; ?>
