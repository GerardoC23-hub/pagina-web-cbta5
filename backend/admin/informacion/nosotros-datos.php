<?php
// Datos de la pantalla "Nosotros": lee los textos y deja las variables listas para la vista.
// Deja: $texts, $errors, $error

require_once __DIR__ . '/../../../config/conexion.php';

// 1. Leer de la base los cuatro textos
$sql    = "SELECT clave, titulo, contenido FROM tblweb_secciones
           WHERE clave IN ('mision', 'vision', 'valores', 'historia')
           ORDER BY orden";
$result = mysqli_query($conexion, $sql);
$texts  = [];

while ($row = mysqli_fetch_assoc($result)) {
  $texts[$row['clave']] = array_map(fn ($value) => $value ?? '', $row);
}

// 2. Si el guardado falló, recuperar lo que escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $key => $content) {
  if (isset($texts[$key])) {
    $texts[$key]['contenido'] = $content;
  }
}
