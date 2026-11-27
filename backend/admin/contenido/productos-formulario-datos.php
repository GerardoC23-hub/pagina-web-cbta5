<?php
// Datos del formulario de Productos: con ?id se edita un producto; sin id se agrega uno nuevo.
// Deja: $id, $isEditing, $record, $states, $errors, $error

require_once __DIR__ . '/../../../includes/site-data.php';

$states = ['Publicado', 'Borrador'];

$id        = $_GET['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);

$record = [
  'nombre' => '', 'descripcion' => '', 'precio' => '', 'unidad' => '',
  'imagen' => '', 'imagen_credito' => '', 'estado' => $states[0],
];

// 1. Al editar, leer el producto de la base
if ($isEditing) {
  $sql  = "SELECT nombre, descripcion, precio, unidad, imagen, imagen_credito, disponible
           FROM tblweb_productos WHERE id_producto = ?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: productos.php');
    exit;
  }
  $found['estado'] = $found['disponible'] ? 'Publicado' : 'Borrador';
  unset($found['disponible']);
  $record = array_map(fn ($value) => $value ?? '', $found);
}

// 2. Si el guardado falló, recuperar lo que escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $key => $value) {
  if (array_key_exists($key, $record)) {
    $record[$key] = $value;
  }
}
