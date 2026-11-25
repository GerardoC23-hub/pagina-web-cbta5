<?php
$base        = '';
$pagina      = 'accesibilidad';
$title       = 'Accesibilidad · CBTa No. 5';
$description = 'Declaración de accesibilidad del sitio del CBTa No. 5 y cómo reportar una barrera.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">
    <div class="col-lg-8 mx-auto">

      <!-- Encabezado de la página -->
      <h1 class="mb-2">Declaración de accesibilidad</h1>
      <p class="lead text-body-secondary mb-4">Este sitio busca cumplir el nivel AA de las pautas WCAG 2.1.</p>

      <!-- Lo que hace el sitio -->
      <ul class="mb-4">
        <li class="mb-2">Contraste mínimo de 4.5:1 en textos normales.</li>
        <li class="mb-2">Se puede usar solo con teclado y con lector de pantalla; hay un enlace para saltar al contenido.</li>
        <li class="mb-2">Las imágenes llevan texto alternativo.</li>
        <li class="mb-2">Botones y campos miden al menos 48 px de alto.</li>
        <li class="mb-2">El texto se puede ampliar sin perder contenido.</li>
        <li class="mb-2">Las animaciones se reducen si tu sistema lo pide.</li>
      </ul>

      <!-- Cómo reportar un problema -->
      <p class="mb-0">¿Encontraste una barrera? Cuéntanos desde el <a href="<?= $base ?>contacto.php">formulario de contacto</a>.</p>

    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
