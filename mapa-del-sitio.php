<?php
$base        = '';
$pagina      = 'mapa-del-sitio';
$title       = 'Mapa del sitio · CBTa No. 5';
$description = 'Todas las páginas del sitio del CBTa No. 5 en un solo lugar, agrupadas por sección.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">
  <div class="container py-5">
    <div class="col-lg-8 mx-auto">

      <!-- Encabezado de la página -->
      <h1 class="mb-2">Mapa del sitio</h1>
      <p class="lead text-body-secondary mb-4">Todas las páginas en un solo lugar.</p>

      <!-- Grupos de páginas (mismo orden que el menú) -->
      <div class="row row-cols-1 row-cols-md-2 g-4">

        <section class="col" aria-labelledby="map-principal">
          <h2 class="h4" id="map-principal">Principal</h2>
          <ul class="mb-0">
            <li><a href="<?= $base ?>index.php">Inicio</a></li>
            <li><a href="<?= $base ?>pages/nuevo-ingreso.php">Nuevo ingreso</a></li>
            <li><a href="<?= $base ?>pages/documentos.php">Documentos</a></li>
            <li><a href="<?= $base ?>pages/noticias.php">Noticias y avisos</a></li>
          </ul>
        </section>

        <section class="col" aria-labelledby="map-nosotros">
          <h2 class="h4" id="map-nosotros">Nosotros</h2>
          <ul class="mb-0">
            <li><a href="<?= $base ?>pages/nosotros.php">Misión, visión e historia</a></li>
            <li><a href="<?= $base ?>pages/personal.php">Personal y directorio</a></li>
          </ul>
        </section>

        <section class="col" aria-labelledby="map-vida">
          <h2 class="h4" id="map-vida">Vida estudiantil</h2>
          <ul class="mb-0">
            <li><a href="<?= $base ?>pages/clubes.php">Clubes y actividades</a></li>
            <li><a href="<?= $base ?>pages/servicios.php">Servicios</a></li>
            <li><a href="<?= $base ?>pages/galeria.php">Galería</a></li>
            <li><a href="<?= $base ?>pages/productos.php">Productos</a></li>
          </ul>
        </section>

        <section class="col" aria-labelledby="map-mas">
          <h2 class="h4" id="map-mas">Más</h2>
          <ul class="mb-0">
            <li><a href="<?= $base ?>pages/materiales.php">Materiales educativos</a></li>
            <li><a href="<?= $base ?>pages/solicitudes.php">Solicitudes en línea</a></li>
            <li><a href="<?= $base ?>enlaces.php">Enlaces de interés</a></li>
            <li><a href="<?= $base ?>pages/contacto.php">Contacto</a></li>
          </ul>
        </section>

        <section class="col" aria-labelledby="map-alumnos">
          <h2 class="h4" id="map-alumnos">Alumnos</h2>
          <ul class="mb-0">
            <li><a href="<?= $base ?>pages/acceso.php">Acceso alumnos</a></li>
            <li><a href="<?= $base ?>panel/recuperar.php">Recuperar contraseña</a></li>
          </ul>
        </section>

        <section class="col" aria-labelledby="map-info">
          <h2 class="h4" id="map-info">Información del sitio</h2>
          <ul class="mb-0">
            <li><a href="<?= $base ?>preguntas-frecuentes.php">Preguntas frecuentes</a></li>
            <li><a href="<?= $base ?>privacidad.php">Aviso de privacidad</a></li>
            <li><a href="<?= $base ?>terminos.php">Términos y condiciones</a></li>
            <li><a href="<?= $base ?>accesibilidad.php">Accesibilidad</a></li>
            <li><a href="<?= $base ?>mapa-del-sitio.php">Mapa del sitio</a></li>
          </ul>
        </section>

      </div>

    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
