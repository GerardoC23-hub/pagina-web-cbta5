<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

$siteRoot  = realpath(__DIR__ . '/../../..');
$tab       = in_array($_POST['t'] ?? '', ['photos', 'videos'], true) ? $_POST['t'] : 'photos';
$id        = $_POST['id'] ?? null;

// Solo se elimina por POST (desde la ventana de confirmación de la lista)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && is_numeric($id)) {

    // 1. Buscar los archivos antes de borrar el registro
    $sql  = "SELECT titulo, tipo, archivo, miniatura FROM tblweb_galeria WHERE id_galeria = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($item) {
        // 2. Borrar los archivos físicos (la foto, o la portada y el MP4 de un video; los enlaces no son archivos)
        $files = $item['tipo'] === 'Foto' ? [$item['archivo']] : [$item['miniatura'], $item['archivo']];
        foreach ($files as $file) {
            // Solo se borra lo que está dentro de uploads/galeria
            $full = $file && strpos($file, 'uploads/galeria/') === 0 ? realpath($siteRoot . '/' . $file) : false;
            if ($full && strpos(str_replace('\\', '/', $full), str_replace('\\', '/', $siteRoot) . '/uploads/galeria/') === 0 && is_file($full)) {
                unlink($full);
            }
        }

        // 3. Borrar el registro de la base de datos
        $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_galeria WHERE id_galeria = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        panel_log($conexion, 'Baja', 'tblweb_galeria', (int) $id, ($item['tipo'] === 'Foto' ? 'Foto: ' : 'Video: ') . $item['titulo']);
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Se eliminó «' . $item['titulo'] . '».'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    }
}

header('Location: index.php?t=' . $tab);
exit;
