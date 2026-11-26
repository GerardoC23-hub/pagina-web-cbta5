<?php
// Datos del detalle de una noticia (noticia.php?id=3): lee la base y deja las variables listas para la vista.
// Deja: $item (o null si no existe), $badgeColors

require_once __DIR__ . '/../../includes/site-data.php';

$id   = (int) ($_GET['id'] ?? 0);
$sql  = "SELECT id_publicacion, titulo, resumen, contenido, categoria, imagen, imagen_credito, fecha_publicacion
         FROM tblweb_publicaciones
         WHERE id_publicacion = ? AND estado = 'Publicada' AND solo_alumnos = 0 AND fecha_publicacion <= CURDATE()";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
mysqli_stmt_close($stmt);

// Color de la etiqueta de cada categoría (diseño)
$badgeColors = [
  'Noticia' => 'bg-secondary-subtle text-secondary-emphasis',
  'Aviso'   => 'bg-warning-subtle text-warning-emphasis',
  'Evento'  => 'bg-info-subtle text-info-emphasis',
  'Logro'   => 'bg-success-subtle text-success-emphasis',
];
