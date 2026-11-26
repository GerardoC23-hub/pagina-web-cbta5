<?php
// Guarda una pregunta frecuente: recibe el formulario, valida, guarda en la base y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-validation.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../../admin/contenido/preguntas.php');
  exit;
}

$id        = $_POST['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);
$formPage  = '../../../admin/contenido/preguntas-formulario.php' . ($isEditing ? '?id=' . (int) $id : '');

// 1. Recibir lo que escribió la persona
$faq = [];
foreach (['pregunta', 'respuesta', 'orden'] as $field) {
  $faq[$field] = trim($_POST[$field] ?? '');
}
$faq['visible'] = isset($_POST['visible']) ? 1 : 0;

// 2. Validar
$errors = [];

if (($message = textError($faq['pregunta'], 'la pregunta', 10, 200)) !== '') {
  $errors['pregunta'] = $message;
}
if (($message = textError($faq['respuesta'], 'la respuesta', 10, 1000)) !== '') {
  $errors['respuesta'] = $message;
}
if (($message = orderError($faq['orden'])) !== '') {
  $errors['orden'] = $message;
}

// Con errores: se regresa al formulario con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $faq;
  header('Location: ' . $formPage);
  exit;
}

// 3. Guardar
$order = (int) $faq['orden'];

try {
  if ($isEditing) {
    $stmt = mysqli_prepare($conexion, "UPDATE tblweb_preguntas SET pregunta = ?, respuesta = ?, orden = ?, visible = ? WHERE id_pregunta = ?");
    mysqli_stmt_bind_param($stmt, "ssiii", $faq['pregunta'], $faq['respuesta'], $order, $faq['visible'], $id);
  } else {
    $stmt = mysqli_prepare($conexion, "INSERT INTO tblweb_preguntas (pregunta, respuesta, orden, visible) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssii", $faq['pregunta'], $faq['respuesta'], $order, $faq['visible']);
  }
  mysqli_stmt_execute($stmt);
  $savedId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
  mysqli_stmt_close($stmt);

  panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_preguntas', $savedId, $faq['pregunta']);
  $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : 'Pregunta agregada.'];
  header('Location: ../../../admin/contenido/preguntas.php');
  exit;
} catch (mysqli_sql_exception $exception) {
  error_log('preguntas: ' . $exception->getMessage());
  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $faq;
  header('Location: ' . $formPage);
  exit;
}
