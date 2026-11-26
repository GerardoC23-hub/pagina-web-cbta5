<?php
$base        = '';
$pagina      = 'terminos';
$title       = 'Términos y condiciones · CBTa No. 5';
$description = 'Condiciones de uso del sitio del CBTa No. 5: cuenta personal, uso adecuado, contenido y disponibilidad.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">
    <div class="col-lg-8 mx-auto">

      <!-- Encabezado de la página -->
      <h1 class="mb-2">Términos y condiciones</h1>
      <p class="small text-body-secondary mb-4">Última actualización: noviembre de 2026</p>

      <!-- Índice -->
      <nav class="mb-5" aria-label="Índice de esta página">
        <h2 class="h5">Contenido</h2>
        <ol class="mb-0">
          <li><a href="#uso">Uso del sitio</a></li>
          <li><a href="#cuenta">Tu cuenta</a></li>
          <li><a href="#uso-adecuado">Uso adecuado</a></li>
          <li><a href="#contenido">Contenido</a></li>
          <li><a href="#disponibilidad">Disponibilidad</a></li>
          <li><a href="#datos">Datos personales</a></li>
          <li><a href="#cambios">Cambios</a></li>
        </ol>
      </nav>

      <!-- Apartados -->
      <section class="mb-4" id="uso">
        <h2 class="h4">1. Uso del sitio</h2>
        <p>Este sitio informa sobre el CBTa No. 5 y ofrece servicios en línea a alumnos y personal. Al usarlo aceptas estas condiciones.</p>
      </section>

      <section class="mb-4" id="cuenta">
        <h2 class="h4">2. Tu cuenta</h2>
        <p>Tu matrícula o usuario y tu contraseña son personales. No los compartas. Eres responsable de lo que ocurra con tu cuenta. Si crees que alguien más la usa, cambia tu contraseña y avisa al plantel.</p>
      </section>

      <section class="mb-4" id="uso-adecuado">
        <h2 class="h4">3. Uso adecuado</h2>
        <p>No está permitido intentar entrar a cuentas ajenas, alterar el sitio ni subir contenido ofensivo o que no te corresponda.</p>
      </section>

      <section class="mb-4" id="contenido">
        <h2 class="h4">4. Contenido</h2>
        <p>Los textos, imágenes y documentos del sitio pertenecen al CBTa No. 5 o se publican con crédito a su autor. No se pueden usar con fines comerciales sin autorización.</p>
      </section>

      <section class="mb-4" id="disponibilidad">
        <h2 class="h4">5. Disponibilidad</h2>
        <p>El plantel procura que el sitio funcione de forma continua, pero puede suspenderlo por mantenimiento sin previo aviso.</p>
      </section>

      <section class="mb-4" id="datos">
        <h2 class="h4">6. Datos personales</h2>
        <p>El tratamiento de tus datos se describe en el <a href="<?= $base ?>privacidad.php">Aviso de privacidad</a>.</p>
      </section>

      <section class="mb-4" id="cambios">
        <h2 class="h4">7. Cambios</h2>
        <p>Estas condiciones pueden cambiar. La versión vigente es la publicada en esta página.</p>
      </section>

    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
