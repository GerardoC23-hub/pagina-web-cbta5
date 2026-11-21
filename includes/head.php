<?php

$base        = $base ?? '';
$title       = $title ?? 'CBTa No. 5 · Huejutla de Reyes, Hgo.';
$description = $description ?? '';
$styles      = $styles ?? [];
$withSite    = $withSite ?? true;
$noindex     = $noindex ?? false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
<?php if ($description !== ''): ?>
  <meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<?php if ($noindex): ?>
  <meta name="robots" content="noindex, nofollow">
<?php endif; ?>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $base ?>assets/css/base.css">
  <link rel="stylesheet" href="<?= $base ?>assets/css/components.css">
<?php if ($withSite): ?>
  <link rel="stylesheet" href="<?= $base ?>assets/css/header.css">
<?php endif; ?>
<?php foreach ($styles as $style): ?>
  <link rel="stylesheet" href="<?= $base ?>assets/css/<?= $style ?>">
<?php endforeach; ?>
</head>
<body>
