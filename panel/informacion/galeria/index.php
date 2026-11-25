<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

// Mostrar u ocultar una foto o video (POST) y volver a la lista (patrón PRG)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id  = $_POST['id'] ?? null;
    $tab = in_array($_POST['t'] ?? '', ['photos', 'videos'], true) ? $_POST['t'] : 'photos';

    if ($id && is_numeric($id)) {
        $sql  = "SELECT titulo, visible FROM tblweb_galeria WHERE id_galeria = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($item) {
            $sql  = "UPDATE tblweb_galeria SET visible = 1 - visible WHERE id_galeria = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            panel_log($conexion, 'Cambio', 'tblweb_galeria', (int) $id, ($item['visible'] ? 'Ocultó: ' : 'Publicó: ') . $item['titulo']);
            $_SESSION['flash'] = ['type' => 'success', 'text' => $item['visible'] ? 'Oculto en la galería.' : 'Visible en la galería.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
        }
    }

    header('Location: index.php?t=' . $tab);
    exit;
}

// Pestaña activa: fotos, videos o álbumes
$tab = $_GET['t'] ?? 'photos';
if (!in_array($tab, ['photos', 'videos', 'albums'], true)) {
    $tab = 'photos';
}
$tabs = ['photos' => 'Fotos', 'videos' => 'Videos', 'albums' => 'Álbumes'];

if ($tab === 'albums') {
    $sql   = "SELECT a.id_album, a.nombre, a.orden, COUNT(g.id_galeria) AS total
              FROM tblweb_albumes a
              LEFT JOIN tblweb_galeria g ON g.id_album = a.id_album
              GROUP BY a.id_album, a.nombre, a.orden
              ORDER BY a.orden, a.nombre";
    $result = mysqli_query($conexion, $sql);
} else {
    $type  = $tab === 'photos' ? 'Foto' : 'Video';
    $sql   = "SELECT g.*, a.nombre AS album
              FROM tblweb_galeria g
              LEFT JOIN tblweb_albumes a ON a.id_album = g.id_album
              WHERE g.tipo = ?
              ORDER BY g.orden, g.id_galeria DESC";
    $stmt  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $type);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
$total = mysqli_num_rows($result);

$base      = '../../../';
$title     = 'Galería · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'galeria';

require __DIR__ . '/../../../includes/head.php';
require __DIR__ . '/../../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Galería</h1>
      <p class="text-secondary mb-4">Fotos y videos del plantel, agrupados en álbumes. Fotos hasta 2 MB (JPG, PNG, WebP), siempre con texto alternativo. Los videos se agregan con un enlace de YouTube o Vimeo, o con un archivo MP4 de hasta 10 MB.</p>

      <ul class="nav nav-tabs mb-3">
<?php foreach ($tabs as $key => $label): ?>
        <li class="nav-item"><a class="nav-link<?= $tab === $key ? ' active' : '' ?>" href="index.php?t=<?= $key ?>"<?= $tab === $key ? ' aria-current="page"' : '' ?>><?= $label ?></a></li>
<?php endforeach; ?>
      </ul>

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <p class="text-secondary small mb-0"><?= $total ?> <?= $tab === 'albums' ? ($total === 1 ? 'álbum' : 'álbumes') : ($tab === 'photos' ? ($total === 1 ? 'foto' : 'fotos') : ($total === 1 ? 'video' : 'videos')) ?></p>
<?php if ($tab === 'albums'): ?>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#album-modal" data-modal-title="Agregar álbum">+ Agregar álbum</button>
<?php else: ?>
        <a class="btn btn-primary" href="formulario.php?tipo=<?= $tab === 'photos' ? 'Foto' : 'Video' ?>">+ Agregar <?= $tab === 'photos' ? 'foto' : 'video' ?></a>
<?php endif; ?>
      </div>

<?php if ($total === 0): ?>
      <div class="alert alert-info" role="status">Todavía no hay nada en esta sección. Usa el botón «Agregar» para crear el primero.</div>
