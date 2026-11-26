<?php
// Datos de la lista del carrusel: deja las variables listas para la vista.
// Deja: $slides, $total, $today, $maxVisible

require_once __DIR__ . '/../../../includes/site-data.php';

$limits     = require __DIR__ . '/carrusel-limites.php';
$maxVisible = $limits['maxVisible'];

// 1. Leer todos los slides, en su orden
$sql    = "SELECT * FROM tblweb_carrusel ORDER BY orden ASC, id_carrusel DESC";
$slides = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

// 2. Datos para mostrar la lista
$total = count($slides);
$today = date('Y-m-d');
