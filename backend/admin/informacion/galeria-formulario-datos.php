<?php
// Datos del formulario de Galería: con ?id se edita; sin id se agrega y ?tipo dice si es Foto o Video.
// Deja: $id, $isEditing, $media, $isPhoto, $tab, $albums, $errors, $error

require_once __DIR__ . '/../../../includes/site-data.php';

$id        = $_GET['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);

$media = [
  'id_album' => '', 'titulo' => '', 'descripcion' => '', 'texto_alt' => '', 'tipo' => 'Foto',
  'archivo' => '', 'miniatura' => '', 'credito' => '', 'fecha' => '', 'orden' => 0, 'visible' => 1, 'video_url' => '',
];

// 1. Al editar, leer el registro de la base
if ($isEditing) {
  $stmt = mysqli_prepare($conexion, "SELECT * FROM tblweb_galeria WHERE id_galeria = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: index.php');
    exit;
  }
  $media = array_merge($media, array_map(fn ($value) => $value ?? '', $found));
} else {
  $media['tipo'] = ($_GET['tipo'] ?? '') === 'Video' ? 'Video' : 'Foto';
}

// Si el video es un enlace, se muestra en su campo
if (strpos($media['archivo'], 'https://') === 0) {
  $media['video_url'] = $media['archivo'];
}

$isPhoto = $media['tipo'] === 'Foto';
$tab     = $isPhoto ? 'photos' : 'videos';
$albums  = mysqli_fetch_all(mysqli_query($conexion, "SELECT id_album, nombre FROM tblweb_albumes ORDER BY orden, nombre"), MYSQLI_ASSOC);

// 2. Si el guardado falló, recuperar lo que escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $key => $value) {
  if (array_key_exists($key, $media)) {
    $media[$key] = $value;
  }
}
