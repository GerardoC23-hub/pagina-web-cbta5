<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

// Solo se elimina por POST (desde la ventana de confirmación de la lista)
$id = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && is_numeric($id)) {

    // 1. Buscar el nombre de la imagen antes de borrar el registro
    $sql  = "SELECT imagen FROM tblweb_carrusel WHERE id_carrusel = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $slide = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($slide) {
        // 2. Borrar el archivo físico de la carpeta uploads
        if ($slide['imagen']) {
            $rutaArchivo = __DIR__ . '/../../../uploads/carrusel/' . basename($slide['imagen']);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
        }

        // 3. Borrar el registro de la base de datos
        $sql  = "DELETE FROM tblweb_carrusel WHERE id_carrusel = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Slide eliminado.'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    }
}

header('Location: index.php');
exit;
