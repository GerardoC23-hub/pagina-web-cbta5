<?php
$base        = '';
$pagina      = 'nosotros';
$title       = 'CBTa No. 5 · Huejutla de Reyes, Hgo.';
$description = 'Conócenos: historia, mision, vision y valores del CBTa No. 5 de Huejutla de Reyes, Hidalgo.';
$styles      = ['pages/about.css'];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?><!-- papi dijo que reutilizaramos header y footer -->


<main id="principal">

  <!-- Este es mi encabezado de la pagina -->
  <section class="bg-brand text-white border-bottom border-4 border-gold" aria-labelledby="about-title">
    <div class="container py-5">
      <h1 class="text-white mb-2" id="about-title">Nosotros </h1>
      <p class="lead mb-0">Conoce la historia, la misión y los valores del plantel.</p>
    </div>
  </section>

<!-- esta es la parte de quienes somos + información del plantel -->
<section class="section" aria-labelledby="who-title">
    <div class="container">
      <div class="row g-4 g-lg-5">

        <div class="col-lg-7">
          <h2 class="section-title" id="who-title">Quiénes somos</h2>
          <p>
            El CBTa No. 5 inició actividades el 2 de octubre de 1972 en Huejutla de Reyes, Hidalgo,
            al norte de la ciudad, como Centro de Estudios Tecnológicos Agropecuarios (CETA 5).
            Comenzó con las especialidades de técnico agrícola y pecuario y, con los años,
            fue sumando otras hasta las ocho carreras actuales.
          </p>
        </div>

        <div class="col-lg-5">
          <dl class="bg-success-subtle rounded p-4 mb-0">
            <dt class="small text-uppercase text-success">Nombre</dt>
            <dd class="fw-medium">Centro de Bachillerato Tecnológico Agropecuario No. 5 «Hermanos Flores Magón»</dd>

            <dt class="small text-uppercase text-success">Clave (CCT)</dt>
            <dd class="fw-medium">13DTA0005I</dd>

            <dt class="small text-uppercase text-success">Inicio de actividades</dt>
            <dd class="fw-medium">2 de octubre de 1972</dd>

            <dt class="small text-uppercase text-success">Subsistema</dt>
            <dd class="fw-medium mb-0">DGETAyCM · SEP</dd>
          </dl>
        </div>

      </div>
    </div>
  </section>

  <!-- esta es la parte de misión, visión y valores -->
   <section class="pb-5" aria-label="Misión y visión">
    <div class="container">
      <div class="row g-4">

        <div class="col-md-6">
          <article class="card mission-card h-100 p-4">
            <span class="rounded-circle bg-success-subtle text-success d-inline-flex p-3 mb-3 align-self-start" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 4h7a3 3 0 0 1 3 3v13a2 2 0 0 0-2-2H2z"></path>
                <path d="M22 4h-7a3 3 0 0 0-3 3v13a2 2 0 0 1 2-2h8z"></path>
              </svg>
            </span>
            <h2 class="h4">Misión</h2>
            <p class="mb-0">
              Formar técnicos de nivel medio superior con una educación integral, pertinente y de calidad,
              que combine conocimientos científicos, tecnológicos y humanistas con la práctica, para que
              los jóvenes de la Huasteca continúen sus estudios, se incorporen al trabajo productivo y
              contribuyan al desarrollo sustentable de su comunidad.
            </p>
          </article>
        </div>

        <div class="col-md-6">
          <article class="card vision-card h-100 p-4">
            <span class="rounded-circle bg-danger-subtle text-danger d-inline-flex p-3 mb-3 align-self-start" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </span>
            <h2 class="h4">Visión</h2>
            <p class="mb-0">
              Ser un plantel reconocido por su excelencia académica, su vinculación con el sector
              productivo y la comunidad, y su formación en valores, que prepara egresados competentes,
              responsables y comprometidos con el cuidado del ambiente y el desarrollo de la región.
            </p>
          </article>
        </div>

      </div>
      <p class="small text-body-secondary mt-3 mb-0">
        Textos de referencia basados en los fines generales del bachillerato tecnológico agropecuario; el plantel los validará.
      </p>
    </div>
  </section>

  <!-- esta es la parte de valores -->
  <section class="pb-5" aria-labelledby="values-title">
    <div class="container">
      <h2 class="section-title" id="values-title">Valores</h2>
      <p>Lema: <strong>Respeto, autoestima y excelencia académica</strong></p>

      <ul class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 list-unstyled mb-0">

        <li class="col">
          <div class="card value-item h-100 p-3">
            <strong class="text-success">Respeto</strong>
            <span class="small text-body-secondary">Fomentar la tolerancia, la empatía y una convivencia sana entre toda la comunidad escolar.</span>
          </div>
        </li>

        <li class="col">
          <div class="card value-item h-100 p-3">
            <strong class="text-success">Autoestima</strong>
            <span class="small text-body-secondary">Fortalecer la confianza personal, el reconocimiento de las propias capacidades y el desarrollo integral.</span>
          </div>
        </li>

        <li class="col">
          <div class="card value-item h-100 p-3">
            <strong class="text-success">Excelencia académica</strong>
            <span class="small text-body-secondary"> Impulsar el aprendizaje continuo, el esfuerzo y la formación de estudiantes competentes.</span>
          </div>
        </li>

        <li class="col">
          <div class="card value-item h-100 p-3">
            <strong class="text-success">Compromiso ambiental</strong>
            <span class="small text-body-secondary">Promover el cuidado del medio ambiente mediante acciones responsables y sostenibles.</span>
          </div>
        </li>

      </ul>
    </div>
  </section>

  <!-- la parte de la historia del cbta5 -->
  <section class="pb-5" aria-labelledby="history-title">
    <div class="container">
      <h2 class="section-title" id="history-title">Nuestra historia</h2>

      <ol class="list-unstyled ms-2 mb-3">

        <li class="position-relative border-start border-3 border-success ps-4 pb-4">
          <span class="timeline-dot position-absolute top-0 start-0 translate-middle mt-2 rounded-circle bg-success p-1"></span>
          <strong class="d-block">1972</strong>
          Inicia actividades como CETA 5, con las especialidades de técnico agrícola y pecuario.
        </li>

        <li class="position-relative border-start border-3 border-success ps-4 pb-4">
          <span class="timeline-dot position-absolute top-0 start-0 translate-middle mt-2 rounded-circle bg-success p-1"></span>
          <strong class="d-block">1976</strong>
          Se incorpora la especialidad de industrias agropecuarias.
        </li>

        <li class="position-relative border-start border-3 border-success ps-4 pb-4">
          <span class="timeline-dot position-absolute top-0 start-0 translate-middle mt-2 rounded-circle bg-success p-1"></span>
          <strong class="d-block">1980</strong>
          Se amplía la oferta con nuevas especialidades agropecuarias.
        </li>

        <li class="position-relative border-start border-3 border-success ps-4">
          <span class="timeline-dot position-absolute top-0 start-0 translate-middle mt-2 rounded-circle bg-success p-1"></span>
          <strong class="d-block">Hoy</strong>
          8 carreras técnicas; en 2025 se graduaron 387 estudiantes.
        </li>

      </ol>

      <p class="small text-body-secondary mb-0">
        Fuente: antecedentes históricos del sitio anterior del plantel (cbta5.net) y Zunoticia.
      </p>
    </div>
  </section>
    <!-- este es el enlace para el directorio -->
  <section aria-label="Personal y directorio">
    <div class="container">
      <div class="card p-4 flex-row flex-wrap align-items-center justify-content-between gap-3">
        <div>
          <h2 class="h4 mb-1">Personal y directorio</h2>
          <p class="text-body-secondary mb-0">Directivos, docentes y departamentos del plantel.</p>
        </div>
        <a class="btn btn-outline-primary" href="personal.php">Ver directorio</a>
      </div>
    </div>
  </section>
  <?php require __DIR__ . '/includes/footer.php'; ?>