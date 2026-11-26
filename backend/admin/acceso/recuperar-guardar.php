<?php
// Paso 1: recibe el correo, crea un código de 6 números y lo envía por correo.

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../../PHPMailer/src/SMTP.php';

$returnTo = '../../../admin/recuperar.php';
$nextPage = '../../../admin/verificar-codigo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $returnTo);
  exit;
}

// 1. Recibir
$email = trim($_POST['email'] ?? '');

// 2. Validar
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['form_errors'] = ['email' => 'Correo no válido.'];
  $_SESSION['form_old']    = ['email' => $email];
  header('Location: ' . $returnTo);
  exit;
}

// 3. Buscar al usuario: solo usuarios activos del panel (editor o administrador)
$sql  = "SELECT u.id_usuario, t.nombre
         FROM tblusuarios u
         JOIN tbltrabajador t ON t.id_trabajador = u.id_trabajador
         JOIN tblweb_panel_usuarios p ON p.id_usuario = u.id_usuario
         WHERE t.email = ? AND p.estado = 'Activo' AND u.perfil IN ('administrador', 'editor')";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if ($user) {
  // No se manda otro código si ya se pidió uno hace menos de 1 minuto
  $sql  = "SELECT id_recuperacion FROM tblrecuperacion WHERE id_usuario = ? AND creado > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
  mysqli_stmt_execute($stmt);
  $recent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$recent) {
    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // 4. Guardar: solo vale el último código, se borran los anteriores
    $stmt = mysqli_prepare($conexion, "DELETE FROM tblrecuperacion WHERE id_usuario = ?");
    mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conexion, "INSERT INTO tblrecuperacion (id_usuario, codigo, expira) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
    mysqli_stmt_bind_param($stmt, "is", $user['id_usuario'], $code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 5. Enviar el correo
    $mailConfig = require __DIR__ . '/../../../config/correo.php';
    $mail       = new PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->Host       = $mailConfig['host'];
      $mail->SMTPAuth   = true;
      $mail->Username   = $mailConfig['usuario'];
      $mail->Password   = $mailConfig['clave'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;
      $mail->CharSet    = 'UTF-8';
      $mail->setFrom($mailConfig['usuario'], 'CBTa 5');
      $mail->addAddress($email);
      $mail->Subject = 'Código para recuperar tu contraseña del panel';
      $mail->Body    = "Hola " . $user['nombre'] . ",\n\n"
                     . "Tu código de recuperación es: " . $code . "\n\n"
                     . "Vence en 10 minutos. Si no lo solicitaste, ignora este correo.";
      $mail->send();
    } catch (\PHPMailer\PHPMailer\Exception $exception) {
      error_log('No se pudo enviar el correo de recuperación: ' . $mail->ErrorInfo);
      $_SESSION['form_error'] = 'No se pudo enviar el correo. Intenta más tarde.';
      $_SESSION['form_old']   = ['email' => $email];
      header('Location: ' . $returnTo);
      exit;
    }
  }
}

// 6. Siguiente paso. La respuesta es la misma exista o no el correo, para no revelar quién tiene cuenta
unset($_SESSION['panel_recovery_verified'], $_SESSION['form_errors'], $_SESSION['form_old']);
$_SESSION['panel_recovery_email'] = $email;
header('Location: ' . $nextPage);
exit;
