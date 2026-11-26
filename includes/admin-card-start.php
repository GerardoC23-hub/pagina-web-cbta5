<?php
// Abre la tarjeta de las pantallas de acceso del admin (recuperar contraseña y sus pasos).
// Necesita: $base, $cardTitle, $cardText
?>

<main class="panel-login min-vh-100 d-flex flex-column align-items-center justify-content-center gap-3 p-3" id="principal">

  <section class="panel-card card w-100 p-4 shadow" aria-labelledby="card-title">
    <div class="d-flex align-items-center gap-3 mb-4">
      <img height="64" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
      <div class="lh-sm">
        <strong class="d-block text-success fs-5">Panel de administración</strong>
        <small class="text-body-secondary">CBTa No. 5 · Huejutla de Reyes</small>
      </div>
    </div>

    <h1 class="h3 fw-bold mb-1" id="card-title"><?= h($cardTitle) ?></h1>
    <p class="text-body-secondary mb-3"><?= h($cardText) ?></p>

<?php if ($error !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= h($error) ?></div>
<?php endif; ?>
