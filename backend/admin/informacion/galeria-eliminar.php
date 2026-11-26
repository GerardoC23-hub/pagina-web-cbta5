<?php
// Elimina una foto o video de la galería (solo por POST, desde la ventana de confirmación) y borra sus archivos.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id  = $_POST['id'] ?? null;
$tab = in_array($_POST['t'] ?? '', ['photos', 'videos'], true) ? $_POST['t'] : 'photos';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar los archivos antes de borrar el registro
  $stmt = mysqli_prepare($conexion, "SELECT titulo, tipo, archivo, miniatura FROM tblweb_galeria WHERE id_galeria = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($item) {
    try {
      // 2. Borrar el registro, dejar la bitácora y después borrar los archivos (los enlaces de video no son archivos)
      $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_galeria WHERE id_galeria = ?");
      mysqli_stmt_bind_param($stmt, "i", $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      panel_log($conexion, 'Baja', 'tblweb_galeria', (int) $id, ($item['tipo'] === 'Foto' ? 'Foto: ' : 'Video: ') . $item['titulo']);
      deleteImage($item['archivo']);
      deleteImage($item['miniatura']);

      $_SESSION['flash'] = ['type' => 'success', 'text' => 'Se eliminó «' . $item['titulo'] . '».'];
    } catch (mysqli_sql_exception $exception) {
      error_log('galeria: ' . $exception->getMessage());
      $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se pudo eliminar. Intenta de nuevo.'];
    }
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../admin/informacion/galeria/index.php?t=' . $tab);
exit;
