<?php
// Datos del formulario de Personal: con ?id se edita una persona; sin id se agrega una nueva.
// Deja: $id, $isEditing, $person, $errors, $error

require_once __DIR__ . '/../../../config/conexion.php';

$id        = $_GET['id'] ?? null;
$isEditing = $id !== null && ctype_digit((string) $id);

$person = [
  'nombre' => '', 'cargo' => '', 'tipo' => 'Docente', 'correo' => '', 'telefono' => '',
  'foto' => '', 'foto_credito' => '', 'orden' => 0, 'autorizado' => 0,
];

// 1. Al editar, leer la persona de la base
if ($isEditing) {
  $sql  = "SELECT * FROM tblweb_personal WHERE id_personal = ?";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if (!$found) {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    header('Location: personal.php');
    exit;
  }
  $person = array_map(fn ($value) => $value ?? '', $found);
}

// 2. Si el guardado falló, recuperar lo que escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $key => $value) {
  if (array_key_exists($key, $person)) {
    $person[$key] = $value;
  }
}
