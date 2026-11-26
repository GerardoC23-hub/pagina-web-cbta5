<?php
$base        = '';
$pagina      = 'privacidad';
$title       = 'Aviso de privacidad · CBTa No. 5';
$description = 'Aviso de privacidad del sitio del CBTa No. 5: qué datos se recogen, para qué se usan y cómo ejercer tus derechos.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">
    <div class="col-lg-8 mx-auto">

      <!-- Encabezado de la página -->
      <h1 class="mb-2">Aviso de privacidad</h1>
      <p class="small text-body-secondary mb-4">Última actualización: noviembre de 2026</p>
      <div class="alert alert-warning" role="note">
        Borrador pendiente de validación del plantel antes de publicarse.
      </div>

      <!-- Índice -->
      <nav class="mb-5" aria-label="Índice de esta página">
        <h2 class="h5">Contenido</h2>
        <ol class="mb-0">
          <li><a href="#responsable">Responsable</a></li>
          <li><a href="#datos">Datos que se recogen</a></li>
          <li><a href="#finalidades">Para qué se usan</a></li>
          <li><a href="#transferencias">Transferencias</a></li>
          <li><a href="#derechos">Tus derechos (ARCO)</a></li>
          <li><a href="#cookies">Cookies</a></li>
          <li><a href="#cambios">Cambios a este aviso</a></li>
        </ol>
      </nav>

      <!-- Apartados -->
      <section class="mb-4" id="responsable">
        <h2 class="h4">1. Responsable</h2>
        <p>El CBTa No. 5 es responsable del tratamiento de tus datos personales. Su domicilio es Boulevard Adolfo López Mateos S/N, Col. Aviación Civil, C.P. 43000, Huejutla de Reyes, Hidalgo.</p>
      </section>

      <section class="mb-4" id="datos">
        <h2 class="h4">2. Datos que se recogen</h2>
        <ul>
          <li>Alumnos: matrícula, contraseña (guardada cifrada) y correo.</li>
          <li>Personal del panel: usuario, contraseña (guardada cifrada) y correo.</li>
          <li>Formularios: lo que escribas en un mensaje o solicitud, como nombre, correo y matrícula si eres alumno.</li>
          <li>Seguridad: datos de la sesión y bitácora de acciones del panel.</li>
        </ul>
        <p>No se piden datos financieros ni datos sensibles.</p>
      </section>

      <section class="mb-4" id="finalidades">
        <h2 class="h4">3. Para qué se usan</h2>
        <ul>
          <li>Darte acceso a tu cuenta.</li>
          <li>Recuperar tu contraseña.</li>
          <li>Atender tus solicitudes y mensajes, y darles seguimiento con un folio.</li>
          <li>Cuidar la seguridad del sitio y llevar la bitácora.</li>
        </ul>
      </section>

      <section class="mb-4" id="transferencias">
        <h2 class="h4">4. Transferencias</h2>
        <p>Tus datos no se venden ni se ceden. Solo se entregan a una autoridad cuando una ley lo exige.</p>
      </section>

      <section class="mb-4" id="derechos">
        <h2 class="h4">5. Tus derechos (ARCO)</h2>
        <p>Puedes pedir acceso, rectificación, cancelación u oposición sobre tus datos. Escribe al correo de contacto del plantel con tu nombre y, si eres alumno, tu matrícula. El correo está en el pie de página.</p>
      </section>

      <section class="mb-4" id="cookies">
        <h2 class="h4">6. Cookies</h2>
        <p>El sitio solo usa la cookie técnica de la sesión. No usa cookies de publicidad ni de seguimiento.</p>
      </section>

      <section class="mb-4" id="cambios">
        <h2 class="h4">7. Cambios a este aviso</h2>
        <p>Si este aviso cambia, se publica aquí con su fecha de actualización.</p>
      </section>

    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
