<?php
$base        = '';
$pagina      = 'preguntas-frecuentes';
$title       = 'Preguntas frecuentes · CBTa No. 5';
$description = 'Respuestas a las dudas más comunes sobre el sitio del CBTa No. 5: acceso de alumnos, contraseña, inscripción, documentos y solicitudes.';
$styles      = ['pages/faq.css'];

// Datos que muestra la pantalla
require __DIR__ . '/backend/publico/preguntas-datos.php';

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
<?php if (!$faqs): ?>
      <div class="alert alert-info mb-0">Todavía no hay preguntas publicadas. Vuelve pronto.</div>
<?php else: ?>
      <div class="accordion" id="faq-list">
<?php foreach ($faqs as $index => $faq): ?>
        <div class="accordion-item mb-3 border rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?= $index + 1 ?>" aria-expanded="false" aria-controls="faq-<?= $index + 1 ?>">
              <?= h($faq['pregunta']) ?>
            </button>
          </h2>
          <div class="accordion-collapse collapse" id="faq-<?= $index + 1 ?>" data-bs-parent="#faq-list">
            <div class="accordion-body">
              <?= h($faq['respuesta']) ?>
            </div>
          </div>
        </div>
<?php endforeach; ?>
      </div>
<?php endif; ?>

      <p class="text-body-secondary mt-4 mb-0">¿No encontraste tu respuesta? Usa los datos de contacto que aparecen al final de la página.</p>

    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
