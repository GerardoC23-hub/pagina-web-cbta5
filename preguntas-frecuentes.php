<?php
$base        = '';
$pagina      = 'preguntas-frecuentes';
$title       = 'Preguntas frecuentes · CBTa No. 5';
$description = 'Respuestas a las dudas más comunes sobre el sitio del CBTa No. 5: acceso de alumnos, contraseña, inscripción, documentos y solicitudes.';
$styles      = ['pages/faq.css'];

// Preguntas y respuestas (el plantel puede ajustar los textos)
$faqs = [
    [
        'question' => '¿Cómo entro a la sección de alumnos?',
        'answer'   => 'Con tu matrícula de 8 dígitos y tu contraseña. En el primer acceso se te pide cambiar la contraseña.',
    ],
    [
        'question' => '¿Qué hago si olvidé mi contraseña?',
        'answer'   => 'En la pantalla de acceso elige «¿Olvidaste tu contraseña?», escribe tu correo y recibirás un enlace para crear una nueva. El enlace sirve una sola vez y vence en 30 minutos. Si no tienes correo registrado, pide a Servicios Escolares que restablezcan tu cuenta.',
    ],
    [
        'question' => '¿Por qué se bloqueó mi acceso?',
        'answer'   => 'Después de 5 intentos fallidos el acceso se bloquea durante 15 minutos. Pasado ese tiempo puedes intentar de nuevo.',
    ],
    [
        'question' => '¿Por qué se cerró mi sesión?',
        'answer'   => 'Por seguridad, la sesión se cierra después de 30 minutos sin actividad. Vuelve a iniciar sesión para continuar.',
    ],
    [
        'question' => '¿Cómo me inscribo al plantel?',
        'answer'   => 'En la sección «Nuevo ingreso» encontrarás los requisitos, las fechas y los pasos del proceso.',
    ],
    [
        'question' => '¿Dónde encuentro documentos y materiales educativos?',
        'answer'   => 'En las secciones «Documentos» y «Materiales educativos». Algunos materiales son exclusivos para alumnos y se ven al iniciar sesión.',
    ],
    [
        'question' => '¿Cómo hago una solicitud en línea?',
        'answer'   => 'En «Solicitudes en línea» elige el trámite y llena el formulario. Al enviarlo recibirás un folio para consultar el estado de tu solicitud.',
    ],
    [
        'question' => '¿Cómo contacto al plantel?',
        'answer'   => 'Los datos de contacto están en el pie de página de todas las páginas del sitio.',
    ],
    [
        'question' => '¿Cómo se usan mis datos personales?',
        'answer'   => 'Se explica en el Aviso de privacidad, que está enlazado en el pie de página.',
    ],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">
    <div class="col-lg-8 mx-auto">

      <!-- Encabezado de la página -->
      <h1 class="mb-2">Preguntas frecuentes</h1>
      <p class="lead text-body-secondary mb-4">Respuestas a las dudas más comunes.</p>

      <!-- Preguntas -->
      <div class="accordion" id="faq-list">
<?php foreach ($faqs as $index => $faq): ?>
        <div class="accordion-item mb-3 border rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?= $index + 1 ?>" aria-expanded="false" aria-controls="faq-<?= $index + 1 ?>">
              <?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?>
            </button>
          </h2>
          <div class="accordion-collapse collapse" id="faq-<?= $index + 1 ?>" data-bs-parent="#faq-list">
            <div class="accordion-body">
              <?= htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8') ?>
            </div>
          </div>
        </div>
<?php endforeach; ?>
      </div>

      <p class="text-body-secondary mt-4 mb-0">¿No encontraste tu respuesta? Usa los datos de contacto que aparecen al final de la página.</p>

    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
