<?php
// Elimina una pregunta frecuente (solo por POST, desde la ventana de confirmación).

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar la pregunta antes de borrar
  $stmt = mysqli_prepare($conexion, "SELECT pregunta FROM tblweb_preguntas WHERE id_pregunta = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $faq = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($faq) {
    try {
      // 2. Borrar y dejar la bitácora
      $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_preguntas WHERE id_pregunta = ?");
      mysqli_stmt_bind_param($stmt, "i", $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      panel_log($conexion, 'Baja', 'tblweb_preguntas', (int) $id, $faq['pregunta']);
      $_SESSION['flash'] = ['type' => 'success', 'text' => 'Pregunta eliminada.'];
    } catch (mysqli_sql_exception $exception) {
      error_log('preguntas-eliminar: ' . $exception->getMessage());
      $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se pudo eliminar. Intenta de nuevo.'];
    }
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../admin/contenido/preguntas.php');
exit;
