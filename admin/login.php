<?php
require __DIR__ . '/../includes/panel-auth.php';
require_once __DIR__ . '/../config/conexion.php';

// Already signed in: go straight to the panel
if (!empty($_SESSION['panel_user'])) {
    header('Location: index.php');
    exit;
}

// One-time message (for example after changing the password)
$notice = $_SESSION['panel_notice'] ?? '';
unset($_SESSION['panel_notice']);

$usernameError = '';
$passwordError = '';
$formError     = '';
$username      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') { $usernameError = 'Escribe tu usuario.'; }
    if ($password === '') { $passwordError = 'Escribe tu contraseña.'; }

    if ($usernameError === '' && $passwordError === '') {

        // Only users listed in tblweb_panel_usuarios can enter the panel
        $sql = "SELECT u.id_usuario, u.nombre_usuario, u.password, u.perfil,
                       t.nombre, t.a_paterno,
                       p.estado, p.cambiar_contrasena, p.intentos_fallidos, p.bloqueado_hasta
                FROM tblusuarios u
                JOIN tblweb_panel_usuarios p ON p.id_usuario = u.id_usuario
                JOIN tbltrabajador t ON t.id_trabajador = u.id_trabajador
                WHERE u.nombre_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$user) {
            $formError = 'Usuario o contraseña incorrectos.';

        } elseif ($user['bloqueado_hasta'] !== null && strtotime($user['bloqueado_hasta']) > time()) {
            $formError = 'Acceso bloqueado 15 minutos.';

        } elseif (!hash_equals($user['password'], md5($password))) {
            // Wrong password: count the attempt, lock the account after 5
            $attempts = $user['intentos_fallidos'] + 1;
            if ($attempts >= 5) {
                $sql = "UPDATE tblweb_panel_usuarios
                        SET intentos_fallidos = 0, bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                        WHERE id_usuario = ?";
                $formError = 'Acceso bloqueado 15 minutos.';
            } else {
                $sql = "UPDATE tblweb_panel_usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id_usuario = ?";
                $formError = 'Usuario o contraseña incorrectos.';
            }
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

        } elseif ($user['estado'] !== 'Activo' || !in_array($user['perfil'], ['administrador', 'editor'], true)) {
            $formError = 'Sin acceso al panel. Contacta al administrador.';

        } else {
            // Correct: reset the counter and save the last access
            $sql = "UPDATE tblweb_panel_usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id_usuario = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $sql = "UPDATE tblusuarios SET ultimo_acceso = NOW() WHERE id_usuario = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user['id_usuario']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            session_regenerate_id(true);
            $_SESSION['panel_user'] = [
                'id'       => (int) $user['id_usuario'],
                'username' => $user['nombre_usuario'],
                'name'     => $user['nombre'] . ' ' . $user['a_paterno'],
                'role'     => $user['perfil'] === 'administrador' ? 'Administrador' : 'Editor',
            ];
            $_SESSION['panel_last_seen']      = time();
            $_SESSION['panel_must_change_pw'] = (int) $user['cambiar_contrasena'];
            header('Location: index.php');
            exit;
        }
    }
}

$base        = '../';
$title       = 'Panel de administración · CBTa No. 5';
$styles      = ['pages/panel.css'];
$withSite    = false;   // no public header/footer on this page
$noindex     = true;    // private area: keep it out of search engines

require __DIR__ . '/../includes/head.php';
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="panel-login-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="panel-login-title">Iniciar sesión</h1>
    <p class="text-body-secondary mb-3">Escribe tu usuario y contraseña.</p>

    <?php if ($notice !== ''): ?>
    <div class="alert alert-success" role="alert"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($formError !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="login.php" method="post" novalidate>

      <div class="mb-3">
        <label class="form-label" for="username">Usuario *</label>
        <input class="form-control<?= $usernameError !== '' ? ' is-invalid' : '' ?>" id="username" name="username" type="text"
               autocomplete="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" aria-describedby="username-error" required>
        <div class="invalid-feedback fw-semibold" id="username-error"><?= $usernameError ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="password">Contraseña *</label>
        <div class="d-flex align-items-center gap-3">
          <input class="form-control<?= $passwordError !== '' ? ' is-invalid' : '' ?>" id="password" name="password" type="password"
                 autocomplete="current-password" aria-describedby="password-error" required>
          <button class="btn btn-link link-dark fw-bold px-1" type="button" data-toggle-password="password" aria-pressed="false">Mostrar</button>
        </div>
        <div class="invalid-feedback d-block fw-semibold" id="password-error"><?= $passwordError ?></div>
      </div>

      <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" type="submit"><img src="<?= $base ?>assets/img/icons/lock-white.svg" width="20" height="20" alt="">Entrar al panel</button>
    </form>

    <p class="small text-body-secondary mt-3 mb-0"><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>

    <p class="small text-body-secondary mt-3 mb-0">Después de 5 intentos fallidos el acceso se bloquea 15 minutos. La sesión se cierra tras 30 minutos sin actividad.</p>
  </section>

  <p class="d-flex align-items-center gap-2 small text-body-secondary mb-0"><img src="<?= $base ?>assets/img/icons/lock-gray.svg" width="16" height="16" alt="">Acceso exclusivo para personal autorizado del plantel.</p>

</main>

<script src="<?= $base ?>assets/js/alert-dismiss.js"></script>
<script src="<?= $base ?>assets/js/password-toggle.js"></script>
</body>
</html>
