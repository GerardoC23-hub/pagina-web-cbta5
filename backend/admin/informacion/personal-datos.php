<?php
// Datos de la lista de Personal: lee la base y deja las variables listas para la vista.
// Deja: $people, $total

require_once __DIR__ . '/../../../config/conexion.php';

$sql    = "SELECT id_personal, nombre, cargo, tipo, foto, orden, autorizado
           FROM tblweb_personal
           ORDER BY orden ASC, nombre ASC";
$people = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
$total  = count($people);
