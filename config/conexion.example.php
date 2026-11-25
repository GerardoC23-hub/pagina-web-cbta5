<?php
// Conexión a la base de datos del proyecto (MySQLi).
// Copia este archivo como conexion.php y cambia usuario y contraseña por los tuyos.
// conexion.php no se sube a GitHub (está en .gitignore).
$host = 'srv638.hstgr.io';
$user = 'TU_USUARIO';
$pass = 'TU_CONTRASEÑA';
$db   = 'u408544566_proyecto_cbta5';

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    error_log('Error de conexión: ' . mysqli_connect_error());
    die('No se pudo conectar con la base de datos. Intenta más tarde.');
}

mysqli_set_charset($conexion, 'utf8mb4');

// Que los errores de MySQL lancen excepciones (así los try/catch de los formularios los atrapan)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
