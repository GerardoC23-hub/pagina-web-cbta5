<?php
// Datos de Preguntas frecuentes: lee la base y deja las variables listas para la vista.
// Deja: $faqs

require_once __DIR__ . '/../../includes/site-data.php';

$sql  = "SELECT pregunta, respuesta FROM tblweb_preguntas WHERE visible = 1 ORDER BY orden ASC, id_pregunta ASC";
$faqs = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
