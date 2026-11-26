<?php
// Guarda un slide del carrusel (alta o cambio): recibe, valida, sube la imagen, guarda y regresa.
// En la base se guarda solo la ruta de la imagen, relativa a la raíz del sitio:
// uploads/carrusel/titulo-del-slide-a1b2c3.jpg

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../includes/site-data.php';

panel_require_login();

$limits   = require __DIR__ . '/carrusel-limites.php';
$siteRoot = realpath(__DIR__ . '/../../..');
$folder   = $limits['folder'];

// Con ?id se edita un slide; sin id se agrega uno nuevo
$id        = $_POST['id'] ?? null;
$isEditing = $id !== null && is_numeric($id);

$returnTo = '../../../admin/contenido/carrusel/formulario.php' . ($isEditing ? '?id=' . (int) $id : '');
$listPage = '../../../admin/contenido/carrusel/index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $listPage);
  exit;
}

// 1. Slide actual (si se edita) y datos recibidos
$current = ['imagen' => ''];

if ($isEditing) {
  $stmt = mysqli_prepare($conexion, "SELECT imagen FROM tblweb_carrusel WHERE id_carrusel = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: ' . $listPage);
    exit;
  }
  $current['imagen'] = (string) $found['imagen'];
}

$slide = [];

foreach (['etiqueta', 'titulo', 'descripcion', 'imagen_alt', 'imagen_credito', 'fecha_inicio', 'fecha_fin'] as $field) {
  $slide[$field] = trim($_POST[$field] ?? '');
}
$slide['orden']   = is_numeric($_POST['orden'] ?? '') ? (int) $_POST['orden'] : 0;
$slide['visible'] = isset($_POST['visible']) ? 1 : 0;

// 2. Validar los textos
$errors = [];

if (mb_strlen($slide['titulo']) < 3 || mb_strlen($slide['titulo']) > 150) {
  $errors['titulo'] = 'Título: de 3 a 150 caracteres.';
}
if (mb_strlen($slide['imagen_alt']) < 8) {
  $errors['imagen_alt'] = 'Describe la imagen (mínimo 8 caracteres).';
}
if ($slide['imagen_credito'] === '') {
  $errors['imagen_credito'] = 'Escribe el autor de la foto.';
}
if ($slide['fecha_inicio'] !== '' && $slide['fecha_fin'] !== '' && $slide['fecha_fin'] < $slide['fecha_inicio']) {
  $errors['fecha_fin'] = 'La fecha final es anterior a la inicial.';
}

// 3. Validar la imagen (obligatoria al agregar; al editar solo si se elige otra)
$file    = $_FILES['imagen'] ?? null;
$hasFile = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;
$ext     = '';

if (!$hasFile) {
  if (!$isEditing) {
    $errors['imagen'] = 'Selecciona una imagen.';
  }
} elseif (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $file['size'] > $limits['maxSize']) {
  $errors['imagen'] = 'La imagen pesa más de 1 MB.';
} elseif ($file['error'] !== UPLOAD_ERR_OK) {
  $errors['imagen'] = 'No se pudo subir el archivo.';
} else {
  // Extensión, tipo MIME real y que de verdad sea una imagen
  $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime  = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);

  if (!in_array($ext, $limits['extensions'], true) || !in_array($mime, $limits['mimeTypes'], true) || @getimagesize($file['tmp_name']) === false) {
    $errors['imagen'] = 'Formato no válido (JPG, PNG o WebP).';
  }
}

// 4. Límite de slides visibles
$error = '';

if ($slide['visible']) {
  $stmt      = mysqli_prepare($conexion, "SELECT COUNT(*) FROM tblweb_carrusel WHERE visible = 1 AND id_carrusel <> ?");
  $currentId = $isEditing ? (int) $id : 0;
  mysqli_stmt_bind_param($stmt, "i", $currentId);
  mysqli_stmt_execute($stmt);
  $visibleCount = (int) mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];
  mysqli_stmt_close($stmt);

  if ($visibleCount >= $limits['maxVisible']) {
    $error = 'Ya hay ' . $limits['maxVisible'] . ' slides visibles. Oculta uno primero.';
  }
}

// Con errores: se regresa al formulario con lo que escribió y los mensajes
if ($errors || $error !== '') {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_error']  = $error;
  $_SESSION['form_old']    = $slide;
  header('Location: ' . $returnTo);
  exit;
}

// 5. Guardar la imagen nueva (si se eligió) en uploads/carrusel/
$newPath = '';

if ($hasFile) {
  $newPath = $folder . slugify($slide['titulo'], 'slide') . '-' . bin2hex(random_bytes(3)) . '.' . $ext;

  if (!is_dir($siteRoot . '/' . $folder)) {
    mkdir($siteRoot . '/' . $folder, 0755, true);
  }
  if (!move_uploaded_file($file['tmp_name'], $siteRoot . '/' . $newPath)) {
    $_SESSION['form_error'] = 'No se pudo guardar la imagen.';
    $_SESSION['form_old']   = $slide;
    header('Location: ' . $returnTo);
    exit;
  }
}

// 6. Guardar en la base (los campos opcionales vacíos van como NULL)
$imagePath    = $newPath !== '' ? $newPath : $current['imagen'];
$optional     = fn ($value) => $value === '' ? null : $value;
$etiqueta     = $optional($slide['etiqueta']);
$descripcion  = $optional($slide['descripcion']);
$fechaInicio  = $optional($slide['fecha_inicio']);
$fechaFin     = $optional($slide['fecha_fin']);

try {
  if ($isEditing) {
    $sql  = "UPDATE tblweb_carrusel SET etiqueta = ?, titulo = ?, descripcion = ?, imagen = ?, imagen_alt = ?, imagen_credito = ?,
                    fecha_inicio = ?, fecha_fin = ?, orden = ?, visible = ?
             WHERE id_carrusel = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssiii", $etiqueta, $slide['titulo'], $descripcion, $imagePath, $slide['imagen_alt'], $slide['imagen_credito'],
      $fechaInicio, $fechaFin, $slide['orden'], $slide['visible'], $id);
  } else {
    $sql  = "INSERT INTO tblweb_carrusel (etiqueta, titulo, descripcion, imagen, imagen_alt, imagen_credito,
                    fecha_inicio, fecha_fin, orden, visible)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssii", $etiqueta, $slide['titulo'], $descripcion, $imagePath, $slide['imagen_alt'], $slide['imagen_credito'],
      $fechaInicio, $fechaFin, $slide['orden'], $slide['visible']);
  }
  mysqli_stmt_execute($stmt);
  $newId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
  mysqli_stmt_close($stmt);

  panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_carrusel', $newId, $slide['titulo']);
} catch (mysqli_sql_exception $exception) {
  error_log('carrusel: ' . $exception->getMessage());

  // Si falló la base, se quita la imagen recién subida para no dejar archivos sin usar
  if ($newPath !== '' && is_file($siteRoot . '/' . $newPath)) {
    unlink($siteRoot . '/' . $newPath);
  }
  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $slide;
  header('Location: ' . $returnTo);
  exit;
}

// 7. Al cambiar la imagen se borra la anterior (solo si está dentro de uploads/carrusel/)
if ($isEditing && $newPath !== '' && $current['imagen'] !== '') {
  $oldFile = realpath($siteRoot . '/' . ltrim($current['imagen'], '/'));

  if ($oldFile !== false && strpos($oldFile, $siteRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'carrusel' . DIRECTORY_SEPARATOR) === 0) {
    unlink($oldFile);
  }
}

// 8. Volver a la lista (PRG)
$_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : 'Slide agregado.'];
header('Location: ' . $listPage);
exit;
