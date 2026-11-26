<?php
$base        = '';
$pagina      = 'personal';
$title       = 'Personal y directorio · CBTa No. 5';
$description = 'Directorio del personal del CBTa No. 5 de Huejutla de Reyes, Hidalgo: directivos, administrativos y docentes.';
$styles      = [];
$search = $_GET['search'] ?? '';
$area   = $_GET['area'] ?? '';

// EStos son mis registros de ejemplo, luego con la base de datos se reemplaza
$staff = [
    ['position' => 'Director (según nota de julio de 2025)', 'name' => 'Mtro. Ignacio Hernández García',         'area' => 'Directivos',     'email' => 'hgo.cbta005.direccion@dgetaycm.sems.gob.mx'],
    ['position' => 'Subdirector Académico',                  'name' => 'Lic. María Fernanda Cruz Hernández (ejemplo)', 'area' => 'Directivos',     'email' => 'hgo.cbta005.academica@dgetaycm.sems.gob.mx'],
    ['position' => 'Jefe de Servicios Escolares',            'name' => 'C. Luis Ángel Ramírez Torres (ejemplo)',  'area' => 'Departamentos', 'email' => 'hgo.cbta005.escolares@dgetaycm.sems.gob.mx'],
    ['position' => 'Docente · Programación',                 'name' => 'Ing. Karla Itzel Martínez Olvera (ejemplo)', 'area' => 'Docentes',       'email' => 'hgo.cbta005.docentes@dgetaycm.sems.gob.mx'],
    ['position' => 'Docente · Producción Agrícola',          'name' => 'Ing. Jorge Alberto Vite Santiago (ejemplo)', 'area' => 'Docentes',       'email' => 'hgo.cbta005.docentes@dgetaycm.sems.gob.mx'],
];

// wsto es para la parte del buscador y del filro, para que se pueda buscar por nombre o cargo y filtrar por area
$results = [];
foreach ($staff as $person) {
    $matchesArea = $area === '' || $person['area'] === $area;
    $matchesText = $search === '' || stripos($person['position'] . ' ' . $person['name'], $search) !== false;

    if ($matchesArea && $matchesText) {
        $results[] = $person;
    }
}
$total = count($results);

require __DIR__ . './includes/head.php';
require __DIR__ . './includes/header.php';
?>

<main id="principal">

  <!-- aqui está el encabezado -->
  <section class="pt-5 pb-4" aria-labelledby="staff-title">
    <div class="container">
      <h1 id="staff-title">Personal y directorio</h1>
      <p class="lead text-body-secondary mb-0">Solo se publica la información que la institución autoriza.</p>
    </div>
  </section>

  <!-- aqui va la parte del buscador y de el filtro-->
 <section class="pb-3" aria-label="Buscar en el directorio">
    <div class="container">
      <form class="row g-3 align-items-end" method="get" action="personal.php">

        <div class="col-md-6">
          <label class="form-label" for="search">Buscar en el directorio</label>
          <input class="form-control" type="search" id="search" name="search" placeholder="Nombre o cargo…" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label" for="area">Área</label>
          <select class="form-select" id="area" name="area">
            <option value="">Todas las áreas</option>
            <option value="Directivos"<?= $area === 'Directivos' ? ' selected' : '' ?>>Directivos</option>
            <option value="Departamentos"<?= $area === 'Departamentos' ? ' selected' : '' ?>>Departamentos</option>
            <option value="Docentes"<?= $area === 'Docentes' ? ' selected' : '' ?>>Docentes</option>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-center gap-3">
          <button class="btn btn-primary" type="submit">Buscar</button>
          <a class="link-success fw-bold" href="personal.php">Limpiar filtros</a>
        </div>

      </form>
    </div>
  </section>
</main>

  <!-- esta es la parte de la taba ue va a dar los resulatdos del filtro -->
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
<?php foreach ($results as $person): ?>
            <tr>
              <td><?= htmlspecialchars($person['position'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($person['name'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($person['area'], ENT_QUOTES, 'UTF-8') ?></td>
              <td class="text-break"><a href="mailto:<?= htmlspecialchars($person['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($person['email'], ENT_QUOTES, 'UTF-8') ?></a></td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
<?php endif; ?>

    </div>
  </section>
<?php require __DIR__ . '/includes/footer.php'; ?>