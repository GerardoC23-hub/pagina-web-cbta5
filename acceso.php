<?php
$base        = '';
$pagina      = 'acceso';
$title       = 'Acceso alumnos · CBTa No. 5';
$description = 'Entra con tu matrícula y tu contraseña para ver contenido exclusivo para alumnos.';
$styles      = ['pages/auth.css'];
$scripts     = ['password-toggle.js'];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <section class="auth-page" aria-labelledby="login-title">
    <div class="container">

      <header class="auth-header">
        <h1 class="page-title" id="login-title">Acceso alumnos</h1>
      </header>

      <div class="auth-card">
        <form action="#" method="post" novalidate>

          <div class="field">
            <label for="student-id">Matrícula *</label>
            <input class="field-control" id="student-id" name="student_id" type="text"
              inputmode="numeric" pattern="\d{8}" maxlength="8" autocomplete="username"
              placeholder="Ej. 20250001" aria-describedby="student-id-hint student-id-error" required>
            <p class="field-error" id="student-id-error" hidden>La matrícula tiene 8 dígitos, por ejemplo 20250001.</p>
          </div>

          <div class="field">
            <label for="student-password">Contraseña *</label>
            <div class="password-field">
              <input class="field-control" id="student-password" name="password" type="password"
                autocomplete="current-password" aria-describedby="student-password-error" required>
              <button class="password-toggle" type="button" data-toggle-password="student-password" aria-pressed="false">Mostrar</button>
            </div>
            <p class="field-error" id="student-password-error" hidden>Escribe tu contraseña.</p>
          </div>

          <button class="btn-main btn-block btn-lock" type="submit">Entrar</button>
        </form>

        <p class="auth-note">En tu primer acceso deberás cambiar tu contraseña. Tras 5 intentos fallidos se bloquea el acceso por 15 minutos y la sesión se cierra a los 30 minutos sin actividad.</p>
        <p class="auth-note">¿Olvidaste tu contraseña? Pide a Servicios Escolares que la restablezcan.</p>
      </div>

    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
