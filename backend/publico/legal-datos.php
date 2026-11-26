<?php
// Datos de una página legal (privacidad, términos, accesibilidad): lee el texto de la base
// y lo deja dividido en secciones, listo para la vista.
// Antes de incluirlo la página define $legalKey (por ejemplo 'legal_privacidad') y $base.
// Deja: $legal (o null si no existe), $sections, $updated
//
// Formato del texto guardado:
//   ## Título            abre una sección
//   - texto              punto de una lista
//   línea en blanco      separa párrafos
//   [texto](enlace)      enlace (si no empieza con http, se toma desde la raíz del sitio)

require_once __DIR__ . '/../../includes/site-data.php';

// Texto → HTML seguro, con los enlaces [texto](enlace)
function legalInline(string $text, string $base): string
{
  return preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function ($match) use ($base) {
    $url = preg_match('#^https?://#i', $match[2]) ? $match[2] : $base . ltrim($match[2], '/');
    return '<a href="' . $url . '">' . $match[1] . '</a>';
  }, h($text));
}

// Texto guardado → lista de secciones, cada una con sus bloques (párrafos y listas)
function legalSections(string $text): array
{
  $sections = [['title' => '', 'id' => '', 'blocks' => []]];
  $current  = 0;
  $paragraph = [];

  $closeParagraph = function () use (&$paragraph, &$sections, &$current) {
    if ($paragraph) {
      $sections[$current]['blocks'][] = ['type' => 'p', 'text' => implode(' ', $paragraph)];
      $paragraph = [];
    }
  };

  foreach (preg_split('/\R/', trim($text)) as $line) {
    $line = trim($line);

    if (strpos($line, '## ') === 0) {
      $closeParagraph();
      $title      = trim(substr($line, 3));
      $sections[] = ['title' => $title, 'id' => slugify($title, 'seccion'), 'blocks' => []];
      $current    = count($sections) - 1;
    } elseif (strpos($line, '- ') === 0) {
      $closeParagraph();
      $last = count($sections[$current]['blocks']) - 1;

      if ($last >= 0 && $sections[$current]['blocks'][$last]['type'] === 'ul') {
        $sections[$current]['blocks'][$last]['items'][] = substr($line, 2);
      } else {
        $sections[$current]['blocks'][] = ['type' => 'ul', 'items' => [substr($line, 2)]];
      }
    } elseif ($line === '') {
      $closeParagraph();
    } else {
      $paragraph[] = $line;
    }
  }
  $closeParagraph();

  return $sections;
}

$stmt = mysqli_prepare($conexion, "SELECT titulo, contenido, actualizado_en FROM tblweb_secciones WHERE clave = ? AND grupo = 'Legal' AND visible = 1");
mysqli_stmt_bind_param($stmt, "s", $legalKey);
mysqli_stmt_execute($stmt);
$legal = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
mysqli_stmt_close($stmt);

$sections = $legal ? legalSections((string) $legal['contenido']) : [];
$updated  = $legal ? formatDateEs($legal['actualizado_en']) : '';
