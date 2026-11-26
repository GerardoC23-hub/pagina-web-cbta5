<?php
// Datos de la pantalla "Datos de contacto": lee la base y deja las variables listas para la vista.
// Deja: $rows, $contact, $social, $errors, $error

require_once __DIR__ . '/../../../config/conexion.php';

// 1. Leer de la base los datos de contacto y las redes sociales
$sql    = "SELECT id_seccion, clave, grupo, titulo, contenido, enlace, visible
           FROM tblweb_secciones
           WHERE grupo IN ('Contacto', 'Redes')
           ORDER BY grupo, orden";
$result = mysqli_query($conexion, $sql);
$rows   = [];

while ($row = mysqli_fetch_assoc($result)) {
  $rows[$row['clave']] = array_map(fn ($value) => $value ?? '', $row);
}

// 2. Si el guardado falló, recuperar lo que la persona escribió y sus errores (se usan una sola vez)
$errors = $_SESSION['form_errors'] ?? [];
$error  = $_SESSION['form_error'] ?? '';
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_error'], $_SESSION['form_old']);

foreach ($old as $key => $values) {
  if (isset($rows[$key])) {
    $rows[$key]['contenido'] = $values['contenido'];
    $rows[$key]['enlace']    = $values['enlace'];
  }
}

// 3. Separar las filas en los dos grupos de la pantalla
$contact = array_filter($rows, fn ($row) => $row['grupo'] === 'Contacto');
$social  = array_filter($rows, fn ($row) => $row['grupo'] === 'Redes');
