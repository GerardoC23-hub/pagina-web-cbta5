<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

// Agregar, editar o eliminar un álbum (solo por POST desde las ventanas de la lista)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = is_numeric($_POST['id'] ?? '') ? (int) $_POST['id'] : 0;

    if ($action === 'save') {
        $name  = trim($_POST['nombre'] ?? '');
        $order = is_numeric($_POST['orden'] ?? '') ? (int) $_POST['orden'] : 0;

        if (mb_strlen($name) < 2 || mb_strlen($name) > 60) {
            $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Nombre: de 2 a 60 caracteres.'];
        } else {
            try {
                if ($id) {
                    $sql  = "UPDATE tblweb_albumes SET nombre = ?, orden = ? WHERE id_album = ?";
                    $stmt = mysqli_prepare($conexion, $sql);
                    mysqli_stmt_bind_param($stmt, "sii", $name, $order, $id);
                } else {
                    $sql  = "INSERT INTO tblweb_albumes (nombre, orden) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conexion, $sql);
                    mysqli_stmt_bind_param($stmt, "si", $name, $order);
                }
                mysqli_stmt_execute($stmt);
                $savedId = $id ?: mysqli_insert_id($conexion);
                mysqli_stmt_close($stmt);

                panel_log($conexion, $id ? 'Cambio' : 'Alta', 'tblweb_albumes', $savedId, 'Álbum: ' . $name);
                $_SESSION['flash'] = ['type' => 'success', 'text' => $id ? 'Álbum actualizado.' : 'Álbum agregado.'];
            } catch (mysqli_sql_exception $exception) {
                // 1062 = el nombre ya existe (la tabla no permite dos álbumes con el mismo nombre)
                $_SESSION['flash'] = ['type' => 'danger', 'text' => $exception->getCode() === 1062 ? 'Ya existe un álbum con ese nombre.' : 'No se pudo guardar el álbum.'];
                error_log('albumes: ' . $exception->getMessage());
            }
        }

    } elseif ($action === 'delete' && $id) {
        $sql  = "SELECT nombre, (SELECT COUNT(*) FROM tblweb_galeria WHERE id_album = tblweb_albumes.id_album) AS total
                 FROM tblweb_albumes WHERE id_album = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $album = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$album) {
            $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
        } elseif ($album['total'] > 0) {
            // Con fotos o videos adentro no se borra: quedarían sin álbum sin que nadie lo note
            $_SESSION['flash'] = ['type' => 'danger', 'text' => 'No se puede eliminar «' . $album['nombre'] . '» porque tiene ' . $album['total'] . ' elemento(s).'];
        } else {
            $stmt = mysqli_prepare($conexion, "DELETE FROM tblweb_albumes WHERE id_album = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            panel_log($conexion, 'Baja', 'tblweb_albumes', $id, 'Álbum: ' . $album['nombre']);
            $_SESSION['flash'] = ['type' => 'success', 'text' => 'Álbum eliminado.'];
        }
    }
}

header('Location: index.php?t=albums');
exit;
