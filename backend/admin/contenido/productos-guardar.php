<?php
// Guarda un producto: recibe el formulario, valida, sube la foto, guarda en la base y regresa a la lista.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-files.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../../admin/contenido/productos.php');
  exit;
}

$id        = $_POST['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);
$formPage  = '../../../admin/contenido/productos-formulario.php' . ($isEditing ? '?id=' . (int) $id : '');

// 1. Al editar, buscar la foto que tiene ahora
$currentImage = '';
if ($isEditing) {
  $stmt = mysqli_prepare($conexion, "SELECT imagen FROM tblweb_productos WHERE id_producto = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: ../../../admin/contenido/productos.php');
    exit;
  }
  $currentImage = (string) $found['imagen'];
}

// 2. Recibir lo que escribió la persona
$item = [];
foreach (['nombre', 'descripcion', 'precio', 'unidad', 'imagen_credito', 'estado'] as $field) {
  $item[$field] = trim($_POST[$field] ?? '');
}

$removeImage = isset($_POST['quitar_imagen']);
$file        = $_FILES['imagen'] ?? null;
$hasFile     = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;

// 3. Validar
$errors = [];

if (mb_strlen($item['nombre']) < 3 || mb_strlen($item['nombre']) > 100) {
  $errors['nombre'] = 'Nombre: de 3 a 100 caracteres.';
}
if (mb_strlen($item['descripcion']) < 10 || mb_strlen($item['descripcion']) > 255) {
  $errors['descripcion'] = 'Descripción: de 10 a 255 caracteres.';
}
if ($item['precio'] !== '' && (!is_numeric($item['precio']) || $item['precio'] < 0 || $item['precio'] > 99999999.99)) {
  $errors['precio'] = 'Precio: escribe un número, por ejemplo 25 o 25.50.';
}
if (mb_strlen($item['unidad']) > 30) {
  $errors['unidad'] = 'Unidad: máximo 30 caracteres.';
}
if (!in_array($item['estado'], ['Publicado', 'Borrador'], true)) {
  $errors['estado'] = 'Elige un estado.';
}
if ($hasFile && ($message = imageError($file)) !== '') {
  $errors['imagen'] = $message;
}

// ¿Quedará con foto? Solo si sube una o conserva la que tenía
$willHaveImage = $hasFile || ($currentImage !== '' && !$removeImage);

if ($willHaveImage && $item['imagen_credito'] === '') {
  $errors['imagen_credito'] = 'Escribe el autor de la foto.';
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

// 4. Guardar la foto nueva (si hay) y luego el registro
$newImage = '';
if ($hasFile) {
  $newImage = saveImage($file, 'productos', 'producto');
  if ($newImage === '') {
    $_SESSION['form_error'] = 'No se pudo guardar la foto. Intenta de nuevo.';
    $_SESSION['form_old']   = $item;
    header('Location: ' . $formPage);
    exit;
  }
}

$image = $newImage !== '' ? $newImage : ($removeImage ? '' : $currentImage);

// Los campos opcionales vacíos se guardan como NULL
$imageDb = $image === '' ? null : $image;
$credit  = $image === '' ? null : $item['imagen_credito'];
$priceDb = $item['precio'] === '' ? null : $item['precio'];
$unitDb  = $item['unidad'] === '' ? null : $item['unidad'];
$available = $item['estado'] === 'Publicado' ? 1 : 0;

try {
  if ($isEditing) {
    $sql  = "UPDATE tblweb_productos SET nombre = ?, descripcion = ?, precio = ?, unidad = ?,
                    imagen = ?, imagen_credito = ?, disponible = ?
             WHERE id_producto = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssii", $item['nombre'], $item['descripcion'], $priceDb, $unitDb,
      $imageDb, $credit, $available, $id);
  } else {
    // El producto nuevo va al final de la lista
    $next = (int) mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COALESCE(MAX(orden), 0) + 1 AS next FROM tblweb_productos"))['next'];

    $sql  = "INSERT INTO tblweb_productos (nombre, descripcion, precio, unidad, imagen, imagen_credito, orden, disponible)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssii", $item['nombre'], $item['descripcion'], $priceDb, $unitDb,
      $imageDb, $credit, $next, $available);
  }
  mysqli_stmt_execute($stmt);
  $savedId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
  mysqli_stmt_close($stmt);

  panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_productos', $savedId, $item['nombre']);

  // La foto anterior se borra para no dejar archivos sin usar
  if ($currentImage !== '' && $currentImage !== $image) {
    deleteImage($currentImage);
  }

  $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : 'Producto agregado.'];
  header('Location: ../../../admin/contenido/productos.php');
  exit;
} catch (mysqli_sql_exception $exception) {
  error_log('productos: ' . $exception->getMessage());
  deleteImage($newImage);

  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $item;
  header('Location: ' . $formPage);
  exit;
}
