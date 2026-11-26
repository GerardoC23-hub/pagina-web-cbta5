<?php
// Datos de la lista de textos legales (privacidad, términos y accesibilidad).
// Deja: $legals

require_once __DIR__ . '/../../../config/conexion.php';

$sql    = "SELECT clave, titulo, CHAR_LENGTH(contenido) AS largo, actualizado_en
           FROM tblweb_secciones WHERE grupo = 'Legal' ORDER BY orden, id_seccion";
$legals = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

// Página pública de cada texto (relativa a la raíz del sitio)
$legalPages = [
  'legal_privacidad'    => 'privacidad.php',
  'legal_terminos'      => 'terminos.php',
  'legal_accesibilidad' => 'accesibilidad.php',
];
