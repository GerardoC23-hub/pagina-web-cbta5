<?php
$base        = '../';
$pagina      = 'personal';
$title       = 'Personal y directorio · CBTa No. 5';
$description = 'Directorio del personal del CBTa No. 5 de Huejutla de Reyes, Hidalgo: directivos, administrativos y docentes.';
$styles      = [];

// Datos que muestra la pantalla
require __DIR__ . '/../backend/publico/personal-datos.php';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>

<main id="principal">

  <!-- Encabezado de la página -->
  <section class="pt-5 pb-4" aria-labelledby="staff-title">
    <div class="container">
      <h1 id="staff-title">Personal y directorio</h1>
      <p class="lead text-body-secondary mb-0">Solo se publica la información que la institución autoriza.</p>
    </div>
  </section>

  <!-- Buscador y filtro por área -->
  <section class="pb-3" aria-label="Buscar en el directorio">
    <div class="container">
      <form class="row g-3 align-items-end" method="get" action="personal.php">

        <div class="col-md-6">
          <label class="form-label" for="search">Buscar en el directorio</label>
          <input class="form-control" type="search" id="search" name="search" placeholder="Nombre o cargo…" value="<?= h($search) ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label" for="area">Área</label>
          <select class="form-select" id="area" name="area">
            <option value="">Todas las áreas</option>
<?php foreach ($areas as $option): ?>
            <option value="<?= h($option) ?>"<?= $area === $option ? ' selected' : '' ?>><?= h($option) ?></option>
<?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-center gap-3">
          <button class="btn btn-primary" type="submit">Buscar</button>
          <a class="link-success fw-bold" href="personal.php">Limpiar filtros</a>
        </div>

      </form>
    </div>
  </section>

  <!-- Resultados del directorio -->
  <section class="pb-5" aria-label="Directorio">
    <div class="container">

      <p class="small text-body-secondary" role="status"><?= $total ?> <?= $total === 1 ? 'resultado' : 'resultados' ?></p>

<?php if ($total === 0): ?>
      <div class="alert alert-info mb-0">No se encontró personal con esos datos. Prueba con otra búsqueda o limpia los filtros.</div>
<?php else: ?>
      <div class="table-responsive border rounded bg-white">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">Cargo</th>
              <th scope="col">Nombre</th>
              <th scope="col">Área</th>
              <th scope="col">Contacto</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($people as $person): ?>
            <tr>
              <td><?= h($person['cargo']) ?></td>
              <td><?= h($person['nombre']) ?></td>
              <td><?= h($person['tipo']) ?></td>
              <td class="text-break">
<?php if ($person['correo']): ?>
                <a href="mailto:<?= h($person['correo']) ?>"><?= h($person['correo']) ?></a>
<?php endif; ?>
<?php if ($person['telefono']): ?>
                <span class="d-block"><?= h($person['telefono']) ?></span>
<?php endif; ?>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
<?php endif; ?>

    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>