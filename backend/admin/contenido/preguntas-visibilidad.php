<?php
// Muestra u oculta una pregunta en el sitio (solo por POST) y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null && ctype_digit((string) $id)) {

  // 1. Buscar la pregunta
  $stmt = mysqli_prepare($conexion, "SELECT pregunta, visible FROM tblweb_preguntas WHERE id_pregunta = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $faq = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($faq) {
    // 2. Cambiar la visibilidad y dejar la bitácora
    $stmt = mysqli_prepare($conexion, "UPDATE tblweb_preguntas SET visible = 1 - visible WHERE id_pregunta = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    panel_log($conexion, 'Cambio', 'tblweb_preguntas', (int) $id, ($faq['visible'] ? 'Ocultó: ' : 'Publicó: ') . $faq['pregunta']);
    $_SESSION['flash'] = ['type' => 'success', 'text' => $faq['visible'] ? 'Oculta en el sitio.' : 'Visible en el sitio.'];
  } else {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
  }
}

header('Location: ../../../admin/contenido/preguntas.php');
exit;
