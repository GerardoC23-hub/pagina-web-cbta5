<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

$maxVisible = 5;
$maxSize    = 1 * 1024 * 1024;   // 1 MB
$extAllowed = ['jpg', 'jpeg', 'png', 'webp'];
$mimeAllowed = ['image/jpeg', 'image/png', 'image/webp'];
$uploadDir  = __DIR__ . '/../../../uploads/carrusel/';

// Con ?id se edita un slide; sin id se agrega uno nuevo
$id        = $_GET['id'] ?? $_POST['id'] ?? null;
$isEditing = $id !== null && is_numeric($id);

$slide = [
    'etiqueta' => '', 'titulo' => '', 'descripcion' => '',
    'boton_texto' => '', 'boton_enlace' => '', 'boton2_texto' => '', 'boton2_enlace' => '',
    'imagen' => '', 'imagen_alt' => '', 'imagen_credito' => '',
    'fecha_inicio' => '', 'fecha_fin' => '', 'orden' => 0, 'visible' => 1,
];

if ($isEditing) {
    $sql  = "SELECT * FROM tblweb_carrusel WHERE id_carrusel = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$found) {
        $_SESSION['flash'] = ['type' => 'danger', 'text' => 'Ya no existe.'];
        header('Location: index.php');
        exit;
    }
    $slide = array_map(fn ($value) => $value ?? '', $found);
}

