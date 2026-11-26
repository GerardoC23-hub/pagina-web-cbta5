<?php
// Muestra u oculta una foto o video en el sitio (solo por POST) y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id  = $_POST['id'] ?? null;
$tab = in_array($_POST['t'] ?? '', ['photos', 'videos'], true) ? $_POST['t'] : 'photos';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar el registro
  $stmt = mysqli_prepare($conexion, "SELECT titulo, visible FROM tblweb_galeria WHERE id_galeria = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($item) {
    // 2. Cambiar la visibilidad y dejar la bitácora
    $stmt = mysqli_prepare($conexion, "UPDATE tblweb_galeria SET visible = 1 - visible WHERE id_galeria = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    panel_log($conexion, 'Cambio', 'tblweb_galeria', (int) $id, ($item['visible'] ? 'Ocultó: ' : 'Publicó: ') . $item['titulo']);
    $_SESSION['flash'] = ['type' => 'success', 'text' => $item['visible'] ? 'Oculto en la galería.' : 'Visible en la galería.'];
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../admin/informacion/galeria/index.php?t=' . $tab);
exit;
