<?php
// Datos del formulario de Noticias: con ?id se edita una noticia; sin id se agrega una nueva.
// Deja: $id, $isEditing, $item, $errors, $error, $categories, $states

require_once __DIR__ . '/../../../includes/site-data.php';

$categories = enumValues($conexion, 'tblweb_publicaciones', 'categoria');
$states     = enumValues($conexion, 'tblweb_publicaciones', 'estado');

$id        = $_GET['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);

$item = [
  'titulo' => '', 'resumen' => '', 'contenido' => '', 'categoria' => $categories[0] ?? '',
  'imagen' => '', 'imagen_credito' => '', 'fecha_publicacion' => date('Y-m-d'), 'estado' => $states[0] ?? '',
];

// 1. Al editar, leer la noticia de la base
if ($isEditing) {
  $sql  = "SELECT * FROM tblweb_publicaciones WHERE id_publicacion = ?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: noticias.php');
    exit;
  }
  $item = array_map(fn ($value) => $value ?? '', $found);
}

// 2. Si el guardado falló, recuperar lo que escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $key => $value) {
  if (array_key_exists($key, $item)) {
    $item[$key] = $value;
  }
}
