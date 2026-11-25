<?php
require __DIR__ . '/../../../includes/panel-auth.php';
panel_require_login();

require_once __DIR__ . '/../../../config/conexion.php';

$maxImage    = 2 * 1024 * 1024;    // 2 MB
$maxVideo    = 10 * 1024 * 1024;   // 10 MB
$videoHosts  = ['youtube.com', 'www.youtube.com', 'youtu.be', 'vimeo.com', 'player.vimeo.com'];
$siteRoot    = realpath(__DIR__ . '/../../..');

// Con ?id se edita; sin id se agrega y ?tipo dice si es Foto o Video
$id        = $_GET['id'] ?? $_POST['id'] ?? null;
$isEditing = $id !== null && is_numeric($id);

$media = [
    'id_album' => '', 'titulo' => '', 'descripcion' => '', 'texto_alt' => '', 'tipo' => 'Foto',
    'archivo' => '', 'miniatura' => '', 'credito' => '', 'fecha' => '', 'orden' => 0, 'visible' => 1,
];

if ($isEditing) {
    $sql  = "SELECT * FROM tblweb_galeria WHERE id_galeria = ?";
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
    $media = array_map(fn ($value) => $value ?? '', $found);
} else {
    $media['tipo'] = ($_GET['tipo'] ?? $_POST['tipo'] ?? 'Foto') === 'Video' ? 'Video' : 'Foto';
}
$isPhoto = $media['tipo'] === 'Foto';
$tab     = $isPhoto ? 'photos' : 'videos';

$albums = mysqli_query($conexion, "SELECT id_album, nombre FROM tblweb_albumes ORDER BY orden, nombre");
$errors = [];
$error  = '';

// Texto en minúsculas, sin acentos ni espacios, para el nombre del archivo
function slug(string $text): string
{
    $text = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $text)), '-'));
    return $text !== '' ? substr($text, 0, 40) : 'archivo';
}

// Revisa un archivo subido y lo guarda en uploads/galeria/$folder/. Devuelve la ruta relativa a la raíz del sitio,
// o '' si no se subió nada (si algo falla, agrega el mensaje a $errors).
function saveFile(string $field, string $folder, string $name, int $maxSize, array $types, array &$errors): string
{
    global $siteRoot;
    $file = $_FILES[$field] ?? null;
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = $file['error'] === UPLOAD_ERR_OK ? (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) : '';

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > $maxSize) {
        $errors[$field] = 'No se pudo subir o pesa más de ' . ($maxSize / 1024 / 1024) . ' MB.';
    } elseif (!isset($types[$ext]) || $types[$ext] !== $mime) {
        $errors[$field] = 'Formato no permitido: ' . implode(', ', array_map('strtoupper', array_keys($types))) . '.';
    } elseif ($types[$ext] !== 'video/mp4' && getimagesize($file['tmp_name']) === false) {
        $errors[$field] = 'No es una imagen válida.';
    } else {
        $dir = $siteRoot . '/uploads/galeria/' . $folder . '/';
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $newName = slug($name) . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $dir . $newName)) {
            return 'uploads/galeria/' . $folder . '/' . $newName;
        }
        $errors[$field] = 'No se pudo guardar el archivo.';
    }
    return '';
}

// Borra un archivo guardado, solo si está dentro de uploads/galeria (los enlaces de video no se tocan)
function deleteFile(?string $path): void
{
    global $siteRoot;
    if (!$path || strpos($path, 'uploads/galeria/') !== 0) {
        return;
    }
    $full = realpath($siteRoot . '/' . $path);
    if ($full && strpos(str_replace('\\', '/', $full), str_replace('\\', '/', $siteRoot) . '/uploads/galeria/') === 0 && is_file($full)) {
        unlink($full);
    }
}

$imageTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
$videoTypes = ['mp4' => 'video/mp4'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recibir y limpiar los datos
    foreach (['titulo', 'descripcion', 'texto_alt', 'credito', 'fecha'] as $field) {
        $media[$field] = trim($_POST[$field] ?? '');
    }
    $media['id_album'] = is_numeric($_POST['id_album'] ?? '') ? (int) $_POST['id_album'] : '';
    $media['orden']    = is_numeric($_POST['orden'] ?? '') ? (int) $_POST['orden'] : 0;
    $media['visible']  = isset($_POST['visible']) ? 1 : 0;
    $videoUrl         = trim($_POST['video_url'] ?? '');

    // 2. Validar los textos
    if (mb_strlen($media['titulo']) < 3 || mb_strlen($media['titulo']) > 120) {
        $errors['titulo'] = 'Título: de 3 a 120 caracteres.';
    }
    if (mb_strlen($media['descripcion']) > 255) {
        $errors['descripcion'] = 'Máximo 255 caracteres.';
    }
    if ($isPhoto && (mb_strlen($media['texto_alt']) < 8 || mb_strlen($media['texto_alt']) > 150)) {
        $errors['texto_alt'] = 'Describe la foto (de 8 a 150 caracteres).';
    }
    if (mb_strlen($media['credito']) > 120) {
        $errors['credito'] = 'Máximo 120 caracteres.';
    }
    if ($media['fecha'] !== '' && !DateTime::createFromFormat('Y-m-d', $media['fecha'])) {
        $errors['fecha'] = 'Fecha no válida.';
    }
    if ($media['id_album'] !== '') {
        $sql  = "SELECT id_album FROM tblweb_albumes WHERE id_album = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $media['id_album']);
        mysqli_stmt_execute($stmt);
        if (!mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
            $errors['id_album'] = 'Álbum no válido.';
        }
        mysqli_stmt_close($stmt);
    }
    $hasVideoFile = ($_FILES['video']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
    if (!$isPhoto) {
        if ($videoUrl !== '') {
            $host = strtolower((string) parse_url($videoUrl, PHP_URL_HOST));
            if (!preg_match('#^https://#i', $videoUrl) || !in_array($host, $videoHosts, true) || mb_strlen($videoUrl) > 255) {
                $errors['video_url'] = 'Enlace de YouTube o Vimeo no válido.';
            }
        } elseif (!$hasVideoFile && !($isEditing && $media['archivo'] !== '')) {
            $errors['video_url'] = 'Escribe el enlace o sube un MP4.';
        }
    }

    // 3. Subir los archivos (foto, o portada y video)
    $newMain  = '';
    $newThumb = '';
    if (!$errors) {
        if ($isPhoto) {
            $newMain = saveFile('imagen', 'fotos', $media['titulo'], $maxImage, $imageTypes, $errors);
            if ($newMain === '' && !$isEditing && !isset($errors['imagen'])) {
                $errors['imagen'] = 'Selecciona una imagen.';
            }
        } else {
            $newThumb = saveFile('miniatura', 'miniaturas', $media['titulo'], $maxImage, $imageTypes, $errors);
            if ($hasVideoFile && $videoUrl === '') {
                $newMain = saveFile('video', 'videos', $media['titulo'], $maxVideo, $videoTypes, $errors);
            }
        }
    }

    // 4. Guardar en la base de datos
    if (!$errors) {
        if ($isPhoto) {
            $archivo   = $newMain !== '' ? $newMain : $media['archivo'];
            $miniatura = null;
        } else {
            // Video: enlace escrito, archivo MP4 subido, o lo que ya tenía
            $archivo   = $videoUrl !== '' ? $videoUrl : ($newMain !== '' ? $newMain : $media['archivo']);
            $miniatura = $newThumb !== '' ? $newThumb : ($media['miniatura'] ?: null);
        }
        // Los campos opcionales vacíos se guardan como NULL
        $albumId     = $media['id_album'] === '' ? null : $media['id_album'];
        $descripcion = $media['descripcion'] === '' ? null : $media['descripcion'];
        $textoAlt    = $media['texto_alt'] === '' ? null : $media['texto_alt'];
        $credito     = $media['credito'] === '' ? null : $media['credito'];
        $fecha       = $media['fecha'] === '' ? null : $media['fecha'];
        $userId      = $_SESSION['panel_user']['id'];

        try {
            if ($isEditing) {
                $sql  = "UPDATE tblweb_galeria SET id_album = ?, titulo = ?, descripcion = ?, texto_alt = ?, archivo = ?, miniatura = ?,
                                credito = ?, fecha = ?, orden = ?, visible = ?
                         WHERE id_galeria = ?";
                $stmt = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($stmt, "isssssssiii", $albumId, $media['titulo'], $descripcion, $textoAlt, $archivo, $miniatura,
                    $credito, $fecha, $media['orden'], $media['visible'], $id);
            } else {
                $sql  = "INSERT INTO tblweb_galeria (id_album, titulo, descripcion, texto_alt, tipo, archivo, miniatura, credito, fecha, orden, visible, id_usuario)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($stmt, "issssssssiii", $albumId, $media['titulo'], $descripcion, $textoAlt, $media['tipo'], $archivo, $miniatura,
                    $credito, $fecha, $media['orden'], $media['visible'], $userId);
            }
            mysqli_stmt_execute($stmt);
            $savedId = $isEditing ? (int) $id : mysqli_insert_id($conexion);
            mysqli_stmt_close($stmt);

            // Al cambiar un archivo se borra el anterior para no dejar archivos sin usar
            if ($isEditing) {
                if ($isPhoto && $newMain !== '') {
                    deleteFile($found['archivo']);
                }
                if (!$isPhoto && $newThumb !== '') {
                    deleteFile($found['miniatura']);
                }
                if (!$isPhoto && $archivo !== $found['archivo']) {
                    deleteFile($found['archivo']);
                }
            }

            panel_log($conexion, $isEditing ? 'Cambio' : 'Alta', 'tblweb_galeria', $savedId, ($isPhoto ? 'Foto: ' : 'Video: ') . $media['titulo']);
            $_SESSION['flash'] = ['type' => 'success', 'text' => $isEditing ? 'Cambios guardados.' : ($isPhoto ? 'Foto agregada.' : 'Video agregado.')];
            header('Location: index.php?t=' . $tab);
            exit;
        } catch (mysqli_sql_exception $exception) {
            error_log('galeria: ' . $exception->getMessage());
            // Si no se pudo guardar, se borra la imagen recién subida
            deleteFile($newMain);
            deleteFile($newThumb);
            $error = 'No se pudo guardar. Intenta de nuevo.';
        }
    }
}

