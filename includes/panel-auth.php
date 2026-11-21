<?php
// Panel session helpers: call panel_require_login() at the top of every private page.
session_start();

// $roles empty = any signed-in user. Example: panel_require_login(['Administrador']);
function panel_require_login(array $roles = []): void
{
    if (empty($_SESSION['panel_user'])) {
        header('Location: login.php');
        exit;
    }
    // Close the session after 30 minutes without activity
    if (time() - ($_SESSION['panel_last_seen'] ?? 0) > 1800) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
    $_SESSION['panel_last_seen'] = time();

    // Section reserved for other roles: back to the panel start
    if ($roles && !in_array($_SESSION['panel_user']['role'], $roles, true)) {
        header('Location: index.php');
        exit;
    }
}
