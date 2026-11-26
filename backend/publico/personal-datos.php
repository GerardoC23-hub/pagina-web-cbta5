<?php
// Datos del directorio de personal: lee la base y deja las variables listas para la vista.
// Solo se muestra el personal que autorizó publicar sus datos.
// Deja: $people, $total, $areas, $search, $area

require_once __DIR__ . '/../../includes/site-data.php';

// 1. Áreas (salen del ENUM de la base) y lo que se escribió en el buscador y el filtro
$areas  = enumValues($conexion, 'tblweb_personal', 'tipo');
$search = trim($_GET['search'] ?? '');
$area   = in_array($_GET['area'] ?? '', $areas, true) ? $_GET['area'] : '';

// 2. Leer el personal autorizado
$like = '%' . $search . '%';
$sql  = "SELECT nombre, cargo, tipo, correo, telefono
         FROM tblweb_personal
         WHERE autorizado = 1
           AND (? = '' OR tipo = ?)
           AND (? = '' OR nombre LIKE ? OR cargo LIKE ?)
         ORDER BY orden ASC, nombre ASC";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "sssss", $area, $area, $search, $like, $like);
mysqli_stmt_execute($stmt);
$people = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$total = count($people);
