<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

$base = '../../';

// Resumen de ejemplo: se reemplaza por los datos reales de cada módulo más adelante
$summary = [
  ['number' => 1, 'label' => 'mensajes y solicitudes nuevos'],
  ['number' => 0, 'label' => 'noticias en borrador'],
  ['number' => 8, 'label' => 'documentos'],
];

// Datos de ejemplo del diseño: se reemplazan por la base de datos más adelante
$services = [
  ['name' => 'Servicios escolares',   'schedule' => 'Lunes a viernes, 8:00 a 14:00 h',             'description' => 'Constancias, historial académico y otros trámites.'],
  ['name' => 'Orientación educativa', 'schedule' => 'Lunes a viernes, 14:00 a 15:30 h (ejemplo)', 'description' => 'Ejemplo: acompañamiento académico a estudiantes.'],
];

$title     = 'Servicios · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$panelPage = 'servicios';

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
          <li class="nav-item"><a class="nav-link text-secondary" href="productos.php">Productos</a></li>
          <li class="nav-item"><a class="nav-link text-secondary" href="clubes.php">Clubes</a></li>
          <li class="nav-item"><a class="nav-link text-secondary" href="actividades.php">Actividades</a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="servicios.php">Servicios</a></li>
        </ul>
      </nav>

      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <p class="small text-secondary mb-0"><?= count($services) ?> servicios</p>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#service-modal">Nuevo servicio</button>
      </div>

      <div class="table-responsive border rounded bg-white">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Horario de atención</th>
              <th scope="col">Descripción</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($services as $service): ?>
            <tr>
              <th scope="row"><?= $service['name'] ?></th>
              <td><?= $service['schedule'] ?></td>
              <td><?= $service['description'] ?></td>
              <td>
                <div class="d-flex gap-2">
                  <button class="btn btn-outline-primary" type="button">Editar</button>
                  <button class="btn btn-danger" type="button">Eliminar</button>
                </div>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Ventana del formulario -->
      <div class="modal fade" id="service-modal" tabindex="-1" aria-labelledby="service-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
          <form class="modal-content">

            <div class="modal-header">
              <h2 class="modal-title h4 fw-bold" id="service-modal-title">Nuevo: servicio</h2>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
              <p class="small text-body-secondary">Los campos marcados con * son obligatorios.</p>

              <div class="mb-3">
                <label class="form-label" for="service-name">Nombre *</label>
                <input class="form-control" id="service-name" type="text" maxlength="100" placeholder="Ej. Orientación educativa">
              </div>

              <div class="mb-3">
                <label class="form-label" for="service-description">Descripción *</label>
                <textarea class="form-control" id="service-description" rows="4"></textarea>
                <div class="form-text">Se muestra en la tarjeta y en la página de detalle.</div>
              </div>

              <div class="mb-3">
                <label class="form-label" for="service-schedule">Horario de atención *</label>
                <input class="form-control" id="service-schedule" type="text" maxlength="100" value="Por confirmar">
              </div>

              <div class="mb-3">
                <label class="form-label" for="service-photo">Foto (opcional)</label>
                <input class="form-control" id="service-photo" type="file" accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Formatos: JPG, PNG o WebP. Máximo 1 MB.</div>
              </div>

              <div class="mb-3">
                <label class="form-label" for="service-photo-credit">Crédito de la foto</label>
                <input class="form-control" id="service-photo-credit" type="text" maxlength="100">
                <div class="form-text">Obligatorio si subes una foto: quién la tomó.</div>
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