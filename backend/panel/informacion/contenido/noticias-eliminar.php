<?php
// Elimina una noticia (solo por POST, desde la ventana de confirmación) y borra su imagen.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar la noticia antes de borrar
  $stmt = mysqli_prepare($conexion, "SELECT titulo, imagen FROM tblweb_publicaciones WHERE id_publicacion = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($item) {
    try {
      // 2. Borrar el registro, dejar la bitácora y después borrar la imagen
      $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_publicaciones WHERE id_publicacion = ?");
      mysqli_stmt_bind_param($stmt, "i", $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      panel_log($conexion, 'Baja', 'tblweb_publicaciones', (int) $id, $item['titulo']);
      deleteImage($item['imagen']);

      $_SESSION['flash'] = ['type' => 'success', 'text' => 'Noticia eliminada.'];
    } catch (mysqli_sql_exception $exception) {
      error_log('noticias-eliminar: ' . $exception->getMessage());
      $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se pudo eliminar. Intenta de nuevo.'];
    }
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../panel/contenido/noticias.php');
exit;
