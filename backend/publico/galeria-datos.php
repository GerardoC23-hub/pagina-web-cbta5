<?php
// Datos de la galería pública: lee la base y deja las variables listas para la vista.
// Deja: $albums, $albumId, $items, $total, $badgeClasses

require_once __DIR__ . '/../../includes/site-data.php';

// 1. Álbumes que tienen al menos una foto o video publicado (para los botones de filtro)
$sql    = "SELECT a.id_album, a.nombre
           FROM tblweb_albumes a
           WHERE EXISTS (SELECT 1 FROM tblweb_galeria g WHERE g.id_album = a.id_album AND g.visible = 1)
           ORDER BY a.orden, a.nombre";
$albums = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

// 2. Álbum elegido (?album=2); si no existe, se muestran todos
$albumId = is_numeric($_GET['album'] ?? '') ? (int) $_GET['album'] : 0;
if ($albumId && !in_array($albumId, array_column($albums, 'id_album'), true)) {
  $albumId = 0;
}

// 3. Fotos y videos publicados
$sql = "SELECT g.id_galeria, g.titulo, g.descripcion, g.texto_alt, g.tipo, g.archivo, g.miniatura, g.credito, g.fecha, g.id_album, a.nombre AS album
    FROM tblweb_galeria g
    LEFT JOIN tblweb_albumes a ON a.id_album = g.id_album
    WHERE g.visible = 1" . ($albumId ? " AND g.id_album = ?" : "") . "
    ORDER BY g.orden, g.fecha DESC, g.id_galeria DESC";
$stmt = mysqli_prepare($conexion, $sql);
if ($albumId) {
  mysqli_stmt_bind_param($stmt, "i", $albumId);
}
mysqli_stmt_execute($stmt);
$items = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
$total = count($items);

// 4. Colores de la insignia del álbum (se repiten en orden)
$badgeClasses = ['bg-success-subtle text-success-emphasis', 'bg-info-subtle text-info-emphasis', 'bg-warning-subtle text-warning-emphasis'];