<?php elseif ($tab === 'albums'): ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de álbumes</caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Orden</th>
                <th scope="col">Elementos</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php while ($album = mysqli_fetch_assoc($result)): ?>
              <tr>
                <th class="fw-bold" scope="row"><?= e($album['nombre']) ?></th>
                <td><?= (int) $album['orden'] ?></td>
                <td><?= (int) $album['total'] ?></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#album-modal" data-modal-title="Editar álbum" data-record="<?= e(json_encode(['id' => $album['id_album'], 'nombre' => $album['nombre'], 'orden' => $album['orden']], JSON_UNESCAPED_UNICODE)) ?>">Editar</button>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-album-modal" data-record="<?= e(json_encode(['id' => $album['id_album'], 'name' => $album['nombre']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                  </div>
                </td>
              </tr>
<?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php else: ?>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de <?= $tab === 'photos' ? 'fotos' : 'videos' ?></caption>
            <thead class="table-light">
              <tr>
                <th scope="col"><?= $tab === 'photos' ? 'Foto' : 'Portada' ?></th>
                <th scope="col">Título</th>
                <th scope="col">Álbum</th>
                <th scope="col">Orden</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php while ($item = mysqli_fetch_assoc($result)): ?>
<?php
$file  = $tab === 'photos' ? $item['archivo'] : $item['miniatura'];
$image = $file ? $base . $file : $base . 'assets/img/placeholder.svg';
?>
              <tr>
                <td><img class="rounded" src="<?= e($image) ?>" width="96" height="64" alt="<?= e($item['texto_alt'] ?: 'Sin imagen') ?>"></td>
                <th class="fw-bold" scope="row">
                  <?= e($item['titulo']) ?>
<?php if ($item['credito']): ?>
                  <span class="d-block small fw-normal text-secondary">Crédito: <?= e($item['credito']) ?></span>
<?php endif; ?>
                </th>
                <td><?= $item['album'] ? e($item['album']) : '<span class="text-secondary">Sin álbum</span>' ?></td>
                <td><?= (int) $item['orden'] ?></td>
                <td><span class="badge rounded-pill <?= $item['visible'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $item['visible'] ? 'Publicado' : 'Oculto' ?></span></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="formulario.php?id=<?= (int) $item['id_galeria'] ?>">Editar</a>
                    <form action="index.php" method="post">
                      <input type="hidden" name="id" value="<?= (int) $item['id_galeria'] ?>">
                      <input type="hidden" name="t" value="<?= $tab ?>">
                      <button class="btn btn-outline-primary btn-sm" type="submit"><?= $item['visible'] ? 'Ocultar' : 'Publicar' ?></button>
                    </form>
                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-record="<?= e(json_encode(['id' => $item['id_galeria'], 'name' => $item['titulo']], JSON_UNESCAPED_UNICODE)) ?>">Eliminar</button>
                  </div>
                </td>
              </tr>
<?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
<?php endif; ?>

      <!-- Ventana: agregar o editar álbum -->
      <div class="modal fade" id="album-modal" tabindex="-1" aria-labelledby="album-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content needs-validation" action="albumes.php" method="post" novalidate>
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="album-modal-title">Agregar álbum</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label" for="album-name">Nombre *</label>
                <input class="form-control" id="album-name" name="nombre" type="text" minlength="2" maxlength="60" required>
                <div class="invalid-feedback">Escribe el nombre del álbum (de 2 a 60 caracteres).</div>
              </div>
              <div>
                <label class="form-label" for="album-order">Orden</label>
                <input class="form-control" id="album-order" name="orden" type="number" min="0" max="999" value="0">
                <div class="form-text">Los álbumes con número menor salen primero.</div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Ventana: confirmar eliminar álbum -->
      <div class="modal fade" id="delete-album-modal" tabindex="-1" aria-labelledby="delete-album-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="albumes.php" method="post">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-album-title">¿Eliminar álbum?</h2>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">Vas a eliminar el álbum «<strong data-fill="name"></strong>». Solo se puede si no tiene fotos ni videos.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Ventana: confirmar eliminar foto o video -->
      <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form class="modal-content" action="eliminar.php" method="post">
            <input type="hidden" name="id">
            <input type="hidden" name="t" value="<?= e($tab) ?>">
            <div class="modal-header">
              <h2 class="modal-title h5 fw-bold" id="delete-modal-title">¿Eliminar <?= $tab === 'videos' ? 'video' : 'foto' ?>?</h2>
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
