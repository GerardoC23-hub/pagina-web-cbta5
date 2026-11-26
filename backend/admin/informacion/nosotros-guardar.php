<?php
// Guarda los textos de Nosotros: recibe el formulario, valida, actualiza la base y regresa a la pantalla.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$returnTo = '../../../admin/informacion/nosotros.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $returnTo);
  exit;
}

// Largo permitido de cada texto [mínimo, máximo]
$limits = ['mision' => [20, 600], 'vision' => [20, 600], 'valores' => [20, 600], 'historia' => [50, 2000]];
$titles = ['mision' => 'Misión', 'vision' => 'Visión', 'valores' => 'Valores', 'historia' => 'Historia'];

// 1. Recibir lo que escribió la persona
$texts  = [];
$errors = [];

foreach ($limits as $key => [$min, $max]) {
  $texts[$key] = trim($_POST['contenido'][$key] ?? '');
  $length      = mb_strlen($texts[$key]);

  // 2. Validar
  if ($length < $min || $length > $max) {
    $errors[$key] = $titles[$key] . ": de $min a $max caracteres.";
  }
}

// Con errores: se regresa a la pantalla con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $texts;
  header('Location: ' . $returnTo);
  exit;
}

// 3. Guardar: todo o nada (transacción)
try {
  mysqli_begin_transaction($conexion);

  $sql  = "UPDATE tblweb_secciones SET contenido = ? WHERE clave = ?";
  $stmt = mysqli_prepare($conexion, $sql);

  foreach ($texts as $key => $content) {
    mysqli_stmt_bind_param($stmt, "ss", $content, $key);
    mysqli_stmt_execute($stmt);
  }
  mysqli_stmt_close($stmt);

  panel_log($conexion, 'Cambio', 'tblweb_secciones', null, 'Textos de Nosotros');
  mysqli_commit($conexion);

  $_SESSION['flash'] = ['type' => 'success', 'text' => 'Textos guardados.'];
} catch (mysqli_sql_exception $exception) {
  mysqli_rollback($conexion);
  error_log('nosotros: ' . $exception->getMessage());

  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $texts;
}

// 4. Volver a la pantalla (PRG)
header('Location: ' . $returnTo);
exit;
