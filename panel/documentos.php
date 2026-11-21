<?php
require __DIR__ . '/../includes/panel-auth.php';
panel_require_login();

$base      = '../';
$title     = 'Documentos y materiales · Panel de administración';
$styles    = ['pages/panel.css'];
$withSite  = false;
$noindex   = true;
$panelPage = 'documentos';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Documentos y materiales</h1>
      <p class="text-secondary mb-4">Oficiales, materiales educativos y exclusivos de alumnos.</p>

      <!-- ===== MÓDULO: Documentos y materiales · responsable: Javier =====
           Aquí va el diseño de esta página (lista, formulario, filtros...).
           Reemplaza el bloque "alert alert-secondary" por tu diseño (usa Bootstrap y muy poco CSS).
           Usa tus propios datos de ejemplo; no dependas de otro módulo. -->
      <section class="alert alert-secondary" aria-label="Pendiente de diseño">
        <p class="mb-0">Pendiente de diseño · responsable: Javier.</p>
      </section>

<?php require __DIR__ . '/../includes/panel-layout-end.php'; ?>
