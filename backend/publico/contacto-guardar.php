<?php
// Guarda el mensaje del formulario de contacto: recibe, valida, inserta con folio y regresa a la página.

require_once __DIR__ . '/../../includes/site-data.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$returnTo = '../../pages/contacto.php#formulario';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $returnTo);
  exit;
}

// Asuntos válidos: los valores del ENUM de la columna asunto
$subjects = enumValues($conexion, 'tblweb_mensajes', 'asunto');

// 1. Recibir y limpiar los datos
$data = [];

foreach (['name', 'email', 'subject', 'message'] as $field) {
  $data[$field] = trim($_POST[$field] ?? '');
}
$data['notice'] = isset($_POST['notice']) ? '1' : '';

// 2. Validar
$errors = [];

if (mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 100) {
  $errors['name'] = 'Nombre: de 3 a 100 caracteres.';
}
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 120) {
  $errors['email'] = 'Correo no válido.';
}
if (!in_array($data['subject'], $subjects, true)) {
  $errors['subject'] = 'Elige un asunto.';
}
if (mb_strlen($data['message']) < 10 || mb_strlen($data['message']) > 600) {
  $errors['message'] = 'Mensaje: de 10 a 600 caracteres.';
}
if ($data['notice'] === '') {
  $errors['notice'] = 'Debes aceptar el aviso de privacidad.';
}

// Con errores: se regresa con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['contact_errors'] = $errors;
  $_SESSION['contact_old']    = $data;
  header('Location: ' . $returnTo);
  exit;
}

// 3. Guardar el mensaje con un folio de seguimiento
$sql = "INSERT INTO tblweb_mensajes (folio, nombre, correo, asunto, mensaje, aviso_aceptado) VALUES (?, ?, ?, ?, ?, 1)";

try {
  for ($attempt = 0; $attempt < 3; $attempt++) {
    $folio = 'MSG-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

    try {
      $stmt = mysqli_prepare($conexion, $sql);
      mysqli_stmt_bind_param($stmt, "sssss", $folio, $data['name'], $data['email'], $data['subject'], $data['message']);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      break;
    } catch (mysqli_sql_exception $exception) {
      // 1062 = el folio ya existe: se genera otro
      if ($exception->getCode() !== 1062 || $attempt === 2) {
        throw $exception;
      }
    }
  }
  $_SESSION['contact_folio'] = $folio;
} catch (mysqli_sql_exception $exception) {
  error_log('contacto: ' . $exception->getMessage());

  $_SESSION['contact_error'] = 'No se pudo enviar. Intenta de nuevo.';
  $_SESSION['contact_old']   = $data;
}

// 4. Volver a la página (PRG)
header('Location: ' . $returnTo);
exit;
