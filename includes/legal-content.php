<?php
// Contenido de una página legal. Lo usan privacidad.php, terminos.php y accesibilidad.php.
// Necesita: $legal, $sections, $updated (los deja backend/publico/legal-datos.php).
$intro    = $sections[0]['blocks'] ?? [];
$numbered = array_slice($sections, 1);
?>

<main id="principal">
  <div class="container py-5">
    <div class="col-lg-8 mx-auto">

<?php if ($legal === null): ?>
      <h1 class="mb-2">Página no disponible</h1>
      <p class="lead text-body-secondary mb-0">Este contenido todavía no está publicado.</p>
<?php else: ?>
      <!-- Encabezado de la página -->
      <h1 class="mb-2"><?= h($legal['titulo']) ?></h1>
      <p class="small text-body-secondary mb-4">Última actualización: <?= h($updated) ?></p>

<?php if ($intro): ?>
      <!-- Texto de apertura -->
<?php foreach ($intro as $index => $block): ?>
<?php if ($block['type'] === 'p'): ?>
      <p class="<?= $index === 0 ? 'lead text-body-secondary ' : '' ?>mb-4"><?= legalInline($block['text'], $base) ?></p>
<?php else: ?>
      <ul class="mb-4">
<?php foreach ($block['items'] as $item): ?>
        <li class="mb-2"><?= legalInline($item, $base) ?></li>
<?php endforeach; ?>
      </ul>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php if ($numbered): ?>
      <!-- Índice -->
      <nav class="mb-5" aria-label="Índice de esta página">
        <h2 class="h5">Contenido</h2>
        <ol class="mb-0">
<?php foreach ($numbered as $section): ?>
          <li><a href="#<?= h($section['id']) ?>"><?= h($section['title']) ?></a></li>
<?php endforeach; ?>
        </ol>
      </nav>

      <!-- Apartados -->
<?php foreach ($numbered as $number => $section): ?>
      <section class="mb-4" id="<?= h($section['id']) ?>">
        <h2 class="h4"><?= $number + 1 ?>. <?= h($section['title']) ?></h2>
<?php foreach ($section['blocks'] as $block): ?>
<?php if ($block['type'] === 'p'): ?>
        <p><?= legalInline($block['text'], $base) ?></p>
<?php else: ?>
        <ul>
<?php foreach ($block['items'] as $item): ?>
          <li><?= legalInline($item, $base) ?></li>
<?php endforeach; ?>
        </ul>
<?php endif; ?>
<?php endforeach; ?>
      </section>

<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>
    </div>
  </div>
</main>
