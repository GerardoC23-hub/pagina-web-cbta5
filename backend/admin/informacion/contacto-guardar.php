<?php
// Guarda los datos de contacto: recibe el formulario, valida, actualiza la base y regresa a la pantalla.

require __DIR__ . '/../../../includes/panel-auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

$returnTo = '../../../admin/informacion/contacto.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $returnTo);
  exit;
}

// Una dirección web debe empezar con http:// o https://
function isValidUrl(string $url): bool
{
  return filter_var($url, FILTER_VALIDATE_URL) !== false && preg_match('#^https?://#i', $url) === 1;
}

// 1. Leer qué claves existen y recibir lo que escribió la persona
$result = mysqli_query($conexion, "SELECT clave, grupo FROM tblweb_secciones WHERE grupo IN ('Contacto', 'Redes')");
$rows   = [];

while ($row = mysqli_fetch_assoc($result)) {
  $rows[$row['clave']] = [
    'grupo'     => $row['grupo'],
    'contenido' => trim($_POST['contenido'][$row['clave']] ?? ''),
    'enlace'    => trim($_POST['enlace'][$row['clave']] ?? ''),
  ];
}

// 2. Validar
$errors = [];
$text   = fn (string $key) => mb_strlen($rows[$key]['contenido'] ?? '');

if ($text('contacto_direccion') < 10 || $text('contacto_direccion') > 200) {
  $errors['contacto_direccion'] = 'Dirección: de 10 a 200 caracteres.';
}
if ($rows['contacto_direccion']['enlace'] !== '' && !isValidUrl($rows['contacto_direccion']['enlace'])) {
  $errors['contacto_direccion_enlace'] = 'Enlace no válido.';
}
if (!preg_match('/^[0-9\s()+\-]{8,20}$/', $rows['contacto_telefono']['contenido'])) {
  $errors['contacto_telefono'] = 'Teléfono no válido.';
}
if (!filter_var($rows['contacto_correo']['contenido'], FILTER_VALIDATE_EMAIL)) {
  $errors['contacto_correo'] = 'Correo no válido.';
}
if ($text('contacto_horario') < 5 || $text('contacto_horario') > 100) {
  $errors['contacto_horario'] = 'Horario: de 5 a 100 caracteres.';
}
if ($text('contacto_sitio') < 3 || $text('contacto_sitio') > 60) {
  $errors['contacto_sitio'] = 'Nombre: de 3 a 60 caracteres.';
}
if (!isValidUrl($rows['contacto_sitio']['enlace'])) {
  $errors['contacto_sitio_enlace'] = 'Enlace no válido.';
}

foreach ($rows as $key => $row) {
  if ($row['grupo'] !== 'Redes') {
    continue;
  }
  if ($row['enlace'] !== '' && !isValidUrl($row['enlace'])) {
    $errors[$key . '_enlace'] = 'Enlace no válido.';
  }
  if (mb_strlen($row['contenido']) > 60) {
    $errors[$key] = 'Máximo 60 caracteres.';
  }
}

// Con errores: se regresa a la pantalla con lo que escribió y los mensajes
if ($errors) {
  $_SESSION['form_errors'] = $errors;
  $_SESSION['form_old']    = $rows;
  header('Location: ' . $returnTo);
  exit;
}

// 3. Guardar: todo o nada (transacción)
try {
  mysqli_begin_transaction($conexion);

  $sql  = "UPDATE tblweb_secciones SET contenido = ?, enlace = ?, visible = ? WHERE clave = ?";
  $stmt = mysqli_prepare($conexion, $sql);

  foreach ($rows as $key => $row) {
    // Los campos opcionales vacíos se guardan como NULL
    $contenido = $row['contenido'] === '' ? null : $row['contenido'];
    $enlace    = $row['enlace'] === '' ? null : $row['enlace'];
    // Una red social sin enlace se oculta del sitio
    $visible   = ($row['grupo'] === 'Redes' && $enlace === null) ? 0 : 1;

    mysqli_stmt_bind_param($stmt, "ssis", $contenido, $enlace, $visible, $key);
    mysqli_stmt_execute($stmt);
  }
  mysqli_stmt_close($stmt);

  panel_log($conexion, 'Cambio', 'tblweb_secciones', null, 'Datos de contacto y redes sociales');
  mysqli_commit($conexion);

  $_SESSION['flash'] = ['type' => 'success', 'text' => 'Datos guardados.'];
} catch (mysqli_sql_exception $exception) {
  mysqli_rollback($conexion);
  error_log('contacto: ' . $exception->getMessage());

  $_SESSION['form_error'] = 'No se pudo guardar. Intenta de nuevo.';
  $_SESSION['form_old']   = $rows;
}

// 4. Volver a la pantalla (PRG)
header('Location: ' . $returnTo);
exit;
