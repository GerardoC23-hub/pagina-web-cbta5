<?php
// Datos de las pantallas de recuperar contraseña (correo, código y contraseña nueva).
// Deja: $errors, $error, $email. Cada pantalla pide el paso anterior; si no lo hizo, la regresa.
// Pasos: 1 correo  →  2 código  →  3 contraseña nueva

require_once __DIR__ . '/../../../includes/site-data.php';

// Paso que pide la pantalla (lo define la vista antes de incluir este archivo)
$step = $step ?? 1;

// 2. El código solo se pide si ya se escribió el correo
if ($step >= 2 && empty($_SESSION['panel_recovery_email'])) {
  header('Location: recuperar.php');
  exit;
}

// 3. La contraseña nueva solo se pide si el código ya se verificó (y no ha vencido)
if ($step >= 3) {
  $verified = $_SESSION['panel_recovery_verified'] ?? null;

  if (!$verified || $verified['until'] < time()) {
    unset($_SESSION['panel_recovery_verified']);
    header('Location: verificar-codigo.php');
    exit;
  }
}

// Errores y datos del intento anterior (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$email  = $_SESSION['form_old']['email'] ?? '';
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);
