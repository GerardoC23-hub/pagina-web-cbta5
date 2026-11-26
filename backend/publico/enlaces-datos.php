<?php
// Datos de Enlaces de interés: lee la base y deja las variables listas para la vista.
// Deja: $links

require_once __DIR__ . '/../../includes/site-data.php';

$sql   = "SELECT titulo, url, descripcion FROM tblweb_enlaces WHERE visible = 1 ORDER BY orden ASC, id_enlace ASC";
$links = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
