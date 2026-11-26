<?php
// Guarda una foto o un video de la galería: recibe el formulario, valida, sube los archivos, guarda y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require __DIR__ . '/../../../includes/panel-validation.php';
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../includes/site-data.php';

panel_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../../admin/informacion/galeria/index.php');
  exit;
}

$id        = $_POST['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);
$listPage  = '../../../admin/informacion/galeria/index.php';

// 1. Saber si es foto o video, y al editar, buscar lo que tiene guardado
$type    = ($_POST['tipo'] ?? '') === 'Video' ? 'Video' : 'Foto';
$current = ['archivo' => '', 'miniatura' => ''];

if ($isEditing) {
  $stmt = mysqli_prepare($conexion, "SELECT tipo, archivo, miniatura FROM tblweb_galeria WHERE id_galeria = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: ' . $listPage);
    exit;
  }
  $type    = $found['tipo'];
  $current = ['archivo' => (string) $found['archivo'], 'miniatura' => (string) $found['miniatura']];
}

$isPhoto  = $type === 'Foto';
$tab      = $isPhoto ? 'photos' : 'videos';
$formPage = '../../../admin/informacion/galeria/formulario.php?' . ($isEditing ? 'id=' . (int) $id : 'tipo=' . $type);

// 2. Recibir lo que escribió la persona
$media = [];
foreach (['titulo', 'descripcion', 'texto_alt', 'credito', 'fecha', 'orden', 'video_url'] as $field) {
  $media[$field] = trim($_POST[$field] ?? '');
}
$media['id_album'] = ctype_digit($_POST['id_album'] ?? '') ? (int) $_POST['id_album'] : '';
$media['visible']  = isset($_POST['visible']) ? 1 : 0;

$image     = $_FILES[$isPhoto ? 'imagen' : 'miniatura'] ?? null;
$hasImage  = $image && $image['error'] !== UPLOAD_ERR_NO_FILE;
$video     = $_FILES['video'] ?? null;
$hasVideo  = $video && $video['error'] !== UPLOAD_ERR_NO_FILE;
$imageName = $isPhoto ? 'imagen' : 'miniatura';

// 3. Validar (cada error se guarda con el nombre de su campo)
$errors = [];

if (($message = textError($media['titulo'], 'el título', 3, 120)) !== '') {
  $errors['titulo'] = $message;
}
if (($message = textError($media['descripcion'], 'la descripción', 3, 255, false)) !== '') {
  $errors['descripcion'] = $message;
}
if ($isPhoto && ($message = textError($media['texto_alt'], 'el texto alternativo', 8, 150)) !== '') {
  $errors['texto_alt'] = $message;
}
if (($message = textError($media['credito'], 'el crédito', 3, 120, false)) !== '') {
  $errors['credito'] = $message;
}
if ($media['fecha'] !== '') {
  $date = DateTime::createFromFormat('Y-m-d', $media['fecha']);
  if (!$date || $date->format('Y-m-d') !== $media['fecha']) {
    $errors['fecha'] = 'La fecha no es válida.';
  }
}
if (($message = orderError($media['orden'])) !== '') {
  $errors['orden'] = $message;
}
if ($media['id_album'] !== '') {
  $stmt = mysqli_prepare($conexion, "SELECT id_album FROM tblweb_albumes WHERE id_album = ?");
  mysqli_stmt_bind_param($stmt, "i", $media['id_album']);
  mysqli_stmt_execute($stmt);
  if (!mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
    $errors['id_album'] = 'Elige un álbum de la lista.';
  }
  mysqli_stmt_close($stmt);
}

// Imagen (foto) o portada (video)
if ($hasImage && ($message = imageError($image, 2)) !== '') {
  $errors[$imageName] = $message;
} elseif ($isPhoto && !$hasImage && $current['archivo'] === '') {
  $errors['imagen'] = 'Selecciona una imagen.';
}

// Video: un enlace de YouTube o Vimeo, o un archivo MP4
if (!$isPhoto) {
  if ($media['video_url'] !== '') {
    if (mb_strlen($media['video_url']) > 255 || strpos($media['video_url'], 'https://') !== 0 || videoEmbedUrl($media['video_url']) === '') {
      $errors['video_url'] = 'Escribe un enlace de YouTube o Vimeo que empiece con https://.';
    }
  } elseif ($hasVideo) {
    if (($message = videoError($video, 10)) !== '') {
      $errors['video'] = $message;
    }
  } elseif ($current['archivo'] === '') {
    $errors['video_url'] = 'Escribe el enlace del video o sube un archivo MP4.';
  }
}

// Con errores: se regresa al formulario con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $media;
  header('Location: ' . $formPage);
  exit;
}

