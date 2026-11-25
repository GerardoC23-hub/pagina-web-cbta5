<?php
// Datos de Inicio: lee la base y deja las variables listas para la vista.
// Deja: $slides, $careers, $admission, $steps, $news, $newsBadges, $activities

require_once __DIR__ . '/../../includes/site-data.php';

// 1. Slides del carrusel: solo los visibles y dentro de sus fechas, en su orden
$sql       = "SELECT * FROM tblweb_carrusel
              WHERE visible = 1
        AND (fecha_inicio IS NULL OR fecha_inicio <= CURDATE())
        AND (fecha_fin    IS NULL OR fecha_fin    >= CURDATE())
              ORDER BY orden ASC, id_carrusel DESC";
$slides    = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

// 2. Oferta educativa: las primeras 4 carreras activas
$careers = mysqli_fetch_all(mysqli_query($conexion,
  "SELECT nombre, descripcion FROM tblweb_carreras WHERE activa = 1 ORDER BY orden, nombre LIMIT 4"), MYSQLI_ASSOC);

// 3. Nuevo ingreso: pasos (un renglón «Título: texto» por paso) y nota para aspirantes
$admission = [];
$sections  = mysqli_query($conexion, "SELECT clave, contenido FROM tblweb_secciones WHERE clave IN ('ingreso_proceso', 'ingreso_nota')");
while ($row = mysqli_fetch_assoc($sections)) {
  $admission[$row['clave']] = (string) $row['contenido'];
}
$steps = [];
foreach (preg_split('/\R/', $admission['ingreso_proceso'] ?? '', -1, PREG_SPLIT_NO_EMPTY) as $line) {
  $parts   = explode(':', $line, 2);
  $steps[] = ['title' => trim($parts[0]), 'text' => trim($parts[1] ?? '')];
}

// 4. Noticias y avisos: las 3 publicaciones más recientes (públicas, ya publicadas)
$news = mysqli_fetch_all(mysqli_query($conexion,
  "SELECT titulo, resumen, categoria, imagen, fecha_publicacion FROM tblweb_publicaciones
     WHERE estado = 'Publicada' AND solo_alumnos = 0 AND fecha_publicacion <= CURDATE()
     ORDER BY fecha_publicacion DESC, id_publicacion DESC LIMIT 3"), MYSQLI_ASSOC);
$newsBadges = ['Noticia' => 'bg-secondary-subtle text-secondary-emphasis', 'Aviso' => 'bg-warning-subtle text-warning-emphasis',
               'Evento' => 'bg-info-subtle text-info-emphasis', 'Logro' => 'bg-success-subtle text-success-emphasis'];

// 5. Vida estudiantil: 3 clubes, actividades o servicios visibles
$activities = mysqli_fetch_all(mysqli_query($conexion,
  "SELECT nombre, tipo, descripcion, imagen FROM tblweb_actividades WHERE visible = 1 ORDER BY orden, id_actividad LIMIT 3"), MYSQLI_ASSOC);
