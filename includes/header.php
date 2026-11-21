<?php
$pagina = $pagina ?? '';
$base   = $base ?? '';
?>
<a class="skip-link visually-hidden-focusable" href="#principal">Saltar al contenido principal</a>

<header class="site-header sticky-top bg-brand border-bottom border-4 border-gold">
  <nav class="navbar navbar-expand-xl" data-bs-theme="dark" aria-label="Navegación principal">
    <div class="container">

      <a class="navbar-brand d-flex align-items-center gap-3 py-2" href="<?= $base ?>index.php">
        <img class="bg-white rounded p-1" height="48" src="<?= $base ?>assets/img/logos/logo-cbta5.png" alt="Escudo del CBTa No. 5">
        <span class="lh-sm">
          <strong class="d-block">CBTa No. 5</strong>
          <small>Huejutla de Reyes, Hgo.</small>
        </span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#menu" aria-controls="menu"
              aria-expanded="false" aria-label="Abrir menú">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-xl-auto align-items-xl-center">

          <li class="nav-item">
            <a class="nav-link text-white fw-semibold py-3<?= $pagina === 'inicio' ? ' active' : '' ?>"<?= $pagina === 'inicio' ? ' aria-current="page"' : '' ?> href="<?= $base ?>index.php">Inicio</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link text-white fw-semibold py-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Nosotros</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3" href="<?= $base ?>nosotros.php">Misión, visión e historia</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3" href="<?= $base ?>personal.php">Personal y directorio</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link text-white fw-semibold py-3<?= $pagina === 'nuevo-ingreso' ? ' active' : '' ?>"<?= $pagina === 'nuevo-ingreso' ? ' aria-current="page"' : '' ?> href="<?= $base ?>nuevo-ingreso.php">Nuevo ingreso</a></li>
          <li class="nav-item"><a class="nav-link text-white fw-semibold py-3<?= $pagina === 'documentos' ? ' active' : '' ?>"<?= $pagina === 'documentos' ? ' aria-current="page"' : '' ?> href="<?= $base ?>documentos.php">Documentos</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link text-white fw-semibold py-3 dropdown-toggle<?= in_array($pagina, ['clubes', 'servicios', 'galeria', 'productos'], true) ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vida estudiantil</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3<?= $pagina === 'clubes' ? ' active' : '' ?>" href="<?= $base ?>clubes.php">Clubes y actividades</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3<?= $pagina === 'servicios' ? ' active' : '' ?>" href="<?= $base ?>servicios.php">Servicios</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3<?= $pagina === 'galeria' ? ' active' : '' ?>"<?= $pagina === 'galeria' ? ' aria-current="page"' : '' ?> href="<?= $base ?>galeria.php">Galería</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3<?= $pagina === 'productos' ? ' active' : '' ?>" href="<?= $base ?>productos.php">Productos</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link text-white fw-semibold py-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Más</a>
            <ul class="dropdown-menu dropdown-menu-xl-end">
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3" href="<?= $base ?>noticias.php">Noticias y avisos</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3" href="<?= $base ?>materiales.php">Materiales educativos</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3" href="<?= $base ?>solicitudes.php">Solicitudes en línea</a></li>
              <li><a class="dropdown-item py-2 ps-4 ps-xl-3" href="<?= $base ?>enlaces.php">Enlaces de interés</a></li>
            </ul>
          </li>

          <li class="nav-item d-grid d-xl-block ms-xl-2 mt-3 mt-xl-0">
            <a class="btn btn-gold" href="<?= $base ?>acceso.php">Acceso alumnos</a>
          </li>

        </ul>
      </div>

    </div>
  </nav>
</header>
