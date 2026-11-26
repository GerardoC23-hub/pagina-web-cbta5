<?php
$base        = '';
$pagina      = 'terminos';
$legalKey    = 'legal_terminos';
$title       = 'Términos y condiciones · CBTa No. 5';
$description = 'Condiciones de uso del sitio del CBTa No. 5: cuenta personal, uso adecuado, contenido y disponibilidad.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/backend/publico/legal-datos.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

require __DIR__ . '/includes/legal-content.php';

require __DIR__ . '/includes/footer.php';
