<?php
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../includes/panel-auth.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
$mailConfig = require __DIR__ . '/../config/correo.php';

$emailError = '';
$formError  = '';
$email      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = 'Correo no válido.';
    } else {
        // Only active panel users (editor or administrador) can recover their password
        $sql = "SELECT u.id_usuario, t.nombre
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
            // Do not send another code if one was requested less than 1 minute ago
            $sql = "SELECT id_recuperacion FROM tblrecuperacion
                    WHERE id_usuario = ? AND creado > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
            mysqli_stmt_execute($stmt);
            $recent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);

            if (!$recent) {
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                // Only the last code is valid: delete the previous ones, then save the new one
                $stmt = mysqli_prepare($conexion, "DELETE FROM tblrecuperacion WHERE id_usuario = ?");
                mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $sql  = "INSERT INTO tblrecuperacion (id_usuario, codigo, expira)
                         VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))";
                $stmt = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($stmt, "is", $user['id_usuario'], $code);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $mail = new PHPMailer(true);
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
                } catch (\PHPMailer\PHPMailer\Exception $e) {
                    error_log('No se pudo enviar el correo de recuperación: ' . $mail->ErrorInfo);
                    $formError = 'No se pudo enviar el correo. Intenta más tarde.';
                }
            }
        }

        // Same answer whether the email exists or not
        if ($formError === '') {
            $_SESSION['panel_recovery_email'] = $email;
            header('Location: restablecer.php');
            exit;
        }
    }
}

$base        = '../';
$title       = 'Recuperar contraseña · Panel de administración';
$styles      = ['pages/panel.css'];
$withSite    = false;
$noindex     = true;

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="panel-recover-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="panel-recover-title">Recuperar contraseña</h1>
    <p class="text-body-secondary mb-3">Escribe el correo de tu usuario.</p>

    <?php if ($formError !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="recuperar.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="recovery-email">Correo electrónico *</label>
        <input class="form-control<?= $emailError !== '' ? ' is-invalid' : '' ?>" id="recovery-email" name="email" type="email"
               autocomplete="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" aria-describedby="recovery-email-error" required>
        <div class="invalid-feedback fw-semibold" id="recovery-email-error"><?= $emailError ?></div>
      </div>

      <button class="btn btn-primary w-100" type="submit">Enviar código</button>
    </form>

    <p class="small text-body-secondary mt-3 mb-0">Si el correo está registrado, recibirás un código de 6 números que vence en 10 minutos. El mensaje es el mismo aunque el correo no exista. Si no tienes correo registrado, pide a un administrador que restablezca tu cuenta.</p>
    <p class="small text-body-secondary mt-3 mb-0"><a href="login.php">Volver al inicio de sesión</a></p>
  </section>

  <p class="d-flex align-items-center gap-2 small text-body-secondary mb-0"><img src="<?= $base ?>assets/img/icons/lock-gray.svg" width="16" height="16" alt="">Acceso exclusivo para personal autorizado del plantel.</p>

</main>

</body>
</html>
