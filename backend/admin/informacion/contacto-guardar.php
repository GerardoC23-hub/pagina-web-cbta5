<?php
// Guarda los datos de contacto: recibe el formulario, valida, actualiza la base y regresa a la pantalla.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-validation.php';
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

$checks = [
  'contacto_direccion' => ['la dirección', 10, 200],
  'contacto_horario'   => ['el horario', 5, 100],
  'contacto_sitio'     => ['el nombre del sitio', 3, 60],
];
foreach ($checks as $key => [$label, $min, $max]) {
  if (($message = textError($rows[$key]['contenido'] ?? '', $label, $min, $max)) !== '') {
    $errors[$key] = $message;
  }
}
if (($rows['contacto_direccion']['enlace'] ?? '') !== '' && !isValidUrl($rows['contacto_direccion']['enlace'])) {
  $errors['contacto_direccion_enlace'] = 'Escribe un enlace completo que empiece con https://.';
}
if (!preg_match('/^[0-9\s()+\-]{8,20}$/', $rows['contacto_telefono']['contenido'] ?? '')) {
  $errors['contacto_telefono'] = ($rows['contacto_telefono']['contenido'] ?? '') === '' ? 'Escribe el teléfono.' : 'El teléfono tiene de 8 a 20 números (puede llevar espacios, + y -).';
}
if (!filter_var($rows['contacto_correo']['contenido'] ?? '', FILTER_VALIDATE_EMAIL) || mb_strlen($rows['contacto_correo']['contenido']) > 120) {
  $errors['contacto_correo'] = ($rows['contacto_correo']['contenido'] ?? '') === '' ? 'Escribe el correo.' : 'Escribe un correo completo, por ejemplo nombre@cbta5.edu.mx.';
}
if (!isValidUrl($rows['contacto_sitio']['enlace'] ?? '')) {
  $errors['contacto_sitio_enlace'] = ($rows['contacto_sitio']['enlace'] ?? '') === '' ? 'Escribe el enlace del sitio.' : 'Escribe un enlace completo que empiece con https://.';
}

foreach ($rows as $key => $row) {
  if ($row['grupo'] !== 'Redes') {
    continue;
  }
  if ($row['enlace'] !== '' && !isValidUrl($row['enlace'])) {
    $errors[$key . '_enlace'] = 'Escribe un enlace completo que empiece con https://.';
  }
  if (mb_strlen($row['contenido']) > 60) {
    $errors[$key] = 'El usuario admite máximo 60 caracteres.';
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