$label     = $isPhoto ? 'foto' : 'video';
$base      = '../../../';
$title     = ($isEditing ? 'Editar ' : 'Agregar ') . $label . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'galeria';

require __DIR__ . '/../../../includes/head.php';
require __DIR__ . '/../../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar' : 'Agregar' ?> <?= $label ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="formulario.php<?= $isEditing ? '?id=' . (int) $id : '?tipo=' . $media['tipo'] ?>" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php else: ?>
        <input type="hidden" name="tipo" value="<?= e($media['tipo']) ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="content-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="content-title">Datos de <?= $isPhoto ? 'la foto' : 'el video' ?></h2>
            <div class="row g-3">

              <div class="col-md-8">
                <label class="form-label" for="titulo">Título *</label>
                <input class="form-control<?= isset($errors['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" type="text" minlength="3" maxlength="120" value="<?= e($media['titulo']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['titulo'] ?? 'Título: de 3 a 120 caracteres.') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="id_album">Álbum</label>
                <select class="form-select<?= isset($errors['id_album']) ? ' is-invalid' : '' ?>" id="id_album" name="id_album">
                  <option value="">Sin álbum</option>
<?php while ($album = mysqli_fetch_assoc($albums)): ?>
                  <option value="<?= (int) $album['id_album'] ?>"<?= (string) $media['id_album'] === (string) $album['id_album'] ? ' selected' : '' ?>><?= e($album['nombre']) ?></option>
<?php endwhile; ?>
                </select>
                <div class="invalid-feedback"><?= e($errors['id_album'] ?? '') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="descripcion">Descripción</label>
                <input class="form-control<?= isset($errors['descripcion']) ? ' is-invalid' : '' ?>" id="descripcion" name="descripcion" type="text" maxlength="255" value="<?= e($media['descripcion']) ?>">
                <div class="form-text">Texto que se muestra debajo. Máximo 255 caracteres.</div>
                <div class="invalid-feedback"><?= e($errors['descripcion'] ?? '') ?></div>
              </div>

<?php if ($isPhoto): ?>
              <div class="col-12">
                <label class="form-label" for="texto_alt">Texto alternativo *</label>
                <input class="form-control<?= isset($errors['texto_alt']) ? ' is-invalid' : '' ?>" id="texto_alt" name="texto_alt" type="text" minlength="8" maxlength="150" value="<?= e($media['texto_alt']) ?>" required>
                <div class="form-text">Describe lo que se ve, para quien usa lector de pantalla.</div>
                <div class="invalid-feedback"><?= e($errors['texto_alt'] ?? 'Describe la foto (de 8 a 150 caracteres).') ?></div>
              </div>
