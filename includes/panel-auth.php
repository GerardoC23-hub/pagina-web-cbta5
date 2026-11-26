<?php
// Panel session helpers: call panel_require_login() at the top of every private page.
session_start();

// URL of the panel folder (works from admin/ and from its subfolders)
$panelDir = str_replace('\\', '/', realpath(__DIR__ . '/../admin'));
$docRoot  = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
define('PANEL_URL', rtrim(str_replace($docRoot, '', $panelDir), '/'));

// $roles empty = any signed-in user. Example: panel_require_login(['Administrador']);
// $allowPasswordChange = true only on the "change password" screen and its save script
function panel_require_login(array $roles = [], bool $allowPasswordChange = false): void
{
    if (empty($_SESSION['panel_user'])) {
        header('Location: ' . PANEL_URL . '/login.php');
        exit;
    }
    // Close the session after 30 minutes without activity
    if (time() - ($_SESSION['panel_last_seen'] ?? 0) > 1800) {
        session_unset();
        session_destroy();
        header('Location: ' . PANEL_URL . '/login.php');
        exit;
    }
    $_SESSION['panel_last_seen'] = time();

    // Temporary password: the person must change it before using the panel
    if (!empty($_SESSION['panel_must_change_pw']) && !$allowPasswordChange) {
        header('Location: ' . PANEL_URL . '/cambiar-contrasena.php');
        exit;
    }

    // Section reserved for other roles: back to the panel start
    if ($roles && !in_array($_SESSION['panel_user']['role'], $roles, true)) {
        header('Location: ' . PANEL_URL . '/index.php');
        exit;
    }
}

// Deja un registro en la bitácora: quién hizo qué ($accion: Alta, Cambio o Baja)
function panel_log(mysqli $conexion, string $accion, string $tabla, ?int $idElemento, string $detalle): void
{
    $sql  = "INSERT INTO tblweb_bitacora (id_usuario, usuario, accion, tabla, id_elemento, detalle, ip)
             VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '';
    mysqli_stmt_bind_param($stmt, "isssiss", $_SESSION['panel_user']['id'], $_SESSION['panel_user']['username'], $accion, $tabla, $idElemento, $detalle, $ip);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
