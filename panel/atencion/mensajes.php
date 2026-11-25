<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../config/conexion.php';

// Pestaña (mensajes o solicitudes) y filtro de estado
$tab = ($_GET['t'] ?? 'messages') === 'requests' ? 'requests' : 'messages';
$states = ['Nuevo', 'En revisión', 'Atendido'];
$filter = in_array($_GET['estado'] ?? '', $states, true) ? $_GET['estado'] : '';

// Cuántos hay sin atender en cada pestaña (para la insignia)
$newMessages = (int) mysqli_fetch_row(mysqli_query($conexion, "SELECT COUNT(*) FROM tblweb_mensajes WHERE estado = 'Nuevo'"))[0];
$newRequests = (int) mysqli_fetch_row(mysqli_query($conexion, "SELECT COUNT(*) FROM tblweb_solicitudes WHERE estado = 'Nuevo'"))[0];

// Lista de la pestaña activa: primero lo nuevo, luego lo más reciente
if ($tab === 'messages') {
    $sql = "SELECT id_mensaje AS id, folio, nombre, correo, matricula, asunto AS tema, mensaje AS detalle,
                   estado, nota_interna, recibido_en AS recibido, '' AS archivos
            FROM tblweb_mensajes";
} else {
    $sql = "SELECT s.id_solicitud AS id, s.folio, s.nombre, s.correo, s.matricula, s.tipo AS tema, s.descripcion AS detalle,
                   s.estado, s.nota_interna, s.recibida_en AS recibido,
                   (SELECT GROUP_CONCAT(a.nombre_original SEPARATOR ', ') FROM tblweb_solicitud_archivos a WHERE a.id_solicitud = s.id_solicitud) AS archivos
            FROM tblweb_solicitudes s";
}
$sql .= $filter !== '' ? " WHERE estado = ?" : "";
$sql .= " ORDER BY FIELD(estado, 'Nuevo', 'En revisión', 'Atendido'), recibido DESC";

