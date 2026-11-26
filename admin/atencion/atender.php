<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../config/conexion.php';

$tab = ($_POST['t'] ?? 'messages') === 'requests' ? 'requests' : 'messages';

// Cambiar el estado de un mensaje o solicitud y guardar la nota del equipo (solo por POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type   = ($_POST['tipo'] ?? '') === 'solicitud' ? 'solicitud' : 'mensaje';
    $id     = is_numeric($_POST['id'] ?? '') ? (int) $_POST['id'] : 0;
    $state  = $_POST['estado'] ?? '';
    $note   = trim($_POST['nota_interna'] ?? '');
    $userId = $_SESSION['panel_user']['id'];

    if (!$id || !in_array($state, ['Nuevo', 'En revisión', 'Atendido'], true)) {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Elige un estado válido.'];
    } elseif (mb_strlen($note) > 500) {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Nota: máximo 500 caracteres.'];
    } else {
        $note = $note === '' ? null : $note;

        // Si queda como «Atendido» se guarda la fecha; si se regresa a otro estado, se borra
        if ($type === 'mensaje') {
            $sql = "UPDATE tblweb_mensajes SET estado = ?, nota_interna = ?, id_usuario = ?, atendido_en = IF(? = 'Atendido', NOW(), NULL)
                    WHERE id_mensaje = ?";
        } else {
            $sql = "UPDATE tblweb_solicitudes SET estado = ?, nota_interna = ?, id_usuario = ?, atendida_en = IF(? = 'Atendido', NOW(), NULL)
                    WHERE id_solicitud = ?";
        }
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssisi", $state, $note, $userId, $state, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        panel_log($conexion, 'Cambio', $type === 'mensaje' ? 'tblweb_mensajes' : 'tblweb_solicitudes', $id, 'Estado: ' . $state);
        $_SESSION['flash'] = ['type' => 'success', 'text' => ($type === 'mensaje' ? 'El mensaje' : 'La solicitud') . ' quedó como «' . $state . '».'];
    }
}

header('Location: mensajes.php?t=' . $tab);
exit;
