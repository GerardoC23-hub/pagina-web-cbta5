<?php
// Guarda un texto legal (privacidad, términos o accesibilidad): valida y actualiza la base.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-validation.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$listPage = '../../../admin/informacion/legales.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $listPage);
  exit;
}

$key      = $_POST['clave'] ?? '';
$formPage = '../../../admin/informacion/legales-formulario.php?clave=' . urlencode($key);

// 1. Comprobar que sea un texto legal que existe
$stmt = mysqli_prepare($conexion, "SELECT titulo FROM tblweb_secciones WHERE clave = ? AND grupo = 'Legal'");
mysqli_stmt_bind_param($stmt, "s", $key);
mysqli_stmt_execute($stmt);
$found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$found) {
  $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ese texto no existe.'];
  header('Location: ' . $listPage);
  exit;
}

// 2. Recibir (el contenido conserva sus saltos de línea)
$legal = [
  'titulo'    => trim($_POST['titulo'] ?? ''),
  'contenido' => str_replace("\r\n", "\n", trim($_POST['contenido'] ?? '')),
];

// 3. Validar
$errors = [];

if (($message = textError($legal['titulo'], 'el título', 5, 120)) !== '') {
  $errors['titulo'] = $message;
}
if (($message = textError($legal['contenido'], 'el texto', 100, 10000)) !== '') {
  $errors['contenido'] = $message;
} elseif (strpos($legal['contenido'], '## ') === false) {
  $errors['contenido'] = 'Agrega al menos una sección que empiece con ## (por ejemplo: ## Uso del sitio).';
}

// Con errores: se regresa al formulario con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $legal;
  header('Location: ' . $formPage);
  exit;
}

// 4. Guardar
try {
  $stmt = mysqli_prepare($conexion, "UPDATE tblweb_secciones SET titulo = ?, contenido = ? WHERE clave = ? AND grupo = 'Legal'");
  mysqli_stmt_bind_param($stmt, "sss", $legal['titulo'], $legal['contenido'], $key);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  panel_log($conexion, 'Cambio', 'tblweb_secciones', null, 'Texto legal: ' . $legal['titulo']);
  $_SESSION['flash'] = ['type' => 'success', 'text' => 'Texto guardado. Ya se ve en el sitio.'];
  header('Location: ' . $listPage);
  exit;
} catch (mysqli_sql_exception $exception) {
  error_log('legales: ' . $exception->getMessage());
  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $legal;
  header('Location: ' . $formPage);
  exit;
}
