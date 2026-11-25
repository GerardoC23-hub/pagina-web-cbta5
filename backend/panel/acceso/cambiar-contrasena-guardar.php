<?php
// Cambio obligatorio de contraseña (primer ingreso): valida, guarda y entra al panel.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login([], true);

$returnTo = '../../../panel/cambiar-contrasena.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $returnTo);
  exit;
}

$userId = $_SESSION['panel_user']['id'];

// 1. Recibir los datos
$password = $_POST['new_password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

// 2. Validar
$errors = [];

if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/', $password)) {
  $errors['password'] = 'Mínimo 8: mayúscula, número y símbolo.';
}
if ($password !== $confirm) {
  $errors['confirm'] = 'Las contraseñas no coinciden.';
}

if (!$errors) {
  // La nueva contraseña no puede ser la temporal que se está usando
  $stmt = mysqli_prepare($conexion, "SELECT password FROM tblusuarios WHERE id_usuario = ?");
  mysqli_stmt_bind_param($stmt, "i", $userId);
  mysqli_stmt_execute($stmt);
  $current = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($current && $current['password'] === md5($password)) {
    $errors['password'] = 'Usa una contraseña distinta a la actual.';
  }
}

if ($errors) {
  $_SESSION['form_errors'] = $errors;
  header('Location: ' . $returnTo);
  exit;
}

// 3. Guardar: todo o nada (transacción)
try {
  mysqli_begin_transaction($conexion);

  $hash = md5($password);

  $stmt = mysqli_prepare($conexion, "UPDATE tblusuarios SET password = ? WHERE id_usuario = ?");
  mysqli_stmt_bind_param($stmt, "si", $hash, $userId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  $stmt = mysqli_prepare($conexion, "UPDATE tblweb_panel_usuarios SET cambiar_contrasena = 0 WHERE id_usuario = ?");
  mysqli_stmt_bind_param($stmt, "i", $userId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  panel_log($conexion, 'Cambio de contraseña', 'tblusuarios', $userId, 'Cambio obligatorio en el primer ingreso');
  mysqli_commit($conexion);
} catch (mysqli_sql_exception $exception) {
  mysqli_rollback($conexion);
  error_log('cambiar-contrasena: ' . $exception->getMessage());

  $_SESSION['form_errors'] = ['form' => 'No se pudo guardar. Intenta de nuevo.'];
  header('Location: ' . $returnTo);
  exit;
}

// 4. Listo: ya puede usar el panel
$_SESSION['panel_must_change_pw'] = 0;
$_SESSION['flash'] = ['type' => 'success', 'text' => 'Contraseña actualizada.'];

header('Location: ../../../panel/index.php');
exit;