$stmt = mysqli_prepare($conexion, $sql);
if ($filter !== '') {
    mysqli_stmt_bind_param($stmt, "s", $filter);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total  = mysqli_num_rows($result);

$badges = ['Nuevo' => 'text-bg-danger', 'En revisión' => 'text-bg-warning', 'Atendido' => 'text-bg-success'];

$base      = '../../';
$title     = 'Mensajes y solicitudes · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'mensajes';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Mensajes y solicitudes</h1>
      <p class="text-secondary mb-4">Lo que llega desde el formulario de contacto y las solicitudes en línea. Marca cada uno como «En revisión» o «Atendido» y deja una nota para el equipo.</p>

      <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link<?= $tab === 'messages' ? ' active' : '' ?>" href="mensajes.php?t=messages"<?= $tab === 'messages' ? ' aria-current="page"' : '' ?>>Mensajes<?php if ($newMessages): ?> <span class="badge text-bg-danger"><?= $newMessages ?> nuevos</span><?php endif; ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $tab === 'requests' ? ' active' : '' ?>" href="mensajes.php?t=requests"<?= $tab === 'requests' ? ' aria-current="page"' : '' ?>>Solicitudes<?php if ($newRequests): ?> <span class="badge text-bg-danger"><?= $newRequests ?> nuevas</span><?php endif; ?></a></li>
      </ul>

      <form class="d-flex flex-wrap align-items-center gap-2 mb-3" action="mensajes.php" method="get">
        <input type="hidden" name="t" value="<?= e($tab) ?>">
        <label class="form-label mb-0" for="estado">Estado</label>
        <select class="form-select w-auto" id="estado" name="estado" onchange="this.form.submit()">
          <option value="">Todos</option>
<?php foreach ($states as $state): ?>
          <option<?= $filter === $state ? ' selected' : '' ?>><?= e($state) ?></option>
<?php endforeach; ?>
        </select>
        <noscript><button class="btn btn-outline-primary" type="submit">Filtrar</button></noscript>
        <span class="text-secondary small ms-auto"><?= $total ?> <?= $tab === 'messages' ? ($total === 1 ? 'mensaje' : 'mensajes') : ($total === 1 ? 'solicitud' : 'solicitudes') ?></span>
      </form>

<?php if ($total === 0): ?>
      <div class="alert alert-info" role="status">No hay <?= $tab === 'messages' ? 'mensajes' : 'solicitudes' ?><?= $filter !== '' ? ' con ese estado' : ' por ahora' ?>.</div>
<?php else: ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de <?= $tab === 'messages' ? 'mensajes' : 'solicitudes' ?></caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Folio</th>
                <th scope="col">De</th>
                <th scope="col"><?= $tab === 'messages' ? 'Asunto' : 'Trámite' ?></th>
                <th scope="col">Recibido</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
<?php
$record = [
    'id' => $row['id'], 'tipo' => $tab === 'messages' ? 'mensaje' : 'solicitud', 'estado' => $row['estado'], 'nota_interna' => $row['nota_interna'] ?? '',
    'folio' => $row['folio'], 'nombre' => $row['nombre'], 'correo' => $row['correo'], 'matricula' => $row['matricula'] ?: 'No indicada',
    'tema' => $row['tema'], 'detalle' => $row['detalle'] ?? '', 'recibido' => date('d/m/Y H:i', strtotime($row['recibido'])),
    'archivos' => $row['archivos'] ?: 'Sin archivos',
];
?>
              <tr>
                <td class="small text-nowrap"><?= e($row['folio']) ?></td>
                <th class="fw-bold" scope="row">
                  <?= e($row['nombre']) ?>
                  <span class="d-block small fw-normal text-secondary"><?= e($row['correo']) ?></span>
                </th>
                <td><?= e($row['tema']) ?></td>
                <td class="small text-nowrap"><?= e($record['recibido']) ?></td>
                <td><span class="badge rounded-pill <?= $badges[$row['estado']] ?>"><?= e($row['estado']) ?></span></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#attend-modal" data-modal-title="<?= $tab === 'messages' ? 'Mensaje' : 'Solicitud' ?> <?= e($row['folio']) ?>" data-record="<?= e(json_encode($record, JSON_UNESCAPED_UNICODE)) ?>"><?= $row['estado'] === 'Atendido' ? 'Ver' : 'Atender' ?></button>
<?php if ($row['estado'] === 'Atendido'): ?>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $row['id'], 'tipo' => $record['tipo'], 'name' => $row['folio']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
<?php endif; ?>
                  </div>
                </td>
              </tr>
<?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php endif; ?>

      <!-- Ventana: ver y atender -->
      <div class="modal fade" id="attend-modal" tabindex="-1" aria-labelledby="attend-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <form class="modal-content needs-validation" action="atender.php" method="post" novalidate>
            <input type="hidden" name="t" value="<?= e($tab) ?>">
            <input type="hidden" name="tipo" value="">
            <input type="hidden" name="id" value="">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="attend-modal-title">Mensaje</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <dl class="row mb-3">
                <dt class="col-sm-3">De</dt>
                <dd class="col-sm-9"><span data-fill="nombre"></span> · <span data-fill="correo"></span></dd>
                <dt class="col-sm-3">Matrícula</dt>
                <dd class="col-sm-9" data-fill="matricula"></dd>
                <dt class="col-sm-3"><?= $tab === 'messages' ? 'Asunto' : 'Trámite' ?></dt>
                <dd class="col-sm-9" data-fill="tema"></dd>
                <dt class="col-sm-3">Recibido</dt>
                <dd class="col-sm-9" data-fill="recibido"></dd>
<?php if ($tab === 'requests'): ?>
                <dt class="col-sm-3">Archivos</dt>
                <dd class="col-sm-9" data-fill="archivos"></dd>
<?php endif; ?>
                <dt class="col-sm-3"><?= $tab === 'messages' ? 'Mensaje' : 'Descripción' ?></dt>
                <dd class="col-sm-9 mb-0" style="white-space: pre-wrap" data-fill="detalle"></dd>
              </dl>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label" for="attend-state">Estado *</label>
                  <select class="form-select" id="attend-state" name="estado" required>
<?php foreach ($states as $state): ?>
                    <option><?= e($state) ?></option>
<?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label" for="attend-note">Nota del equipo</label>
                  <textarea class="form-control" id="attend-note" name="nota_interna" rows="3" maxlength="500"></textarea>
                  <div class="form-text">Solo la ve el equipo del panel. Máximo 500 caracteres.</div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Ventana: confirmar eliminar -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="eliminar.php" method="post">
            <input type="hidden" name="t" value="<?= e($tab) ?>">
            <input type="hidden" name="tipo" value="">
            <input type="hidden" name="id" value="">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar <?= $tab === 'messages' ? 'mensaje' : 'solicitud' ?>?</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">Vas a eliminar «<strong data-fill="name"></strong>». Solo se pueden eliminar los ya atendidos. Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </div>
          </form>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
