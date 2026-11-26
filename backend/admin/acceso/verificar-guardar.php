<?php
// Paso 2: recibe el código del correo y lo revisa. Si es correcto, deja pasar a la contraseña nueva.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

$returnTo = '../../../admin/verificar-codigo.php';
$nextPage = '../../../admin/restablecer.php';
$email    = $_SESSION['panel_recovery_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $email === '') {
  header('Location: ../../../admin/recuperar.php');
  exit;
}

// Regresa al formulario con un mensaje
function backToCode(string $returnTo, string $message): void
{
  $_SESSION['form_errors'] = ['code' => $message];
  header('Location: ' . $returnTo);
  exit;
}

// 1. Recibir y validar
$code = trim($_POST['code'] ?? '');

if (!preg_match('/^[0-9]{6}$/', $code)) {
  backToCode($returnTo, 'El código tiene 6 números.');
}

// 2. Buscar el último código vigente de ese correo
$sql  = "SELECT r.id_recuperacion, r.id_usuario, r.codigo, r.intentos
         FROM tblusuarios u
         JOIN tbltrabajador t ON t.id_trabajador = u.id_trabajador
         JOIN tblweb_panel_usuarios p ON p.id_usuario = u.id_usuario
         JOIN tblrecuperacion r ON r.id_usuario = u.id_usuario
         WHERE t.email = ? AND p.estado = 'Activo' AND r.usado = 0 AND r.expira > NOW()
         ORDER BY r.id_recuperacion DESC LIMIT 1";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$row) {
  backToCode($returnTo, 'Código incorrecto o vencido.');
}

// 3. Con 5 intentos fallidos el código se cierra
if ($row['intentos'] >= 5) {
  $stmt = mysqli_prepare($conexion, "UPDATE tblrecuperacion SET usado = 1 WHERE id_recuperacion = ?");
  mysqli_stmt_bind_param($stmt, "i", $row['id_recuperacion']);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  backToCode($returnTo, 'Demasiados intentos. Pide un código nuevo.');
}

// 4. Comparar el código
if (!hash_equals($row['codigo'], $code)) {
  $stmt = mysqli_prepare($conexion, "UPDATE tblrecuperacion SET intentos = intentos + 1 WHERE id_recuperacion = ?");
  mysqli_stmt_bind_param($stmt, "i", $row['id_recuperacion']);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  backToCode($returnTo, 'Código incorrecto.');
}

// 5. Código correcto: se puede pasar a escribir la contraseña nueva (10 minutos para hacerlo)
unset($_SESSION['form_errors']);
$_SESSION['panel_recovery_verified'] = [
  'id_recuperacion' => (int) $row['id_recuperacion'],
  'id_usuario'      => (int) $row['id_usuario'],
  'until'           => time() + 600,
];
header('Location: ' . $nextPage);
exit;
