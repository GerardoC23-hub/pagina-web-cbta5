<?php
// Datos de la lista de Preguntas frecuentes: lee la base y deja las variables listas para la vista.
// Deja: $faqs, $total

require_once __DIR__ . '/../../../config/conexion.php';

$sql   = "SELECT id_pregunta, pregunta, respuesta, orden, visible FROM tblweb_preguntas ORDER BY orden, id_pregunta";
$faqs  = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
$total = count($faqs);
