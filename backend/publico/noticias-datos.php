<?php
// Datos de Noticias y avisos (lista pública): lee la base y deja las variables listas para la vista.
// Deja: $news, $total, $categories, $search, $category, $badgeColors

require_once __DIR__ . '/../../includes/site-data.php';

// 1. Categorías (salen del ENUM de la base) y lo que se escribió en el buscador y el filtro
$categories = enumValues($conexion, 'tblweb_publicaciones', 'categoria');
$search     = trim($_GET['search'] ?? '');
$category   = in_array($_GET['category'] ?? '', $categories, true) ? $_GET['category'] : '';

// 2. Solo las publicaciones públicas y ya publicadas, las más nuevas primero
$like = '%' . $search . '%';
$sql  = "SELECT id_publicacion, titulo, resumen, categoria, imagen, fecha_publicacion
         FROM tblweb_publicaciones
         WHERE estado = 'Publicada' AND solo_alumnos = 0 AND fecha_publicacion <= CURDATE()
           AND (? = '' OR categoria = ?)
           AND (? = '' OR titulo LIKE ? OR resumen LIKE ?)
         ORDER BY fecha_publicacion DESC, id_publicacion DESC";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "sssss", $category, $category, $search, $like, $like);
mysqli_stmt_execute($stmt);
$news = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$total = count($news);

// 3. Color de la etiqueta de cada categoría (diseño)
$badgeColors = [
  'Noticia' => 'bg-secondary-subtle text-secondary-emphasis',
  'Aviso'   => 'bg-warning-subtle text-warning-emphasis',
  'Evento'  => 'bg-info-subtle text-info-emphasis',
  'Logro'   => 'bg-success-subtle text-success-emphasis',
];
