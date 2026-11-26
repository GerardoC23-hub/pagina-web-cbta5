<?php
// Datos de la lista de Galería: según la pestaña (fotos, videos o álbumes) lee la base y deja las variables listas para la vista.
// Deja: $tab, $tabs, $result, $total

$tab  = $_GET['t'] ?? 'photos';
$tabs = ['photos' => 'Fotos', 'videos' => 'Videos', 'albums' => 'Álbumes'];

if (!isset($tabs[$tab])) {
  $tab = 'photos';
}

if ($tab === 'albums') {
  $sql    = "SELECT a.id_album, a.nombre, a.orden, COUNT(g.id_galeria) AS total
             FROM tblweb_albumes a
             LEFT JOIN tblweb_galeria g ON g.id_album = a.id_album
             GROUP BY a.id_album, a.nombre, a.orden
             ORDER BY a.orden, a.nombre";
  $result = mysqli_query($conexion, $sql);
} else {
  $type   = $tab === 'photos' ? 'Foto' : 'Video';
  $sql    = "SELECT g.*, a.nombre AS album
             FROM tblweb_galeria g
             LEFT JOIN tblweb_albumes a ON a.id_album = g.id_album
             WHERE g.tipo = ?
             ORDER BY g.orden, g.id_galeria DESC";
  $stmt   = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "s", $type);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
}
$total = mysqli_num_rows($result);
