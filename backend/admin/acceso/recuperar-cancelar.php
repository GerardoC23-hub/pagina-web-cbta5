<?php
// Cancela la recuperación de contraseña: borra el avance guardado y regresa al inicio de sesión.

require __DIR__ . '/../../../includes/panel-auth.php';

unset($_SESSION['panel_recovery_email'], $_SESSION['panel_recovery_verified']);

header('Location: ../../../admin/login.php');
exit;
