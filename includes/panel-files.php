<?php
// Imágenes que se suben desde el panel: revisar, guardar y borrar.
// La base guarda solo la ruta relativa a la raíz del sitio (por ejemplo uploads/personal/personal-a1b2c3d4.jpg).

// Revisa la imagen subida: peso, extensión y que sea una imagen de verdad.
// Regresa el mensaje de error, o '' si está bien.
function imageError(array $file, int $maxMb = 1): string
{
  if (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $file['size'] > $maxMb * 1024 * 1024) {
    return "La imagen pesa más de $maxMb MB.";
  }
  if ($file['error'] !== UPLOAD_ERR_OK) {
    return 'No se pudo subir la imagen.';
  }

  $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $info = @getimagesize($file['tmp_name']);

  if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || !$info || !in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'], true)) {
    return 'Formato no válido (JPG, PNG o WebP).';
  }
  return '';
}

// Guarda la imagen en uploads/<carpeta>/ con un nombre propio (nunca el original).
// Regresa la ruta para la base, o '' si no se pudo guardar.
function saveImage(array $file, string $folder, string $prefix): string
{
  $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
  $info       = getimagesize($file['tmp_name']);
  $dir        = __DIR__ . '/../uploads/' . $folder . '/';

  if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
  }

  $path = 'uploads/' . $folder . '/' . $prefix . '-' . bin2hex(random_bytes(4)) . '.' . $extensions[$info['mime']];

  return move_uploaded_file($file['tmp_name'], __DIR__ . '/../' . $path) ? $path : '';
}

// Borra un archivo guardado en uploads/ (si existe)
function deleteImage(?string $path): void
{
  if (!$path || strpos($path, 'uploads/') !== 0 || strpos($path, '..') !== false) {
    return;
  }
  $full = __DIR__ . '/../' . $path;
  if (is_file($full)) {
    unlink($full);
  }
}
