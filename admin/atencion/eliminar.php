<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../config/conexion.php';

$tab = ($_POST['t'] ?? 'messages') === 'requests' ? 'requests' : 'messages';

// Solo se eliminan mensajes y solicitudes ya atendidos, por POST desde la ventana de confirmación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($_POST['id'] ?? '')) {
    $id   = (int) $_POST['id'];
    $type = ($_POST['tipo'] ?? '') === 'solicitud' ? 'solicitud' : 'mensaje';

    if ($type === 'mensaje') {
        $sql = "SELECT folio, estado FROM tblweb_mensajes WHERE id_mensaje = ?";
    } else {
        $sql = "SELECT folio, estado FROM tblweb_solicitudes WHERE id_solicitud = ?";
    }
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$row) {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
    } elseif ($row['estado'] !== 'Atendido') {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Solo se eliminan los atendidos.'];
    } else {
        // Una solicitud puede traer archivos: se borran de la carpeta storage antes de borrar el registro
        if ($type === 'solicitud') {
            // La ruta se guarda relativa a la raíz del sitio (por ejemplo storage/solicitudes/acta-a1b2c3.pdf)
            $siteRoot = str_replace('\\', '/', realpath(__DIR__ . '/../..'));
            $stmt = mysqli_prepare($conexion, "SELECT ruta FROM tblweb_solicitud_archivos WHERE id_solicitud = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $files = mysqli_stmt_get_result($stmt);
            while ($file = mysqli_fetch_assoc($files)) {
                $path = realpath($siteRoot . '/' . ltrim($file['ruta'], '/'));
                // Solo se borra lo que está dentro de storage/
                if ($path && strpos(str_replace('\\', '/', $path), $siteRoot . '/storage/') === 0 && is_file($path)) {
                    unlink($path);
                }
            }
            mysqli_stmt_close($stmt);
            $sql = "DELETE FROM tblweb_solicitudes WHERE id_solicitud = ?";
        } else {
            $sql = "DELETE FROM tblweb_mensajes WHERE id_mensaje = ?";
        }
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        panel_log($conexion, 'Baja', $type === 'mensaje' ? 'tblweb_mensajes' : 'tblweb_solicitudes', $id, 'Folio: ' . $row['folio']);
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Se eliminó ' . $row['folio'] . '.'];
    }
}

header('Location: mensajes.php?t=' . $tab);
exit;
