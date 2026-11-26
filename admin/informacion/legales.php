<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/informacion/legales-datos.php';

// Configuración de la página
$base      = '../../';
$title     = 'Avisos legales · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'legales';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3">Avisos legales</h1>
      <p class="text-secondary mb-4">Aviso de privacidad, términos de uso y declaración de accesibilidad. Los cambios se ven en el sitio al guardar.</p>

      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Lista de textos legales</caption>
            <thead class="table-light">
              <tr>
                <th scope="col">Texto</th>
                <th scope="col">Última actualización</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($legals as $legal): ?>
              <tr>
                <th class="fw-bold" scope="row"><?= e($legal['titulo']) ?></th>
                <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($legal['actualizado_en'])) ?></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="legales-formulario.php?clave=<?= e($legal['clave']) ?>">Editar</a>
<?php if (isset($legalPages[$legal['clave']])): ?>
                    <a class="btn btn-outline-primary btn-sm" href="<?= $base . $legalPages[$legal['clave']] ?>" target="_blank" rel="noopener">Ver en el sitio</a>
<?php endif; ?>
                  </div>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
