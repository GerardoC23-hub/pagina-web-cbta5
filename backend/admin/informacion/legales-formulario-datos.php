<?php
// Datos del formulario de un texto legal: ?clave dice cuál (privacidad, términos o accesibilidad).
// Deja: $legal, $errors, $error

require_once __DIR__ . '/../../../config/conexion.php';

$key = $_GET['clave'] ?? '';

// 1. Leer el texto de la base (solo los del grupo Legal)
$stmt = mysqli_prepare($conexion, "SELECT clave, titulo, contenido, actualizado_en FROM tblweb_secciones WHERE clave = ? AND grupo = 'Legal'");
mysqli_stmt_bind_param($stmt, "s", $key);
mysqli_stmt_execute($stmt);
$legal = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$legal) {
  $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ese texto no existe.'];
  header('Location: legales.php');
  exit;
}
$legal = array_map(fn ($value) => $value ?? '', $legal);

// 2. Si el guardado falló, recuperar lo que escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $field => $value) {
  if (array_key_exists($field, $legal) && $field !== 'clave') {
    $legal[$field] = $value;
  }
}
