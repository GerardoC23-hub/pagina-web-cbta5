<?php
// Datos del formulario del carrusel (agregar o editar): deja las variables listas para la vista.
// Deja: $id, $isEditing, $slide, $errors, $error, $maxVisible

require_once __DIR__ . '/../../../includes/site-data.php';

$limits     = require __DIR__ . '/carrusel-limites.php';
$maxVisible = $limits['maxVisible'];

// 1. Con ?id se edita un slide; sin id se agrega uno nuevo
$id        = $_GET['id'] ?? null;
$isEditing = $id !== null && is_numeric($id);

$slide = [
  'etiqueta' => '', 'titulo' => '', 'descripcion' => '',
  'imagen' => '', 'imagen_alt' => '', 'imagen_credito' => '',
  'fecha_inicio' => '', 'fecha_fin' => '', 'orden' => 0, 'visible' => 1,
];

if ($isEditing) {
  $sql  = "SELECT * FROM tblweb_carrusel WHERE id_carrusel = ?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: index.php');
    exit;
  }
  $slide = array_map(fn ($value) => $value ?? '', $found);
}

// 2. Si el guardado falló, recuperar lo que escribió y los errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $field => $value) {
  if (array_key_exists($field, $slide) && $field !== 'imagen') {
    $slide[$field] = $value;
  }
}
