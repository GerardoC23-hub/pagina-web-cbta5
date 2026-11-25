<?php
require __DIR__ . '/../includes/panel-auth.php';
panel_require_login();

$base      = '../';
$title     = 'Inicio · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$panelPage = 'inicio';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/panel-layout-start.php';
?>

      <section class="rounded p-4 mb-4 text-white bg-brand border-bottom border-4 border-warning-subtle" aria-labelledby="welcome-title">
        <h1 class="h3 fw-bold mb-1" id="welcome-title">Bienvenido, <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="mb-0 text-gold">Usa el menú de la izquierda para administrar el sitio. Tu rol: <?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>.</p>
      </section>

<?php require __DIR__ . '/../includes/panel-layout-end.php'; ?>
