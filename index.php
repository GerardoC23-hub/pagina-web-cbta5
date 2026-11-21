<?php
$base        = '';
$pagina      = 'inicio';
$title       = 'CBTa No. 5 · Huejutla de Reyes, Hgo.';
$description = 'Sitio oficial del CBTa No. 5 de Huejutla de Reyes, Hidalgo: oferta educativa, nuevo ingreso, noticias, vida estudiantil y contacto.';
$styles      = [];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">

  <!-- Hero: se desliza solo, sin flechas ni puntos -->
  <section class="pt-4" aria-label="Destacados">
    <div class="container">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="hover">
        <div class="carousel-inner">

          <div class="carousel-item active">
            <div class="card p-4 p-lg-5"><div class="row g-4 g-lg-5 align-items-center">
              <div class="col-lg-6 d-flex flex-column align-items-start gap-3">
                <span class="badge rounded-pill bg-gold">Desde 1972 · Huejutla de Reyes</span>
                <h1 class="mb-0">Formamos técnicos para el campo y la tecnología de la Huasteca</h1>
                <p class="lead fs-5 fw-normal text-body-secondary mb-0">Bachillerato tecnológico agropecuario con ocho carreras.</p>
                <div class="d-flex flex-wrap gap-3">
                  <a class="btn btn-primary" href="nosotros.php">Conoce el plantel →</a>
                  <a class="btn btn-outline-primary" href="oferta.php">Ver carreras</a>
                </div>
              </div>
              <div class="col-lg-6"><img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Fachada del CBTa No. 5 (foto por agregar)"></div>
            </div></div>
          </div>

          <div class="carousel-item">
            <div class="card p-4 p-lg-5"><div class="row g-4 g-lg-5 align-items-center">
              <div class="col-lg-6 d-flex flex-column align-items-start gap-3">
                <span class="badge rounded-pill bg-gold">Nuevo ingreso</span>
                <h2 class="h1 mb-0">Preinscripciones: fechas, requisitos y proceso</h2>
                <p class="lead fs-5 fw-normal text-body-secondary mb-0">Consulta cómo solicitar tu ficha.</p>
                <div class="d-flex flex-wrap gap-3">
                  <a class="btn btn-primary" href="nuevo-ingreso.php">Ver requisitos →</a>
                </div>
              </div>
              <div class="col-lg-6"><img class="photo photo-4x3" src="assets/img/placeholder.svg" width="640" height="360" alt="Estudiantes en preinscripciones (foto por agregar)"></div>
            </div></div>
          </div>

        </div>
      </div>

      <nav aria-label="Accesos directos">
        <ul class="row row-cols-1 row-cols-md-3 g-3 list-unstyled mt-1 mb-0">
          <li class="col">
            <a class="card card-hover h-100 flex-row align-items-center gap-3 p-3 quick-link text-reset text-decoration-none" href="nuevo-ingreso.php">
              <img class="rounded-circle bg-success-subtle p-2 flex-shrink-0" src="assets/img/icons/calendar.svg" width="44" height="44" alt="">
              <span><strong class="d-block">Nuevo ingreso</strong><small class="text-body-secondary">Requisitos y fechas</small></span>
            </a>
          </li>
          <li class="col">
            <a class="card card-hover h-100 flex-row align-items-center gap-3 p-3 quick-link text-reset text-decoration-none" href="documentos.php">
              <img class="rounded-circle bg-success-subtle p-2 flex-shrink-0" src="assets/img/icons/document.svg" width="44" height="44" alt="">
              <span><strong class="d-block">Documentos</strong><small class="text-body-secondary">Convocatorias</small></span>
            </a>
          </li>
          <li class="col">
            <a class="card card-hover h-100 flex-row align-items-center gap-3 p-3 quick-link text-reset text-decoration-none" href="solicitudes.php">
              <img class="rounded-circle bg-success-subtle p-2 flex-shrink-0" src="assets/img/icons/edit.svg" width="44" height="44" alt="">
              <span><strong class="d-block">Solicitudes en línea</strong><small class="text-body-secondary">Trámites sin ir al plantel</small></span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </section>

  <!-- Oferta educativa -->
  <section class="section" aria-labelledby="careers-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="careers-title">Oferta educativa</h2>
          <p class="text-body-secondary mt-1 mb-0">Elige la carrera técnica que se parezca más a ti.</p>
        </div>
        <a class="btn btn-outline-primary" href="oferta.php">Ver toda la oferta</a>
      </div>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 list-unstyled mb-0">
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="oferta.php">Programación</a></h3>
            <p class="card-text text-body-secondary mb-3">Software, datos y aplicaciones.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="oferta.php">Contabilidad</a></h3>
            <p class="card-text text-body-secondary mb-3">Finanzas y registros contables.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="oferta.php">Ofimática</a></h3>
            <p class="card-text text-body-secondary mb-3">Herramientas digitales para la oficina.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
        <li class="col d-none d-md-block">
          <article class="card card-hover h-100 p-4">
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="oferta.php">Desarrollo Comunitario</a></h3>
            <p class="card-text text-body-secondary mb-3">Proyectos sociales para la comunidad.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
      </ul>
    </div>
  </section>

  <!-- Nuevo ingreso -->
  <section class="section bg-body-tertiary" aria-labelledby="admission-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="admission-title">Nuevo ingreso en 4 pasos</h2>
          <p class="text-body-secondary mt-1 mb-0">Las fechas se publicarán aquí.</p>
        </div>
        <a class="btn btn-outline-primary" href="nuevo-ingreso.php">Ver requisitos y fechas</a>
      </div>
      <ol class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 list-unstyled mb-0">
        <li class="col"><div class="card h-100 flex-row gap-3 p-3"><span class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px" aria-hidden="true">1</span><span><strong class="d-block">Ficha</strong><span class="text-body-secondary">Solicítala en el plantel.</span></span></div></li>
        <li class="col"><div class="card h-100 flex-row gap-3 p-3"><span class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px" aria-hidden="true">2</span><span><strong class="d-block">Documentos</strong><span class="text-body-secondary">Entrega tus documentos.</span></span></div></li>
        <li class="col"><div class="card h-100 flex-row gap-3 p-3"><span class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px" aria-hidden="true">3</span><span><strong class="d-block">Evaluación</strong><span class="text-body-secondary">Presenta el examen diagnóstico.</span></span></div></li>
        <li class="col"><div class="card h-100 flex-row gap-3 p-3"><span class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px" aria-hidden="true">4</span><span><strong class="d-block">Inscripción</strong><span class="text-body-secondary">Confirma tu lugar.</span></span></div></li>
      </ol>
    </div>
  </section>

  <!-- Noticias -->
  <section class="section" aria-labelledby="news-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="news-title">Noticias y avisos</h2>
          <p class="text-body-secondary mt-1 mb-0">Lo más reciente del plantel.</p>
        </div>
        <a class="btn btn-outline-primary" href="noticias.php">Ver todas</a>
      </div>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 list-unstyled mb-0">
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Festejo del Día del Estudiante (foto por agregar)" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-info-subtle text-info-emphasis">Evento</span><time datetime="2026-05-22">22 de mayo de 2026</time></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="noticias.php">Festejo del Día del Estudiante</a></h3>
            <p class="card-text text-body-secondary mb-3">Estudiantes participaron en el festejo municipal.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Leer más</span>
          </article>
        </li>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Preinscripciones 2026 (foto por agregar)" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Aviso</span><time datetime="2026-01-22">22 de enero de 2026</time></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="noticias.php">Preinscripciones 2026</a></h3>
            <p class="card-text text-body-secondary mb-3">Fechas, requisitos y carreras disponibles.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Leer más</span>
          </article>
        </li>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Graduación 2025 (foto por agregar)" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Logro</span><time datetime="2025-07-18">18 de julio de 2025</time></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="noticias.php">387 estudiantes se graduaron</a></h3>
            <p class="card-text text-body-secondary mb-3">La generación concluyó su bachillerato.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Leer más</span>
          </article>
        </li>
      </ul>
    </div>
  </section>

  <!-- Vida estudiantil -->
  <section class="section bg-body-tertiary" aria-labelledby="life-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="life-title">Vida estudiantil</h2>
          <p class="text-body-secondary mt-1 mb-0">Clubes, actividades y servicios del plantel.</p>
        </div>
        <a class="btn btn-outline-primary" href="clubes.php">Ver todo</a>
      </div>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 list-unstyled mb-0">
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Desfile de cuadrillas del Xantolo (foto por agregar)" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-body-tertiary text-body-secondary border">Actividad</span></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="clubes.php">Desfile de cuadrillas del Xantolo</a></h3>
            <p class="card-text text-body-secondary mb-3">Participación en el desfile estudiantil.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver más</span>
          </article>
        </li>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Feria Didáctica (foto por agregar)" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-body-tertiary text-body-secondary border">Actividad</span></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="clubes.php">Feria Didáctica</a></h3>
            <p class="card-text text-body-secondary mb-3">Visita a la Escuela Normal de las Huastecas.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver más</span>
          </article>
        </li>
        <li class="col">
          <article class="card card-hover h-100 p-4">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Orientación educativa (foto por agregar)" loading="lazy">
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-body-secondary"><span class="badge rounded-pill bg-body-tertiary text-body-secondary border">Servicio</span></div>
            <h3 class="card-title h5 fw-bold mb-2"><a class="stretched-link text-reset text-decoration-none" href="servicios.php">Orientación educativa</a></h3>
            <p class="card-text text-body-secondary mb-3">Apoyo para estudiantes y familias.</p>
            <span class="card-link mt-auto fw-bold text-success" aria-hidden="true">Ver más</span>
          </article>
        </li>
      </ul>
    </div>
  </section>

  <!-- Contáctanos: solo formulario y mapa. Teléfono, correo y dirección van en el footer -->
  <section class="section" aria-labelledby="contact-title">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="section-title mb-0" id="contact-title">Contáctanos</h2>
          <p class="text-body-secondary mt-1 mb-0">Te respondemos a tu correo con un folio de seguimiento.</p>
        </div>
      </div>
      <div class="card overflow-hidden"><div class="row g-0">
        <form class="col-lg-6 p-4 p-lg-5" action="#" method="post" novalidate>
          <h3 class="mb-1">Envíanos un mensaje</h3>
          <p class="form-text mb-3 mt-0">Los campos marcados con * son obligatorios.</p>

          <div class="mb-3">
            <label class="form-label" for="name">Nombre completo *</label>
            <input class="form-control" id="name" name="name" type="text" placeholder="Ej. Ana Pérez López" autocomplete="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="email">Correo electrónico *</label>
            <input class="form-control" id="email" name="email" type="email" placeholder="nombre@correo.com" autocomplete="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="message">Mensaje *</label>
            <textarea class="form-control" id="message" name="message" maxlength="600" placeholder="Escribe tu duda o comentario…" required></textarea>
            <p class="form-text">Máximo 600 caracteres.</p>
          </div>

          <button class="btn btn-primary" type="submit">Enviar mensaje</button>
        </form>

        <iframe class="col-lg-6 border-0" style="min-height:320px" title="Mapa de ubicación del CBTa No. 5"
                src="https://www.google.com/maps?q=CBTa+No.+5+Huejutla+de+Reyes+Hidalgo&amp;output=embed"
                loading="lazy"></iframe>
      </div></div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
