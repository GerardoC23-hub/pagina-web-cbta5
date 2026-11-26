<?php
// Paso 3: recibe la contraseña nueva (solo si el código ya se verificó) y la guarda.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

$returnTo = '../../../admin/restablecer.php';
$verified = $_SESSION['panel_recovery_verified'] ?? null;

// Sin código verificado no se cambia nada
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$verified || $verified['until'] < time()) {
  unset($_SESSION['panel_recovery_verified']);
  header('Location: ../../../admin/verificar-codigo.php');
  exit;
}

// 1. Recibir
$password = $_POST['new_password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

// 2. Validar
$errors = [];

if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/', $password)) {
  $errors['new_password'] = 'Mínimo 8: mayúscula, número y símbolo.';
}
if ($password !== $confirm) {
  $errors['confirm_password'] = 'Las contraseñas no coinciden.';
}
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  header('Location: ' . $returnTo);
  exit;
}

// 3. El código debe seguir vigente y sin usar
$sql  = "SELECT r.id_recuperacion, u.id_usuario, u.nombre_usuario
         FROM tblrecuperacion r
         JOIN tblusuarios u ON u.id_usuario = r.id_usuario
         WHERE r.id_recuperacion = ? AND r.id_usuario = ? AND r.usado = 0 AND r.expira > NOW()";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $verified['id_recuperacion'], $verified['id_usuario']);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$row) {
  unset($_SESSION['panel_recovery_verified']);
  $_SESSION['form_error'] = 'El código venció. Pide uno nuevo.';
  header('Location: ../../../admin/recuperar.php');
  exit;
}

// 4. Guardar la contraseña (MD5, igual que la app), cerrar el código y anotar en la bitácora
$id   = (int) $row['id_usuario'];
$hash = md5($password);

mysqli_begin_transaction($conexion);

try {
  $stmt = mysqli_prepare($conexion, "UPDATE tblusuarios SET password = ? WHERE id_usuario = ?");
  mysqli_stmt_bind_param($stmt, "si", $hash, $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  $stmt = mysqli_prepare($conexion, "UPDATE tblweb_panel_usuarios SET cambiar_contrasena = 0, intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id_usuario = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  $stmt = mysqli_prepare($conexion, "UPDATE tblrecuperacion SET usado = 1 WHERE id_recuperacion = ?");
  mysqli_stmt_bind_param($stmt, "i", $row['id_recuperacion']);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  $ip   = $_SERVER['REMOTE_ADDR'] ?? '';
  $stmt = mysqli_prepare($conexion, "INSERT INTO tblweb_bitacora (id_usuario, usuario, accion, detalle, ip) VALUES (?, ?, 'Cambio de contraseña', 'Recuperación por correo', ?)");
  mysqli_stmt_bind_param($stmt, "iss", $id, $row['nombre_usuario'], $ip);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  mysqli_commit($conexion);
} catch (mysqli_sql_exception $exception) {
  mysqli_rollback($conexion);
  error_log('restablecer: ' . $exception->getMessage());
  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  header('Location: ' . $returnTo);
  exit;
}

// 5. Listo: se borra el avance y se regresa al inicio de sesión
unset($_SESSION['panel_recovery_email'], $_SESSION['panel_recovery_verified'], $_SESSION['form_errors']);
$_SESSION['panel_notice'] = 'Contraseña actualizada. Ya puedes iniciar sesión.';
header('Location: ../../../admin/login.php');
exit;
