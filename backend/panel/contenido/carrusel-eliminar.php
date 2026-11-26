<?php
// Elimina un slide del carrusel y su imagen. Solo se llama por POST desde la ventana de confirmar.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$listPage = '../../../panel/contenido/carrusel/index.php';
$siteRoot = realpath(__DIR__ . '/../../..');
$id       = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !is_numeric($id)) {
  header('Location: ' . $listPage);
  exit;
}

// 1. Buscar la imagen antes de borrar el registro
$stmt = mysqli_prepare($conexion, "SELECT titulo, imagen FROM tblweb_carrusel WHERE id_carrusel = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$slide = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$slide) {
  $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  header('Location: ' . $listPage);
  exit;
}

// 2. Borrar el registro
try {
  $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_carrusel WHERE id_carrusel = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  panel_log($conexion, 'Baja', 'tblweb_carrusel', (int) $id, $slide['titulo']);
} catch (mysqli_sql_exception $exception) {
  error_log('carrusel: ' . $exception->getMessage());

  $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se pudo eliminar.'];
  header('Location: ' . $listPage);
  exit;
}

// 3. Borrar el archivo (solo si está dentro de uploads/carrusel/)
if ($slide['imagen']) {
  $file = realpath($siteRoot . '/' . ltrim($slide['imagen'], '/'));

  if ($file !== false && strpos($file, $siteRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'carrusel' . DIRECTORY_SEPARATOR) === 0) {
    unlink($file);
  }
}

$_SESSION['flash'] = ['type' => 'success', 'text' => 'Slide eliminado.'];
header('Location: ' . $listPage);
exit;
