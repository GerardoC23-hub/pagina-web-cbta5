<?php
// Datos de la lista de Servicios del panel: lee la base y deja las variables listas para la vista.
// Deja: $services, $total

require_once __DIR__ . '/../../../includes/site-data.php';

// Solo los servicios (la tabla también guarda clubes y actividades)
$sql      = "SELECT id_actividad, nombre, descripcion, horario
             FROM tblweb_actividades
             WHERE tipo = 'Servicio'
             ORDER BY orden ASC, id_actividad ASC";
$services = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

$total = count($services);
