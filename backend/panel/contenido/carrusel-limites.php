<?php
// Límites del carrusel (se usan en la lista, en el formulario y al guardar).

return [
  'maxVisible' => 5,                 // más slides visibles vuelve pesado el inicio
  'maxSize'    => 1 * 1024 * 1024,   // 1 MB por imagen
  'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
  'mimeTypes'  => ['image/jpeg', 'image/png', 'image/webp'],
  'folder'     => 'uploads/carrusel/',   // ruta relativa a la raíz del sitio
];
