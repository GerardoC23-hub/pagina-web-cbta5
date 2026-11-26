<?php
// Agrega o edita un álbum (solo por POST, desde la ventana de la lista) y regresa a la pestaña de álbumes.

require __DIR__ . '/../../../includes/panel-auth.php';
require __DIR__ . '/../../../includes/panel-validation.php';
require_once __DIR__ . '/../../../config/conexion.php';

panel_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id    = ctype_digit($_POST['id'] ?? '') ? (int) $_POST['id'] : 0;
  $name  = trim($_POST['nombre'] ?? '');
  $order = trim($_POST['orden'] ?? '');

  // 1. Validar
  $message = textError($name, 'el nombre del álbum', 2, 60);
  if ($message === '') {
    $message = orderError($order);
  }

  if ($message !== '') {
    $_SESSION['flash'] = ['type' => 'danger', 'text' => $message];
  } else {
    // 2. Guardar
    $order = (int) $order;
    try {
      if ($id) {
        $stmt = mysqli_prepare($conexion, "UPDATE tblweb_albumes SET nombre = ?, orden = ? WHERE id_album = ?");
        mysqli_stmt_bind_param($stmt, "sii", $name, $order, $id);
      } else {
        $stmt = mysqli_prepare($conexion, "INSERT INTO tblweb_albumes (nombre, orden) VALUES (?, ?)");
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
}

header('Location: ../../../admin/informacion/galeria/index.php?t=albums');
exit;
