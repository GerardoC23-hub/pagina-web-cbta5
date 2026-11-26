<?php
$base        = '';
$pagina      = 'privacidad';
$legalKey    = 'legal_privacidad';
$title       = 'Aviso de privacidad · CBTa No. 5';
$description = 'Aviso de privacidad del sitio del CBTa No. 5: qué datos se recogen, para qué se usan y cómo ejercer tus derechos.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/backend/publico/legal-datos.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

require __DIR__ . '/includes/legal-content.php';

require __DIR__ . '/includes/footer.php';
