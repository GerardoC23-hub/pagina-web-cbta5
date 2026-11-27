<?php
// Elimina un producto (solo por POST, desde la ventana de confirmación) y borra su foto.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar el producto antes de borrar
  $stmt = mysqli_prepare($conexion, "SELECT nombre, imagen FROM tblweb_productos WHERE id_producto = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($item) {
    try {
      // 2. Borrar el registro, dejar la bitácora y después borrar la foto
      $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_productos WHERE id_producto = ?");
      mysqli_stmt_bind_param($stmt, "i", $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      panel_log($conexion, 'Baja', 'tblweb_productos', (int) $id, $item['nombre']);
      deleteImage($item['imagen']);

      $_SESSION['flash'] = ['type' => 'success', 'text' => 'Producto eliminado.'];
    } catch (mysqli_sql_exception $exception) {
      error_log('productos-eliminar: ' . $exception->getMessage());
      $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se pudo eliminar. Intenta de nuevo.'];
    }
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../admin/contenido/productos.php');
exit;
