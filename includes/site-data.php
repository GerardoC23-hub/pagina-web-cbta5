<?php
// Datos que muestra el sitio público y que se administran desde el panel.
// Si la base de datos no responde, esas partes simplemente no se muestran.

try {
    require_once __DIR__ . '/../config/conexion.php';
} catch (Throwable $exception) {
    error_log('site-data: ' . $exception->getMessage());
}

// Escapa texto para mostrarlo en HTML
function h(?string $text): string
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

// Valores de una columna ENUM (por ejemplo los asuntos de contacto): se leen de la base de datos
function enumValues(mysqli $conexion, string $table, string $column): array
{
    $sql  = "SELECT COLUMN_TYPE FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $table, $column);
    mysqli_stmt_execute($stmt);
    $row  = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$row || stripos($row['COLUMN_TYPE'], 'enum(') !== 0) {
        return [];
    }
    preg_match_all("/'((?:[^']|'')*)'/", $row['COLUMN_TYPE'], $matches);
    return array_map(fn ($value) => str_replace("''", "'", $value), $matches[1]);
}

// Fecha en español: 2025-07-18 → 18 de julio de 2025
function formatDateEs(?string $date): string
{
    $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $time   = $date ? strtotime($date) : false;
    return $time ? date('j', $time) . ' de ' . $months[(int) date('n', $time) - 1] . ' de ' . date('Y', $time) : '';
}

// Ruta de un archivo subido desde el panel (se guarda relativa a la raíz del sitio). Sin archivo: imagen de relleno.
function siteFile(?string $path, string $base): string
{
    if ($path === null || trim($path) === '') {
        return $base . 'assets/img/placeholder.svg';
    }
    return preg_match('#^https?://#i', $path) ? $path : $base . ltrim($path, '/');
}

// Enlace de YouTube o Vimeo → enlace para insertarlo en la página (iframe). Otro enlace: devuelve ''.
function videoEmbedUrl(string $url): string
{
    if (preg_match('#^https://(?:www\.)?youtube\.com/watch\?(?:\S*&)?v=([\w-]{6,20})#i', $url, $m)
        || preg_match('#^https://youtu\.be/([\w-]{6,20})#i', $url, $m)
        || preg_match('#^https://(?:www\.)?youtube\.com/embed/([\w-]{6,20})#i', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    if (preg_match('#^https://(?:player\.)?vimeo\.com/(?:video/)?(\d+)#i', $url, $m)) {
        return 'https://player.vimeo.com/video/' . $m[1];
    }
    return '';
}

// Datos de contacto y redes sociales (tblweb_secciones). Se leen una sola vez por página.
function siteContact(): array
{
    global $conexion;
    static $data = null;
    if ($data !== null) {
        return $data;
    }

    // Sin datos: si la base no responde, no se muestra nada (todo viene de la base de datos)
    $data = [
        'address' => ['text' => '', 'link' => ''],
        'phone'   => '',
        'email'   => '',
        'hours'   => '',
        'social'  => [],
    ];

    if (!isset($conexion) || !($conexion instanceof mysqli)) {
        return $data;
    }
    try {
        $sql    = "SELECT clave, titulo, contenido, enlace FROM tblweb_secciones
                   WHERE grupo IN ('Contacto', 'Redes') AND visible = 1 ORDER BY orden";
        $result = mysqli_query($conexion, $sql);
        $social = [];
        while ($row = mysqli_fetch_assoc($result)) {
            switch ($row['clave']) {
                case 'contacto_direccion':
                    $data['address'] = ['text' => (string) $row['contenido'], 'link' => (string) $row['enlace']];
                    break;
                case 'contacto_telefono':
                    $data['phone'] = (string) $row['contenido'];
                    break;
                case 'contacto_correo':
                    $data['email'] = (string) $row['contenido'];
                    break;
                case 'contacto_horario':
                    $data['hours'] = (string) $row['contenido'];
                    break;
                default:
                    // Redes sociales: solo las que tienen enlace
                    if (strpos($row['clave'], 'red_') === 0 && $row['enlace']) {
                        $social[] = ['name' => $row['titulo'], 'user' => (string) $row['contenido'], 'url' => $row['enlace']];
                    }
            }
        }
        $data['social'] = $social;
    } catch (Throwable $exception) {
        error_log('site-data: ' . $exception->getMessage());
    }
    return $data;
}
