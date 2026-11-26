<?php
// Elimina un álbum (solo por POST, desde la ventana de confirmación). Con fotos o videos adentro no se borra.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar el álbum y cuántos elementos tiene
  $sql  = "SELECT nombre, (SELECT COUNT(*) FROM tblweb_galeria WHERE id_album = tblweb_albumes.id_album) AS total
           FROM tblweb_albumes WHERE id_album = ?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $album = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$album) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  } elseif ($album['total'] > 0) {
    // Quedarían sin álbum sin que nadie lo note
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se puede eliminar «' . $album['nombre'] . '» porque tiene ' . $album['total'] . ' elemento(s).'];
  } else {
    // 2. Borrar y dejar la bitácora
    $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_albumes WHERE id_album = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    panel_log($conexion, 'Baja', 'tblweb_albumes', (int) $id, 'Álbum: ' . $album['nombre']);
    $_SESSION['flash'] = ['type' => 'success', 'text' => 'Álbum eliminado.'];
  }
}

header('Location: ../../../admin/informacion/galeria/index.php?t=albums');
exit;
