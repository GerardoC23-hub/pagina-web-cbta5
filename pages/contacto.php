<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos que muestra la página
require __DIR__ . '/../backend/publico/contacto-datos.php';

// Configuración de la página
$base        = '../';
$pagina      = 'contacto';
$title       = 'Contacto · CBTa No. 5';
$description = 'Escribe al CBTa No. 5 de Huejutla de Reyes: formulario de contacto, ubicación en el mapa y datos de atención.';
$styles      = [];

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>

<main id="principal">
  <div class="container">

    <div class="pt-4">
      <h1 class="mb-0">Contacto</h1>
      <p class="lead fs-5 fw-normal text-body-secondary mt-2 mb-0">Te respondemos a tu correo con un folio de seguimiento.</p>
    </div>

    <!-- Teléfono, correo y dirección van en el footer -->
    <section class="section" aria-label="Formulario y mapa">
      <div class="card overflow-hidden"><div class="row g-0">
        <form class="col-lg-6 p-4 p-lg-5" id="formulario" action="../backend/publico/contacto-guardar.php" method="post">
          <h3 class="mb-1">Envíanos un mensaje</h3>
          <p class="form-text mb-3 mt-0">Los campos marcados con * son obligatorios.</p>

<?php if ($sentFolio !== ''): ?>
          <div class="alert alert-success" role="status">Recibimos tu mensaje. Tu folio de seguimiento es <strong><?= htmlspecialchars($sentFolio, ENT_QUOTES, 'UTF-8') ?></strong>. Guárdalo por si necesitas dar seguimiento.</div>
<?php endif; ?>
<?php if ($error !== ''): ?>
          <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

          <div class="mb-3">
            <label class="form-label" for="name">Nombre completo *</label>
            <input class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" id="name" name="name" type="text" minlength="3" maxlength="100" placeholder="Ej. Ana Pérez López" autocomplete="name" value="<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>" required>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['name'] ?? 'Nombre: de 3 a 100 caracteres.', ENT_QUOTES, 'UTF-8') ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="email">Correo electrónico *</label>
            <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="email" name="email" type="email" maxlength="120" placeholder="nombre@correo.com" autocomplete="email" value="<?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?>" required>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['email'] ?? 'Correo no válido.', ENT_QUOTES, 'UTF-8') ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="subject">Asunto *</label>
            <select class="form-select<?= isset($errors['subject']) ? ' is-invalid' : '' ?>" id="subject" name="subject" required>
              <option value="">Elige un asunto</option>
<?php foreach ($subjects as $subject): ?>
              <option<?= $data['subject'] === $subject ? ' selected' : '' ?>><?= htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') ?></option>
<?php endforeach; ?>
            </select>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['subject'] ?? 'Elige un asunto.', ENT_QUOTES, 'UTF-8') ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="message">Mensaje *</label>
            <textarea class="form-control<?= isset($errors['message']) ? ' is-invalid' : '' ?>" id="message" name="message" minlength="10" maxlength="600" placeholder="Escribe tu duda o comentario…" required><?= htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8') ?></textarea>
            <div class="form-text">Máximo 600 caracteres.</div>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['message'] ?? 'Mensaje: de 10 a 600 caracteres.', ENT_QUOTES, 'UTF-8') ?></div>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input<?= isset($errors['notice']) ? ' is-invalid' : '' ?>" id="notice" name="notice" type="checkbox"<?= $data['notice'] !== '' ? ' checked' : '' ?> required>
            <label class="form-check-label" for="notice">Acepto el aviso de privacidad *</label>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['notice'] ?? 'Debes aceptar el aviso de privacidad.', ENT_QUOTES, 'UTF-8') ?></div>
          </div>

          <button class="btn btn-primary" type="submit">Enviar mensaje</button>
        </form>

<?php if ($mapSrc !== ''): ?>
        <iframe class="col-lg-6 border-0" style="min-height:320px" title="Mapa de ubicación del CBTa No. 5"
                src="<?= h($mapSrc) ?>"
                loading="lazy"></iframe>
<?php endif; ?>
      </div></div>
    </section>

  </div>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
