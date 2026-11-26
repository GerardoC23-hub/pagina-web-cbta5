<?php
// Datos de la lista de Noticias y avisos: lee la base (con filtros) y deja las variables listas para la vista.
// Deja: $news, $total, $categories, $states, $category, $state, $today

require_once __DIR__ . '/../../../includes/site-data.php';

$categories = enumValues($conexion, 'tblweb_publicaciones', 'categoria');
$states     = enumValues($conexion, 'tblweb_publicaciones', 'estado');
$today      = date('Y-m-d');

// 1. Filtros: solo se aceptan valores de la lista
$category = in_array($_GET['categoria'] ?? '', $categories, true) ? $_GET['categoria'] : '';
$state    = in_array($_GET['estado'] ?? '', $states, true) ? $_GET['estado'] : '';

// 2. Leer de la base
$sql    = "SELECT id_publicacion, titulo, categoria, fecha_publicacion, estado, solo_alumnos
           FROM tblweb_publicaciones
           WHERE (? = '' OR categoria = ?) AND (? = '' OR estado = ?)
           ORDER BY fecha_publicacion DESC, id_publicacion DESC";
$stmt   = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $category, $category, $state, $state);
mysqli_stmt_execute($stmt);
$news   = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$total = count($news);
