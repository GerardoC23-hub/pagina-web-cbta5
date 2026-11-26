<?php
// Guarda una noticia o aviso: recibe el formulario, valida, sube la imagen, guarda en la base y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../../panel/contenido/noticias.php');
  exit;
}

$id        = $_POST['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);
$formPage  = '../../../panel/contenido/noticias-formulario.php' . ($isEditing ? '?id=' . (int) $id : '');

// 1. Al editar, buscar la imagen que tiene ahora
$currentImage = '';
if ($isEditing) {
  $stmt = mysqli_prepare($conexion, "SELECT imagen FROM tblweb_publicaciones WHERE id_publicacion = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: ../../../panel/contenido/noticias.php');
    exit;
  }
  $currentImage = (string) $found['imagen'];
}

// 2. Recibir lo que escribió la persona
$item = [];
foreach (['titulo', 'resumen', 'contenido', 'categoria', 'imagen_credito', 'fecha_publicacion', 'estado'] as $field) {
  $item[$field] = trim($_POST[$field] ?? '');
}

$removeImage = isset($_POST['quitar_imagen']);
$file        = $_FILES['imagen'] ?? null;
$hasFile     = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;

// 3. Validar
$errors = [];

if (mb_strlen($item['titulo']) < 5 || mb_strlen($item['titulo']) > 150) {
  $errors['titulo'] = 'Título: de 5 a 150 caracteres.';
}
if (mb_strlen($item['resumen']) < 10 || mb_strlen($item['resumen']) > 255) {
  $errors['resumen'] = 'Resumen: de 10 a 255 caracteres.';
}
if (mb_strlen($item['contenido']) < 30 || mb_strlen($item['contenido']) > 5000) {
  $errors['contenido'] = 'Contenido: de 30 a 5000 caracteres.';
}
if (!in_array($item['categoria'], ['Noticia', 'Aviso', 'Evento', 'Logro'], true)) {
  $errors['categoria'] = 'Elige una categoría.';
}
if (!in_array($item['estado'], ['Borrador', 'Publicada'], true)) {
  $errors['estado'] = 'Elige un estado.';
}
$date = DateTime::createFromFormat('Y-m-d', $item['fecha_publicacion']);
if (!$date || $date->format('Y-m-d') !== $item['fecha_publicacion']) {
  $errors['fecha_publicacion'] = 'Fecha no válida.';
}
if ($hasFile && ($message = imageError($file)) !== '') {
  $errors['imagen'] = $message;
}

// ¿Quedará con imagen? Solo si sube una o conserva la que tenía
$willHaveImage = $hasFile || ($currentImage !== '' && !$removeImage);

if ($willHaveImage && $item['imagen_credito'] === '') {
  $errors['imagen_credito'] = 'Escribe el autor de la imagen.';
} elseif (mb_strlen($item['imagen_credito']) > 120) {
  $errors['imagen_credito'] = 'Crédito: máximo 120 caracteres.';
}

// Con errores: se regresa al formulario con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $item;
  header('Location: ' . $formPage);
  exit;
}

// 4. Guardar la imagen nueva (si hay) y luego el registro
$newImage = '';
if ($hasFile) {
  $newImage = saveImage($file, 'publicaciones', 'noticia');
  if ($newImage === '') {
    $_SESSION['form_error'] = 'No se pudo guardar la imagen. Intenta de nuevo.';
    $_SESSION['form_old']   = $item;
    header('Location: ' . $formPage);
    exit;
  }
}

$image = $newImage !== '' ? $newImage : ($removeImage ? '' : $currentImage);

// Los campos opcionales vacíos se guardan como NULL
$imageDb = $image === '' ? null : $image;
$credit  = $image === '' ? null : $item['imagen_credito'];

try {
  if ($isEditing) {
    $sql  = "UPDATE tblweb_publicaciones SET titulo = ?, resumen = ?, contenido = ?, categoria = ?,
                    imagen = ?, imagen_credito = ?, fecha_publicacion = ?, estado = ?
             WHERE id_publicacion = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $item['titulo'], $item['resumen'], $item['contenido'], $item['categoria'],
      $imageDb, $credit, $item['fecha_publicacion'], $item['estado'], $id);
  } else {
    $sql  = "INSERT INTO tblweb_publicaciones (titulo, resumen, contenido, categoria, imagen, imagen_credito, fecha_publicacion, estado, id_usuario)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $item['titulo'], $item['resumen'], $item['contenido'], $item['categoria'],
      $imageDb, $credit, $item['fecha_publicacion'], $item['estado'], $_SESSION['panel_user']['id']);
  }
  mysqli_stmt_execute($stmt);
  $savedId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
  mysqli_stmt_close($stmt);

  panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_publicaciones', $savedId, $item['titulo']);

  // La imagen anterior se borra para no dejar archivos sin usar
  if ($currentImage !== '' && $currentImage !== $image) {
    deleteImage($currentImage);
  }

  $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : 'Noticia agregada.'];
  header('Location: ../../../panel/contenido/noticias.php');
  exit;
} catch (mysqli_sql_exception $exception) {
  error_log('noticias: ' . $exception->getMessage());
  deleteImage($newImage);

  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $item;
  header('Location: ' . $formPage);
  exit;
}