$errors = [];
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recibir y limpiar los datos
    foreach (['etiqueta', 'titulo', 'descripcion', 'boton_texto', 'boton_enlace', 'boton2_texto', 'boton2_enlace', 'imagen_alt', 'imagen_credito', 'fecha_inicio', 'fecha_fin'] as $field) {
        $slide[$field] = trim($_POST[$field] ?? '');
    }
    $slide['orden']   = is_numeric($_POST['orden'] ?? '') ? (int) $_POST['orden'] : 0;
    $slide['visible'] = isset($_POST['visible']) ? 1 : 0;

    // 2. Validar los textos
    $linkPattern = '#^(https?://\S+|[\w/\-]+\.php\S*|\#\S*)$#';

    if (mb_strlen($slide['titulo']) < 3 || mb_strlen($slide['titulo']) > 150) {
        $errors['titulo'] = 'Título: de 3 a 150 caracteres.';
    }
    if (mb_strlen($slide['imagen_alt']) < 8) {
        $errors['imagen_alt'] = 'Describe la imagen (mínimo 8 caracteres).';
    }
    if ($slide['imagen_credito'] === '') {
        $errors['imagen_credito'] = 'Escribe el autor de la foto.';
    }
    foreach (['boton' => 'principal', 'boton2' => 'secundario'] as $prefix => $name) {
        $text = $slide[$prefix . '_texto'];
        $link = $slide[$prefix . '_enlace'];
        if ($link !== '' && !preg_match($linkPattern, $link)) {
            $errors[$prefix . '_enlace'] = 'Enlace no válido.';
        } elseif (($text === '') !== ($link === '')) {
            $errors[$prefix . '_texto'] = "El botón $name necesita texto y enlace, o dejar los dos vacíos.";
        }
    }
    if ($slide['fecha_inicio'] !== '' && $slide['fecha_fin'] !== '' && $slide['fecha_fin'] < $slide['fecha_inicio']) {
        $errors['fecha_fin'] = 'La fecha final es anterior a la inicial.';
    }

    // 3. Validar la imagen (obligatoria al agregar; al editar solo si se elige otra)
    $file    = $_FILES['imagen'] ?? null;
    $hasFile = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;
    $ext     = '';

    if (!$hasFile) {
        if (!$isEditing) {
            $errors['imagen'] = 'Selecciona una imagen.';
        }
    } elseif (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $file['size'] > $maxSize) {
        $errors['imagen'] = 'La imagen pesa más de 1 MB.';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $errors['imagen'] = 'No se pudo subir el archivo.';
    } else {
        // Extensión y tipo MIME real del archivo temporal
        $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($ext, $extAllowed, true) || !in_array($mime, $mimeAllowed, true)) {
            $errors['imagen'] = 'Formato no válido (JPG, PNG o WebP).';
        }
    }

    // 4. Límite de slides visibles
    if ($slide['visible']) {
        $sql  = "SELECT COUNT(*) FROM tblweb_carrusel WHERE visible = 1 AND id_carrusel <> ?";
        $stmt = mysqli_prepare($conexion, $sql);
        $currentId = $isEditing ? (int) $id : 0;
        mysqli_stmt_bind_param($stmt, "i", $currentId);
        mysqli_stmt_execute($stmt);
        $visibleCount = (int) mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];
        mysqli_stmt_close($stmt);

        if ($visibleCount >= $maxVisible) {
            $error = "Ya hay $maxVisible slides visibles. Oculta uno antes o guarda este como oculto.";
        }
    }

    // 5. Si todo está bien, guardar
    if (!$errors && $error === '') {
        $newName = '';

        if ($hasFile) {
            $newName = uniqid('banner_', true) . '.' . $ext;
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                $error = 'No se pudo guardar la imagen.';
            }
        }

        if ($error === '') {
            $imageName = $newName !== '' ? $newName : $slide['imagen'];
            // Los campos opcionales vacíos se guardan como NULL
            $optional = fn ($value) => $value === '' ? null : $value;
            $etiqueta = $optional($slide['etiqueta']);       $descripcion = $optional($slide['descripcion']);
            $botonTexto = $optional($slide['boton_texto']);  $botonEnlace = $optional($slide['boton_enlace']);
            $boton2Texto = $optional($slide['boton2_texto']); $boton2Enlace = $optional($slide['boton2_enlace']);
            $fechaInicio = $optional($slide['fecha_inicio']); $fechaFin = $optional($slide['fecha_fin']);

            try {
                if ($isEditing) {
                    $sql  = "UPDATE tblweb_carrusel SET etiqueta = ?, titulo = ?, descripcion = ?, boton_texto = ?, boton_enlace = ?,
                                    boton2_texto = ?, boton2_enlace = ?, imagen = ?, imagen_alt = ?, imagen_credito = ?,
                                    fecha_inicio = ?, fecha_fin = ?, orden = ?, visible = ?
                             WHERE id_carrusel = ?";
                    $stmt = mysqli_prepare($conexion, $sql);
                    mysqli_stmt_bind_param($stmt, "ssssssssssssiii", $etiqueta, $slide['titulo'], $descripcion, $botonTexto, $botonEnlace,
                        $boton2Texto, $boton2Enlace, $imageName, $slide['imagen_alt'], $slide['imagen_credito'],
                        $fechaInicio, $fechaFin, $slide['orden'], $slide['visible'], $id);
                } else {
                    $sql  = "INSERT INTO tblweb_carrusel (etiqueta, titulo, descripcion, boton_texto, boton_enlace, boton2_texto, boton2_enlace,
                                    imagen, imagen_alt, imagen_credito, fecha_inicio, fecha_fin, orden, visible)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conexion, $sql);
                    mysqli_stmt_bind_param($stmt, "ssssssssssssii", $etiqueta, $slide['titulo'], $descripcion, $botonTexto, $botonEnlace,
                        $boton2Texto, $boton2Enlace, $imageName, $slide['imagen_alt'], $slide['imagen_credito'],
                        $fechaInicio, $fechaFin, $slide['orden'], $slide['visible']);
                }
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Al cambiar la imagen se borra la anterior para no dejar archivos sin usar
                if ($isEditing && $newName !== '' && $found['imagen']) {
                    $oldPath = $uploadDir . basename($found['imagen']);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : 'Slide agregado.'];
                header('Location: index.php');
                exit;
            } catch (mysqli_sql_exception $exception) {
                error_log('carrusel: ' . $exception->getMessage());
                if ($newName !== '' && file_exists($uploadDir . $newName)) {
                    unlink($uploadDir . $newName);
                }
                $error = 'No se pudo guardar. Intenta de nuevo.';
            }
        }
    }
}

$base      = '../../../';
$title     = ($isEditing ? 'Editar slide' : 'Agregar slide') . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'carrusel';

require __DIR__ . '/../../../includes/head.php';
require __DIR__ . '/../../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar slide' : 'Agregar slide' ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="formulario.php<?= $isEditing ? '?id=' . (int) $id : '' ?>" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Texto del slide</h2>
            <div class="row g-3">

              <div class="col-md-4">
                <label class="form-label" for="etiqueta">Etiqueta</label>
                <input class="form-control" id="etiqueta" name="etiqueta" type="text" maxlength="60" value="<?= e($slide['etiqueta']) ?>">
                <div class="form-text">Texto corto arriba del título, por ejemplo «Nuevo ingreso».</div>
              </div>

              <div class="col-md-8">
                <label class="form-label" for="titulo">Título *</label>
                <input class="form-control<?= isset($errors['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" type="text" minlength="3" maxlength="150" value="<?= e($slide['titulo']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['titulo'] ?? 'Título: de 3 a 150 caracteres.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="descripcion">Descripción</label>
                <input class="form-control" id="descripcion" name="descripcion" type="text" maxlength="255" value="<?= e($slide['descripcion']) ?>">
                <div class="form-text">Una frase corta. Máximo 255 caracteres.</div>
              </div>

