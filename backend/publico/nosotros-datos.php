<?php
// Datos de la página Nosotros: lee los textos del plantel y deja las variables listas para la vista.
// Deja: $about (cada texto por su clave: mision, vision, valores, historia, nombre_oficial, lema)

require_once __DIR__ . '/../../includes/site-data.php';

$sql    = "SELECT clave, titulo, contenido FROM tblweb_secciones
           WHERE grupo = 'Institucional' AND visible = 1
             AND clave IN ('nombre_oficial', 'mision', 'vision', 'valores', 'historia', 'lema')";
$result = mysqli_query($conexion, $sql);
$about  = [];

while ($row = mysqli_fetch_assoc($result)) {
  $about[$row['clave']] = $row;
}
