<?php
// Panel shell (Bootstrap): side menu, header (user name + sign out) and the opening <main>.
// Set $panelPage (section id) and $title before including this file.
require_once __DIR__ . '/panel-icons.php';

// Escapa texto para mostrarlo en HTML
function e(?string $text): string
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

$menu      = require __DIR__ . '/panel-menu.php';
$user      = $_SESSION['panel_user'];
$panelPage = $panelPage ?? '';
$initial   = mb_strtoupper(mb_substr($user['name'], 0, 1));
?>
<a class="visually-hidden-focusable" href="#principal">Saltar al contenido principal</a>

<div class="d-lg-flex min-vh-100 bg-body-tertiary">

  <aside class="panel-sidebar offcanvas-lg offcanvas-start bg-brand text-white flex-shrink-0" id="panel-sidebar" tabindex="-1" aria-label="Menú del panel">
    <div class="offcanvas-header d-lg-none">
      <button class="btn-close btn-close-white ms-auto" type="button" data-bs-dismiss="offcanvas" data-bs-target="#panel-sidebar" aria-label="Cerrar menú"></button>
    </div>
    <div class="offcanvas-body d-block p-3 p-lg-4">
      <a class="d-flex align-items-center gap-3 mb-3 text-white text-decoration-none" href="<?= $base ?>admin/index.php">
        <span class="bg-white rounded-3 p-2 border-bottom border-3 border-warning-subtle"><img class="d-block" height="52" src="<?= $base ?>assets/img/logos/logo-cbta5-panel.png" alt="Escudo del CBTa No. 5"></span>
        <span class="lh-sm"><strong class="d-block">CBTa No. 5</strong><small class="text-gold">Panel de administración</small></span>
      </a>

      <nav aria-label="Secciones del panel">
        <ul class="nav flex-column">
<?php $lastGroup = ''; ?>
<?php foreach ($menu as $item): ?>
<?php if (in_array($user['role'], $item['roles'], true)): ?>
<?php if ($item['group'] !== $lastGroup && $item['group'] !== 'Principal'): $lastGroup = $item['group']; ?>
          <li class="text-gold small fw-bold text-uppercase px-2 pt-3 pb-1"><?= $item['group'] ?></li>
<?php endif; ?>
          <li><a class="nav-link d-flex align-items-center gap-3 rounded<?= $panelPage === $item['id'] ? ' active' : '' ?>"<?= $panelPage === $item['id'] ? ' aria-current="page"' : '' ?> href="<?= $base ?>admin/<?= $item['file'] ?>"><?= panel_icon($item['icon']) ?><span><?= $item['label'] ?></span></a></li>
<?php endif; ?>
<?php endforeach; ?>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="flex-grow-1 min-w-0">
    <header class="d-flex align-items-center gap-3 px-3 px-lg-4 py-2 bg-white border-bottom">
      <button class="btn btn-outline-success d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#panel-sidebar" aria-controls="panel-sidebar">Menú</button>
      <span class="ms-auto rounded-circle bg-success text-white fw-bold d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px" aria-hidden="true"><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></span>
      <span class="fw-bold"><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></span>
      <a class="btn btn-outline-success btn-sm fw-bold" href="<?= $base ?>admin/logout.php">Cerrar sesión</a>
    </header>

    <main class="p-3 p-lg-5" id="principal">
<?php if (!empty($_SESSION['flash'])): ?>
      <div class="alert alert-<?= e($_SESSION['flash']['type']) ?> alert-dismissible fade show" role="<?= $_SESSION['flash']['type'] === 'danger' ? 'alert' : 'status' ?>"<?= $_SESSION['flash']['type'] === 'success' ? ' data-auto-dismiss' : '' ?>>
        <?= e($_SESSION['flash']['text']) ?>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
<?php unset($_SESSION['flash']); endif; ?>
