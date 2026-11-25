<?php
require __DIR__ . '/../includes/panel-auth.php';
require_once __DIR__ . '/../config/conexion.php';

// The email comes from the previous step (not from the URL)
$email = $_SESSION['panel_recovery_email'] ?? '';
if ($email === '') {
    header('Location: recuperar.php');
    exit;
}

$codeError     = '';
$passwordError = '';
$confirmError  = '';
$formError     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code     = trim($_POST['code'] ?? '');
    $password = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!preg_match('/^[0-9]{6}$/', $code)) { $codeError = 'El código tiene 6 números.'; }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/', $password)) { $passwordError = 'Mínimo 8: mayúscula, número y símbolo.'; }
    if ($password !== $confirm) { $confirmError = 'Las contraseñas no coinciden.'; }

    if ($codeError === '' && $passwordError === '' && $confirmError === '') {

        $sql = "SELECT u.id_usuario, u.nombre_usuario, r.id_recuperacion, r.codigo, r.intentos
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
            $formError = 'Código incorrecto o vencido. Pide uno nuevo.';

        } elseif ($row['intentos'] >= 5) {
            $stmt = mysqli_prepare($conexion, "UPDATE tblrecuperacion SET usado = 1 WHERE id_recuperacion = ?");
            mysqli_stmt_bind_param($stmt, "i", $row['id_recuperacion']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $formError = 'Demasiados intentos. Pide un código nuevo.';

        } elseif (!hash_equals($row['codigo'], $code)) {
            $stmt = mysqli_prepare($conexion, "UPDATE tblrecuperacion SET intentos = intentos + 1 WHERE id_recuperacion = ?");
            mysqli_stmt_bind_param($stmt, "i", $row['id_recuperacion']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $codeError = 'Código incorrecto.';

        } else {
            // Correct code: save the new password (MD5, same as the app) and close the code
            $hash = md5($password);
            $id   = $row['id_usuario'];

            $stmt = mysqli_prepare($conexion, "UPDATE tblusuarios SET password = ? WHERE id_usuario = ?");
            mysqli_stmt_bind_param($stmt, "si", $hash, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $sql = "UPDATE tblweb_panel_usuarios
                    SET cambiar_contrasena = 0, intentos_fallidos = 0, bloqueado_hasta = NULL
                    WHERE id_usuario = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conexion, "UPDATE tblrecuperacion SET usado = 1 WHERE id_recuperacion = ?");
            mysqli_stmt_bind_param($stmt, "i", $row['id_recuperacion']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Log it in the audit trail
            $sql = "INSERT INTO tblweb_bitacora (id_usuario, usuario, accion, detalle, ip)
                    VALUES (?, ?, 'Cambio de contraseña', 'Recuperación por correo', ?)";
            $stmt = mysqli_prepare($conexion, $sql);
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            mysqli_stmt_bind_param($stmt, "iss", $id, $row['nombre_usuario'], $ip);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            unset($_SESSION['panel_recovery_email']);
            $_SESSION['panel_notice'] = 'Contraseña actualizada. Ya puedes iniciar sesión.';
            header('Location: login.php');
            exit;
        }
    }
}

$base        = '../';
$title       = 'Nueva contraseña · Panel de administración';
$styles      = ['pages/panel.css'];
$withSite    = false;
$noindex     = true;

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="panel-reset-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="panel-reset-title">Nueva contraseña</h1>
    <p class="text-body-secondary mb-3">Escribe el código que enviamos a tu correo y crea una contraseña nueva.</p>

    <?php if ($formError !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="restablecer.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="code">Código del correo *</label>
        <input class="form-control<?= $codeError !== '' ? ' is-invalid' : '' ?>" id="code" name="code" type="text"
               inputmode="numeric" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code" aria-describedby="code-error" required>
        <div class="invalid-feedback fw-semibold" id="code-error"><?= $codeError !== '' ? $codeError : 'El código tiene 6 números.' ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="new-password">Nueva contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= $passwordError !== '' ? ' is-invalid' : '' ?>" id="new-password" name="new_password" type="password"
                 minlength="8" pattern="(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" autocomplete="new-password" aria-describedby="new-password-hint new-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="new-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="form-text" id="new-password-hint">Mínimo 8 caracteres, con mayúscula, número y símbolo (por ejemplo ! # $ %).</div>
        <div class="invalid-feedback fw-semibold" id="new-password-error"><?= $passwordError !== '' ? $passwordError : 'Mínimo 8: mayúscula, número y símbolo.' ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="confirm-password">Confirmar contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= $confirmError !== '' ? ' is-invalid' : '' ?>" id="confirm-password" name="confirm_password" type="password"
                 autocomplete="new-password" aria-describedby="confirm-password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="confirm-password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="invalid-feedback fw-semibold" id="confirm-password-error"><?= $confirmError !== '' ? $confirmError : 'Las contraseñas no coinciden.' ?></div>
      </div>

      <button class="btn btn-primary w-100" type="submit">Guardar contraseña</button>
    </form>

    <p class="small text-body-secondary mt-3 mb-0">El código vence en 10 minutos. Si venció, <a href="recuperar.php">pide uno nuevo</a>.</p>
  </section>

  <p class="d-flex align-items-center gap-2 small text-body-secondary mb-0"><img src="<?= $base ?>assets/img/icons/lock-gray.svg" width="16" height="16" alt="">Acceso exclusivo para personal autorizado del plantel.</p>

</main>

<script src="<?= $base ?>assets/js/password-toggle.js"></script>
</body>
</html>
