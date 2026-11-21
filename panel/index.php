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
        <p class="mb-0 text-gold">Elige un módulo para empezar. Tu rol: <?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>.</p>
      </section>

      <?php
      // Group the sections the user can open, skipping Inicio
      $groups = [];
      foreach ($menu as $item) {
          if ($item['id'] !== 'inicio' && in_array($user['role'], $item['roles'], true)) {
              $groups[$item['group']][] = $item;
          }
      }
      ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
<?php foreach ($groups as $groupName => $items): ?>
        <section class="col" aria-labelledby="group-<?= $items[0]['id'] ?>">
          <div class="card h-100">
            <h2 class="card-header bg-white h6 text-danger text-uppercase fw-bold mb-0" id="group-<?= $items[0]['id'] ?>"><?= $groupName ?></h2>
            <div class="list-group list-group-flush">
<?php foreach ($items as $item): ?>
              <a class="list-group-item list-group-item-action d-flex align-items-center gap-3" href="<?= $item['file'] ?>"><?= panel_icon($item['icon']) ?><span><?= $item['label'] ?></span></a>
<?php endforeach; ?>
            </div>
          </div>
        </section>
<?php endforeach; ?>
      </div>

<?php require __DIR__ . '/../includes/panel-layout-end.php'; ?>