// 4. Guardar los archivos nuevos (si hay)
$prefix   = slugify($media['titulo'], $isPhoto ? 'foto' : 'video');
$newMain  = '';
$newThumb = '';

if ($isPhoto && $hasImage) {
  $newMain = saveImage($image, 'galeria/fotos', $prefix);
} elseif (!$isPhoto) {
  if ($hasImage) {
    $newThumb = saveImage($image, 'galeria/miniaturas', $prefix);
  }
  if ($hasVideo && $media['video_url'] === '') {
    $newMain = saveVideo($video, 'galeria/videos', $prefix);
  }
}

if (($hasImage && ($isPhoto ? $newMain : $newThumb) === '') || (!$isPhoto && $hasVideo && $media['video_url'] === '' && $newMain === '')) {
  deleteImage($newMain);
  deleteImage($newThumb);
  $_SESSION['form_error'] = 'No se pudo guardar el archivo. Intenta de nuevo.';
  $_SESSION['form_old']   = $media;
  header('Location: ' . $formPage);
  exit;
}

// 5. Guardar en la base de datos
if ($isPhoto) {
  $file      = $newMain !== '' ? $newMain : $current['archivo'];
  $thumbnail = null;
} else {
  // Video: enlace escrito, archivo MP4 subido, o lo que ya tenía
  $file      = $media['video_url'] !== '' ? $media['video_url'] : ($newMain !== '' ? $newMain : $current['archivo']);
  $thumbnail = $newThumb !== '' ? $newThumb : ($current['miniatura'] !== '' ? $current['miniatura'] : null);
}

// Los campos opcionales vacíos se guardan como NULL
$albumId     = $media['id_album'] === '' ? null : $media['id_album'];
$description = $media['descripcion'] === '' ? null : $media['descripcion'];
$altText     = $media['texto_alt'] === '' ? null : $media['texto_alt'];
$credit      = $media['credito'] === '' ? null : $media['credito'];
$date        = $media['fecha'] === '' ? null : $media['fecha'];
$order       = (int) $media['orden'];
$userId      = $_SESSION['panel_user']['id'];

try {
  if ($isEditing) {
    $sql  = "UPDATE tblweb_galeria SET id_album = ?, titulo = ?, descripcion = ?, texto_alt = ?, archivo = ?, miniatura = ?,
                    credito = ?, fecha = ?, orden = ?, visible = ?
             WHERE id_galeria = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "isssssssiii", $albumId, $media['titulo'], $description, $altText, $file, $thumbnail,
      $credit, $date, $order, $media['visible'], $id);
  } else {
    $sql  = "INSERT INTO tblweb_galeria (id_album, titulo, descripcion, texto_alt, tipo, archivo, miniatura, credito, fecha, orden, visible, id_usuario)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "issssssssiii", $albumId, $media['titulo'], $description, $altText, $type, $file, $thumbnail,
      $credit, $date, $order, $media['visible'], $userId);
  }
  mysqli_stmt_execute($stmt);
  $savedId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
  mysqli_stmt_close($stmt);

  // Al cambiar un archivo se borra el anterior para no dejar archivos sin usar
  if ($isEditing) {
    if ($file !== $current['archivo']) {
      deleteImage($current['archivo']);
    }
    if ($newThumb !== '') {
      deleteImage($current['miniatura']);
    }
  }

  panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_galeria', $savedId, ($isPhoto ? 'Foto: ' : 'Video: ') . $media['titulo']);
  $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : ($isPhoto ? 'Foto agregada.' : 'Video agregado.')];
  header('Location: ' . $listPage . '?t=' . $tab);
  exit;
} catch (mysqli_sql_exception $exception) {
  error_log('galeria: ' . $exception->getMessage());
  // Si no se pudo guardar, se borran los archivos recién subidos
  deleteImage($newMain);
  deleteImage($newThumb);
  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $media;
  header('Location: ' . $formPage);
  exit;
}
