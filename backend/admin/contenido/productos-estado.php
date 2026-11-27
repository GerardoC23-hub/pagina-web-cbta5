<?php
// Publica o despublica un producto (solo por POST, desde el botón de la lista).

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar el producto y ver cómo está ahora
  $stmt = mysqli_prepare($conexion, "SELECT nombre, disponible FROM tblweb_productos WHERE id_producto = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($item) {
    try {
      // 2. Cambiar al estado contrario y dejar la bitácora
      $available = $item['disponible'] ? 0 : 1;

      $stmt = mysqli_prepare($conexion, "UPDATE tblweb_productos SET disponible = ? WHERE id_producto = ?");
      mysqli_stmt_bind_param($stmt, "ii", $available, $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      panel_log($conexion, 'Cambio', 'tblweb_productos', (int) $id, $item['nombre']);

      $_SESSION['flash'] = ['type' => 'success', 'text' => $available ? 'Producto publicado.' : 'Producto despublicado. Ya no se ve en el sitio.'];
    } catch (mysqli_sql_exception $exception) {
      error_log('productos-estado: ' . $exception->getMessage());
      $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se pudo cambiar. Intenta de nuevo.'];
    }
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../admin/contenido/productos.php');
exit;
