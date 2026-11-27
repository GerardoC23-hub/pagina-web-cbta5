<?php
// Datos de Servicios (lista pública): lee la base y deja las variables listas para la vista.
// Deja: $services

require_once __DIR__ . '/../../includes/site-data.php';

$sql = "SELECT id_actividad, nombre, descripcion, horario, imagen
        FROM tblweb_actividades
        WHERE tipo = 'Servicio' AND visible = 1
        ORDER BY orden ASC, id_actividad ASC";
$services = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