<?php foreach (['boton' => 'Botón principal', 'boton2' => 'Botón secundario'] as $prefix => $label): ?>
              <div class="col-md-4">
                <label class="form-label" for="<?= $prefix ?>_texto"><?= $label ?> (texto)</label>
                <input class="form-control<?= isset($errors[$prefix . '_texto']) ? ' is-invalid' : '' ?>" id="<?= $prefix ?>_texto" name="<?= $prefix ?>_texto" type="text" maxlength="40" value="<?= e($slide[$prefix . '_texto']) ?>">
                <div class="invalid-feedback"><?= e($errors[$prefix . '_texto'] ?? '') ?></div>
              </div>
              <div class="col-md-8">
                <label class="form-label" for="<?= $prefix ?>_enlace"><?= $label ?> (enlace)</label>
                <input class="form-control<?= isset($errors[$prefix . '_enlace']) ? ' is-invalid' : '' ?>" id="<?= $prefix ?>_enlace" name="<?= $prefix ?>_enlace" type="text" maxlength="120" pattern="(https?:\/\/\S+|[\w\/\-]+\.php\S*|#\S*)" placeholder="pages/nosotros.php" value="<?= e($slide[$prefix . '_enlace']) ?>">
                <div class="invalid-feedback"><?= e($errors[$prefix . '_enlace'] ?? 'Enlace no válido.') ?></div>
              </div>
<?php endforeach; ?>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="image-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="image-title">Imagen</h2>
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label" for="imagen">Imagen<?= $isEditing ? '' : ' *' ?></label>
<?php if ($isEditing && $slide['imagen']): ?>
                <img class="d-block rounded mb-2" src="<?= e($base . 'uploads/carrusel/' . $slide['imagen']) ?>" width="192" height="108" alt="Imagen actual del slide">
<?php endif; ?>
                <input class="form-control<?= isset($errors['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="1"<?= $isEditing ? '' : ' required' ?>>
                <div class="form-text">Formatos: JPG, PNG o WebP. Máximo 1 MB.<?= $isEditing ? ' Déjala vacía para conservar la actual.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['imagen'] ?? 'Selecciona una imagen.') ?></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="imagen_alt">Descripción de la imagen (texto alternativo) *</label>
                <input class="form-control<?= isset($errors['imagen_alt']) ? ' is-invalid' : '' ?>" id="imagen_alt" name="imagen_alt" type="text" minlength="8" maxlength="150" value="<?= e($slide['imagen_alt']) ?>" required>
                <div class="form-text">Describe qué se ve, para personas que usan lector de pantalla.</div>
                <div class="invalid-feedback"><?= e($errors['imagen_alt'] ?? 'Describe la imagen (mínimo 8 caracteres).') ?></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="imagen_credito">Crédito *</label>
                <input class="form-control<?= isset($errors['imagen_credito']) ? ' is-invalid' : '' ?>" id="imagen_credito" name="imagen_credito" type="text" maxlength="120" value="<?= e($slide['imagen_credito']) ?>" required>
                <div class="form-text">Quién tomó la foto.</div>
                <div class="invalid-feedback"><?= e($errors['imagen_credito'] ?? 'Escribe el autor de la foto.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="publish-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="publish-title">Publicación</h2>
            <div class="row g-3">

              <div class="col-md-4">
                <label class="form-label" for="fecha_inicio">Mostrar desde</label>
                <input class="form-control" id="fecha_inicio" name="fecha_inicio" type="date" value="<?= e($slide['fecha_inicio']) ?>">
                <div class="form-text">Opcional.</div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="fecha_fin">Mostrar hasta</label>
                <input class="form-control<?= isset($errors['fecha_fin']) ? ' is-invalid' : '' ?>" id="fecha_fin" name="fecha_fin" type="date" value="<?= e($slide['fecha_fin']) ?>">
                <div class="form-text">Opcional.</div>
                <div class="invalid-feedback"><?= e($errors['fecha_fin'] ?? '') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="orden">Orden</label>
                <input class="form-control" id="orden" name="orden" type="number" min="0" max="999" value="<?= (int) $slide['orden'] ?>">
                <div class="form-text">El número más bajo sale primero.</div>
              </div>

              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" id="visible" name="visible" type="checkbox" value="1"<?= $slide['visible'] ? ' checked' : '' ?>>
                  <label class="form-check-label" for="visible">Visible en el inicio</label>
                </div>
              </div>

            </div>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-3">
          <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Agregar slide' ?></button>
          <a class="btn btn-outline-primary" href="index.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../../includes/panel-layout-end.php'; ?>