<?php else: ?>
              <div class="col-12">
                <label class="form-label" for="video_url">Enlace del video (YouTube o Vimeo)</label>
                <input class="form-control<?= isset($errors['video_url']) ? ' is-invalid' : '' ?>" id="video_url" name="video_url" type="url" maxlength="255" pattern="https://(www\.|player\.)?(youtube\.com|youtu\.be|vimeo\.com)/\S+" placeholder="https://www.youtube.com/watch?v=…" value="<?= e($_POST['video_url'] ?? (strpos($media['archivo'], 'https://') === 0 ? $media['archivo'] : '')) ?>">
                <div class="invalid-feedback"><?= e($errors['video_url'] ?? 'Enlace de YouTube o Vimeo no válido.') ?></div>
              </div>
              <div class="col-12">
                <label class="form-label" for="video">…o sube un archivo MP4</label>
                <input class="form-control<?= isset($errors['video']) ? ' is-invalid' : '' ?>" id="video" name="video" type="file" accept="video/mp4" data-max-mb="10">
                <div class="form-text">Máximo 10 MB. Si escribes un enlace, se usa el enlace.<?= $isEditing && $media['archivo'] !== '' && strpos($media['archivo'], 'https://') !== 0 ? ' Ahora tiene un archivo subido; déjalo vacío para conservarlo.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['video'] ?? '') ?></div>
              </div>
<?php endif; ?>

              <div class="col-md-6">
                <label class="form-label" for="credito">Crédito</label>
                <input class="form-control<?= isset($errors['credito']) ? ' is-invalid' : '' ?>" id="credito" name="credito" type="text" maxlength="120" value="<?= e($media['credito']) ?>">
                <div class="invalid-feedback"><?= e($errors['credito'] ?? '') ?></div>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="fecha">Fecha</label>
                <input class="form-control<?= isset($errors['fecha']) ? ' is-invalid' : '' ?>" id="fecha" name="fecha" type="date" value="<?= e($media['fecha']) ?>">
                <div class="invalid-feedback"><?= e($errors['fecha'] ?? '') ?></div>
              </div>
              <div class="col-md-2">
                <label class="form-label" for="orden">Orden</label>
                <input class="form-control" id="orden" name="orden" type="number" min="0" max="999" value="<?= (int) $media['orden'] ?>">
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="image-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="image-title"><?= $isPhoto ? 'Imagen' : 'Portada del video' ?></h2>
<?php
$field   = $isPhoto ? 'imagen' : 'miniatura';
$current = $isPhoto ? $media['archivo'] : $media['miniatura'];
?>
<?php if ($isEditing && $current): ?>
            <img class="d-block rounded mb-2" src="<?= e($base . $current) ?>" width="192" height="128" alt="Imagen actual">
<?php endif; ?>
            <label class="form-label" for="<?= $field ?>"><?= $isPhoto ? 'Imagen' . ($isEditing ? '' : ' *') : 'Portada (opcional)' ?></label>
            <input class="form-control<?= isset($errors[$field]) ? ' is-invalid' : '' ?>" id="<?= $field ?>" name="<?= $field ?>" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="2"<?= $isPhoto && !$isEditing ? ' required' : '' ?>>
            <div class="form-text">Formatos: JPG, PNG o WebP. Máximo 2 MB.<?= $isEditing ? ' Déjala vacía para conservar la actual.' : '' ?></div>
            <div class="invalid-feedback"><?= e($errors[$field] ?? 'Selecciona una imagen.') ?></div>
          </div>
        </section>

        <div class="form-check form-switch mb-4">
          <input class="form-check-input" id="visible" name="visible" type="checkbox" role="switch"<?= $media['visible'] ? ' checked' : '' ?>>
          <label class="form-check-label" for="visible">Publicado (se ve en la galería del sitio)</label>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-primary" type="submit">Guardar</button>
          <a class="btn btn-outline-primary" href="index.php?t=<?= $tab ?>">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../../includes/panel-layout-end.php'; ?>
