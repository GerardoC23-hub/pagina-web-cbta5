<?php
// Guarda una persona del directorio: recibe el formulario, valida, sube la foto, guarda en la base y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../includes/site-data.php';

panel_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../../admin/informacion/personal.php');
  exit;
}

$id        = $_POST['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);
$formPage  = '../../../admin/informacion/personal-formulario.php' . ($isEditing ? '?id=' . (int) $id : '');

// 1. Al editar, buscar la foto que tiene ahora
$currentPhoto = '';
if ($isEditing) {
  $stmt = mysqli_prepare($conexion, "SELECT foto FROM tblweb_personal WHERE id_personal = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: ../../../admin/informacion/personal.php');
    exit;
  }
  $currentPhoto = (string) $found['foto'];
}

// 2. Recibir lo que escribió la persona
$person = [];
foreach (['nombre', 'cargo', 'tipo', 'correo', 'telefono', 'foto_credito'] as $field) {
  $person[$field] = trim($_POST[$field] ?? '');
}
$person['orden']      = is_numeric($_POST['orden'] ?? '') ? (int) $_POST['orden'] : 0;
$person['autorizado'] = isset($_POST['autorizado']) ? 1 : 0;

$removePhoto = isset($_POST['quitar_foto']);
$file        = $_FILES['foto'] ?? null;
$hasFile     = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;

// 3. Validar
$errors = [];

if (mb_strlen($person['nombre']) < 3 || mb_strlen($person['nombre']) > 120) {
  $errors['nombre'] = 'Nombre: de 3 a 120 caracteres.';
}
if (mb_strlen($person['cargo']) < 3 || mb_strlen($person['cargo']) > 100) {
  $errors['cargo'] = 'Cargo: de 3 a 100 caracteres.';
}
if (!in_array($person['tipo'], enumValues($conexion, 'tblweb_personal', 'tipo'), true)) {
  $errors['tipo'] = 'Elige un área.';
}
if ($person['correo'] !== '' && (!filter_var($person['correo'], FILTER_VALIDATE_EMAIL) || mb_strlen($person['correo']) > 120)) {
  $errors['correo'] = 'Correo no válido.';
}
if ($person['telefono'] !== '' && !preg_match('/^[0-9\s()+\-]{8,20}$/', $person['telefono'])) {
  $errors['telefono'] = 'Teléfono no válido.';
}
if ($person['orden'] < 0 || $person['orden'] > 999) {
  $errors['orden'] = 'Orden: de 0 a 999.';
}
if ($hasFile && ($message = imageError($file)) !== '') {
  $errors['foto'] = $message;
}

// ¿Quedará con foto? Solo si sube una o conserva la que tenía
$willHavePhoto = $hasFile || ($currentPhoto !== '' && !$removePhoto);

if ($willHavePhoto && $person['foto_credito'] === '') {
  $errors['foto_credito'] = 'Escribe el autor de la foto.';
} elseif (mb_strlen($person['foto_credito']) > 120) {
  $errors['foto_credito'] = 'Crédito: máximo 120 caracteres.';
}

// Con errores: se regresa al formulario con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $person;
  header('Location: ' . $formPage);
  exit;
}

// 4. Guardar la foto nueva (si hay) y luego el registro
$newPhoto = '';
if ($hasFile) {
  $newPhoto = saveImage($file, 'personal', 'personal');
  if ($newPhoto === '') {
    $_SESSION['form_error'] = 'No se pudo guardar la foto. Intenta de nuevo.';
    $_SESSION['form_old']   = $person;
    header('Location: ' . $formPage);
    exit;
  }
}

$photo = $newPhoto !== '' ? $newPhoto : ($removePhoto ? '' : $currentPhoto);

// Los campos opcionales vacíos se guardan como NULL
$optional = fn ($value) => $value === '' ? null : $value;
$correo   = $optional($person['correo']);
$telefono = $optional($person['telefono']);
$photoDb  = $optional($photo);
$credit   = $photo === '' ? null : $person['foto_credito'];

try {
  if ($isEditing) {
    $sql  = "UPDATE tblweb_personal SET nombre = ?, cargo = ?, tipo = ?, correo = ?, telefono = ?,
                    foto = ?, foto_credito = ?, orden = ?, autorizado = ?
             WHERE id_personal = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssiii", $person['nombre'], $person['cargo'], $person['tipo'], $correo, $telefono,
      $photoDb, $credit, $person['orden'], $person['autorizado'], $id);
  } else {
    $sql  = "INSERT INTO tblweb_personal (nombre, cargo, tipo, correo, telefono, foto, foto_credito, orden, autorizado)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssii", $person['nombre'], $person['cargo'], $person['tipo'], $correo, $telefono,
      $photoDb, $credit, $person['orden'], $person['autorizado']);
  }
  mysqli_stmt_execute($stmt);
  $savedId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
  mysqli_stmt_close($stmt);

  panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_personal', $savedId, $person['nombre']);

  // La foto anterior se borra para no dejar archivos sin usar
  if ($currentPhoto !== '' && $currentPhoto !== $photo) {
    deleteImage($currentPhoto);
  }

  $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : 'Persona agregada.'];
  header('Location: ../../../admin/informacion/personal.php');
  exit;
} catch (mysqli_sql_exception $exception) {
  error_log('personal: ' . $exception->getMessage());
  deleteImage($newPhoto);

  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $person;
  header('Location: ' . $formPage);
  exit;
}
