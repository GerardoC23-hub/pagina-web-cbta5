<?php
// Datos de la página de contacto: deja las variables listas para la vista.
// Deja: $mapSrc, $subjects, $errors, $error, $data, $sentFolio
// Requiere que la sesión ya esté iniciada.

require_once __DIR__ . '/../../includes/site-data.php';

// 1. Mapa: se arma con el enlace de Google Maps que se guarda en el panel (sin enlace válido, no se muestra)
$mapLink = siteContact()['address']['link'];
$mapSrc  = '';

if (preg_match('#^https://(www\.)?google\.[a-z.]+/maps#i', $mapLink)) {
  $mapSrc = strpos($mapLink, 'output=embed') !== false ? $mapLink : $mapLink . (strpos($mapLink, '?') === false ? '?' : '&') . 'output=embed';
}

// 2. Asuntos del formulario: son los valores del ENUM de la columna asunto
$subjects = enumValues($conexion, 'tblweb_mensajes', 'asunto');

// 3. Resultado del envío anterior (se usa una sola vez): errores, lo que escribió o el folio
$errors    = $_SESSION['contact_errors'] ?? [];
$error     = $_SESSION['contact_error'] ?? '';
$data      = $_SESSION['contact_old'] ?? ['name' => '', 'email' => '', 'subject' => '', 'message' => '', 'notice' => ''];
$sentFolio = $_SESSION['contact_folio'] ?? '';

unset($_SESSION['contact_errors'], $_SESSION['contact_error'], $_SESSION['contact_old'], $_SESSION['contact_folio']);
