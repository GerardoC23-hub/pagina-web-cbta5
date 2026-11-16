<?php
$base        = '';
$pagina      = 'inicio';
$title       = 'CBTa No. 5 · Huejutla de Reyes, Hgo.';
$description = 'Sitio oficial del CBTa No. 5 de Huejutla de Reyes, Hidalgo: oferta educativa, nuevo ingreso, noticias, vida estudiantil y contacto.';
$styles      = ['pages/home.css'];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="principal">

  <!-- Hero: se desliza solo, sin flechas ni puntos -->
  <section class="hero" aria-label="Destacados">
    <div class="container">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="hover">
        <div class="carousel-inner">

          <div class="carousel-item active">
            <div class="hero-slide">
              <div class="hero-text">
                <span class="tag tag-gold">Desde 1972 · Huejutla de Reyes</span>
                <h1 class="hero-title">Formamos técnicos para el campo y la tecnología de la Huasteca</h1>
                <p class="hero-lead">Bachillerato tecnológico agropecuario con ocho carreras.</p>
                <div class="hero-actions">
                  <a class="btn-main" href="nosotros.php">Conoce el plantel →</a>
                  <a class="btn-outline-main" href="oferta.php">Ver carreras</a>
                </div>
              </div>
              <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Fachada del CBTa No. 5 (foto por agregar)">
            </div>
          </div>

          <div class="carousel-item">
            <div class="hero-slide">
              <div class="hero-text">
                <span class="tag tag-gold">Nuevo ingreso</span>
                <h2 class="hero-title">Preinscripciones: fechas, requisitos y proceso</h2>
                <p class="hero-lead">Consulta cómo solicitar tu ficha.</p>
                <div class="hero-actions">
                  <a class="btn-main" href="nuevo-ingreso.php">Ver requisitos →</a>
                </div>
              </div>
              <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Estudiantes en preinscripciones (foto por agregar)">
            </div>
          </div>

        </div>
      </div>

      <nav aria-label="Accesos directos">
        <ul class="quick-links">
          <li>
            <a class="quick-link" href="nuevo-ingreso.php">
              <span class="quick-icon quick-calendar" aria-hidden="true"></span>
              <span><strong>Nuevo ingreso</strong><span>Requisitos y fechas</span></span>
            </a>
          </li>
          <li>
            <a class="quick-link" href="documentos.php">
              <span class="quick-icon quick-document" aria-hidden="true"></span>
              <span><strong>Documentos</strong><span>Convocatorias</span></span>
            </a>
          </li>
          <li>
            <a class="quick-link" href="solicitudes.php">
              <span class="quick-icon quick-edit" aria-hidden="true"></span>
              <span><strong>Solicitudes en línea</strong><span>Trámites sin ir al plantel</span></span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </section>

  <!-- Oferta educativa -->
  <section class="section" aria-labelledby="careers-title">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title" id="careers-title">Oferta educativa</h2>
          <p class="section-subtitle">Elige la carrera técnica que se parezca más a ti.</p>
        </div>
        <a class="btn-outline-main" href="oferta.php">Ver toda la oferta</a>
      </div>
      <ul class="career-grid">
        <li>
          <article class="card-item">
            <h3 class="card-title"><a class="stretched-link" href="oferta.php">Programación</a></h3>
            <p class="card-text">Software, datos y aplicaciones.</p>
            <span class="card-link" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <h3 class="card-title"><a class="stretched-link" href="oferta.php">Contabilidad</a></h3>
            <p class="card-text">Finanzas y registros contables.</p>
            <span class="card-link" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <h3 class="card-title"><a class="stretched-link" href="oferta.php">Ofimática</a></h3>
            <p class="card-text">Herramientas digitales para la oficina.</p>
            <span class="card-link" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <h3 class="card-title"><a class="stretched-link" href="oferta.php">Desarrollo Comunitario</a></h3>
            <p class="card-text">Proyectos sociales para la comunidad.</p>
            <span class="card-link" aria-hidden="true">Ver detalle</span>
          </article>
        </li>
      </ul>
    </div>
  </section>

  <!-- Nuevo ingreso -->
  <section class="section section-alt" aria-labelledby="admission-title">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title" id="admission-title">Nuevo ingreso en 4 pasos</h2>
          <p class="section-subtitle">Las fechas se publicarán aquí.</p>
        </div>
        <a class="btn-outline-main" href="nuevo-ingreso.php">Ver requisitos y fechas</a>
      </div>
      <ol class="steps">
        <li class="step"><strong>Ficha</strong><span>Solicítala en el plantel.</span></li>
        <li class="step"><strong>Documentos</strong><span>Entrega tus documentos.</span></li>
        <li class="step"><strong>Evaluación</strong><span>Presenta el examen diagnóstico.</span></li>
        <li class="step"><strong>Inscripción</strong><span>Confirma tu lugar.</span></li>
      </ol>
    </div>
  </section>

  <!-- Noticias -->
  <section class="section" aria-labelledby="news-title">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title" id="news-title">Noticias y avisos</h2>
          <p class="section-subtitle">Lo más reciente del plantel.</p>
        </div>
        <a class="btn-outline-main" href="noticias.php">Ver todas</a>
      </div>
      <ul class="card-grid">
        <li>
          <article class="card-item">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Festejo del Día del Estudiante (foto por agregar)" loading="lazy">
            <div class="card-meta"><span class="tag tag-info">Evento</span><time datetime="2026-05-22">22 de mayo de 2026</time></div>
            <h3 class="card-title"><a class="stretched-link" href="noticias.php">Festejo del Día del Estudiante</a></h3>
            <p class="card-text">Estudiantes participaron en el festejo municipal.</p>
            <span class="card-link" aria-hidden="true">Leer más</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Preinscripciones 2026 (foto por agregar)" loading="lazy">
            <div class="card-meta"><span class="tag tag-warn">Aviso</span><time datetime="2026-01-22">22 de enero de 2026</time></div>
            <h3 class="card-title"><a class="stretched-link" href="noticias.php">Preinscripciones 2026</a></h3>
            <p class="card-text">Fechas, requisitos y carreras disponibles.</p>
            <span class="card-link" aria-hidden="true">Leer más</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Graduación 2025 (foto por agregar)" loading="lazy">
            <div class="card-meta"><span class="tag tag-ok">Logro</span><time datetime="2025-07-18">18 de julio de 2025</time></div>
            <h3 class="card-title"><a class="stretched-link" href="noticias.php">387 estudiantes se graduaron</a></h3>
            <p class="card-text">La generación concluyó su bachillerato.</p>
            <span class="card-link" aria-hidden="true">Leer más</span>
          </article>
        </li>
      </ul>
    </div>
  </section>

  <!-- Vida estudiantil -->
  <section class="section section-alt" aria-labelledby="life-title">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title" id="life-title">Vida estudiantil</h2>
          <p class="section-subtitle">Clubes, actividades y servicios del plantel.</p>
        </div>
        <a class="btn-outline-main" href="clubes.php">Ver todo</a>
      </div>
      <ul class="card-grid">
        <li>
          <article class="card-item">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Desfile de cuadrillas del Xantolo (foto por agregar)" loading="lazy">
            <div class="card-meta"><span class="tag">Actividad</span></div>
            <h3 class="card-title"><a class="stretched-link" href="clubes.php">Desfile de cuadrillas del Xantolo</a></h3>
            <p class="card-text">Participación en el desfile estudiantil.</p>
            <span class="card-link" aria-hidden="true">Ver más</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Feria Didáctica (foto por agregar)" loading="lazy">
            <div class="card-meta"><span class="tag">Actividad</span></div>
            <h3 class="card-title"><a class="stretched-link" href="clubes.php">Feria Didáctica</a></h3>
            <p class="card-text">Visita a la Escuela Normal de las Huastecas.</p>
            <span class="card-link" aria-hidden="true">Ver más</span>
          </article>
        </li>
        <li>
          <article class="card-item">
            <img class="photo" src="assets/img/placeholder.svg" width="640" height="360" alt="Orientación educativa (foto por agregar)" loading="lazy">
            <div class="card-meta"><span class="tag">Servicio</span></div>
            <h3 class="card-title"><a class="stretched-link" href="servicios.php">Orientación educativa</a></h3>
            <p class="card-text">Apoyo para estudiantes y familias.</p>
            <span class="card-link" aria-hidden="true">Ver más</span>
          </article>
        </li>
      </ul>
    </div>
  </section>

  <!-- Contáctanos: solo formulario y mapa. Teléfono, correo y dirección van en el footer -->
  <section class="section" aria-labelledby="contact-title">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title" id="contact-title">Contáctanos</h2>
          <p class="section-subtitle">Te respondemos a tu correo con un folio de seguimiento.</p>
        </div>
      </div>
      <div class="contact-box">
        <form class="contact-form" action="#" method="post" novalidate>
          <h3>Envíanos un mensaje</h3>
          <p class="field-hint mb-3">Los campos marcados con * son obligatorios.</p>

          <div class="field">
            <label for="name">Nombre completo *</label>
            <input class="field-control" id="name" name="name" type="text" placeholder="Ej. Ana Pérez López" autocomplete="name" required>
          </div>
          <div class="field">
            <label for="email">Correo electrónico *</label>
            <input class="field-control" id="email" name="email" type="email" placeholder="nombre@correo.com" autocomplete="email" required>
          </div>
          <div class="field">
            <label for="message">Mensaje *</label>
            <textarea class="field-control" id="message" name="message" maxlength="600" placeholder="Escribe tu duda o comentario…" required></textarea>
            <p class="field-hint">Máximo 600 caracteres.</p>
          </div>

          <button class="btn-main" type="submit">Enviar mensaje</button>
        </form>

        <iframe class="contact-map" title="Mapa de ubicación del CBTa No. 5"
                src="https://www.google.com/maps?q=CBTa+No.+5+Huejutla+de+Reyes+Hidalgo&amp;output=embed"
                loading="lazy"></iframe>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
