<?php
// Datos del detalle de un servicio (servicio-detalle.php?id=3): lee la base y deja las variables listas para la vista.
// Deja: $item (o null si no existe), $others

require_once __DIR__ . '/../../includes/site-data.php';

// 1. El servicio que se pidió
$id   = (int) ($_GET['id'] ?? 0);
$sql  = "SELECT id_actividad, nombre, descripcion, horario, imagen, imagen_credito
         FROM tblweb_actividades
         WHERE id_actividad = ? AND tipo = 'Servicio' AND visible = 1";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
mysqli_stmt_close($stmt);

// 2. Los demás servicios, para "También te puede interesar"
$others = [];
if ($item !== null) {
    $sql  = "SELECT id_actividad, nombre, descripcion, horario, imagen
             FROM tblweb_actividades
             WHERE tipo = 'Servicio' AND visible = 1 AND id_actividad <> ?
             ORDER BY orden ASC, id_actividad ASC
             LIMIT 4";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $others = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
