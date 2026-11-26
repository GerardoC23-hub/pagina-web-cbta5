<?php
require __DIR__ . '/../../includes/panel-auth.php';
panel_require_login();

// Datos que muestra la pantalla
require __DIR__ . '/../../backend/admin/informacion/personal-formulario-datos.php';

// Configuración de la página
$base      = '../../';
$title     = ($isEditing ? 'Editar persona' : 'Agregar persona') . ' · Panel de administración';
$styles    = ['pages/panel.css'];
$scripts   = ['panel-forms.js'];
$withSite  = false;
$noindex   = true;
$panelPage = 'personal';

require __DIR__ . '/../../includes/head.php';
require __DIR__ . '/../../includes/panel-layout-start.php';
?>

      <h1 class="h3 fw-bold border-start border-4 border-danger ps-3"><?= $isEditing ? 'Editar persona' : 'Agregar persona' ?></h1>
      <p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>

<?php if ($error !== ''): ?>
      <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
<?php endif; ?>

      <form class="needs-validation" action="../../backend/admin/informacion/personal-guardar.php" method="post" enctype="multipart/form-data" novalidate>
<?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
<?php endif; ?>

        <section class="card mb-4" aria-labelledby="data-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="data-title">Datos</h2>
            <div class="row g-3">

              <div class="col-md-8">
                <label class="form-label" for="nombre">Nombre *</label>
                <input class="form-control<?= isset($errors['nombre']) ? ' is-invalid' : '' ?>" id="nombre" name="nombre" type="text" minlength="3" maxlength="120" pattern="[\p{L}][\p{L}\s.'’\-]*" data-msg-required="Escribe el nombre." data-msg-min="El nombre debe tener al menos 3 caracteres." data-msg-pattern="El nombre solo lleva letras, espacios, puntos y guiones." value="<?= e($person['nombre']) ?>" required>
                <div class="invalid-feedback"><?= e($errors['nombre'] ?? 'Nombre: de 3 a 120 caracteres.') ?></div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="tipo">Área *</label>
                <select class="form-select<?= isset($errors['tipo']) ? ' is-invalid' : '' ?>" id="tipo" name="tipo" required>
<?php foreach ($types as $type): ?>
                  <option value="<?= $type ?>"<?= $person['tipo'] === $type ? ' selected' : '' ?>><?= $type ?></option>
<?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= e($errors['tipo'] ?? 'Elige un área.') ?></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="cargo">Cargo *</label>
                <input class="form-control<?= isset($errors['cargo']) ? ' is-invalid' : '' ?>" id="cargo" name="cargo" type="text" <?= textRules('el cargo', 3, 100) ?> value="<?= e($person['cargo']) ?>">
                <div class="invalid-feedback"><?= e($errors['cargo'] ?? 'Cargo: de 3 a 100 caracteres.') ?></div>
              </div>

              <div class="col-md-7">
                <label class="form-label" for="correo">Correo institucional</label>
                <input class="form-control<?= isset($errors['correo']) ? ' is-invalid' : '' ?>" id="correo" name="correo" type="email" maxlength="120" data-msg-type="Escribe un correo completo, por ejemplo nombre@cbta5.edu.mx." value="<?= e($person['correo']) ?>">
                <div class="invalid-feedback"><?= e($errors['correo'] ?? 'Correo no válido.') ?></div>
              </div>

              <div class="col-md-5">
                <label class="form-label" for="telefono">Teléfono</label>
                <input class="form-control<?= isset($errors['telefono']) ? ' is-invalid' : '' ?>" id="telefono" name="telefono" type="tel" maxlength="20" pattern="[0-9\s\(\)\+\-]{8,20}" data-msg-pattern="El teléfono tiene de 8 a 20 números (puede llevar espacios, + y -)." value="<?= e($person['telefono']) ?>">
                <div class="invalid-feedback"><?= e($errors['telefono'] ?? 'Teléfono no válido.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="photo-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="photo-title">Foto</h2>
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label" for="foto">Foto</label>
<?php if ($person['foto']): ?>
                <img class="d-block rounded mb-2 object-fit-cover" src="<?= e($base . $person['foto']) ?>" width="96" height="96" alt="Foto actual">
<?php endif; ?>
                <input class="form-control<?= isset($errors['foto']) ? ' is-invalid' : '' ?>" id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" data-max-mb="1" data-msg-type="La foto debe ser JPG, PNG o WebP.">
                <div class="form-text">JPG, PNG o WebP. Máximo 1 MB.<?= $person['foto'] ? ' Déjala vacía para conservar la actual.' : '' ?></div>
                <div class="invalid-feedback"><?= e($errors['foto'] ?? 'Formato no válido (JPG, PNG o WebP).') ?></div>
<?php if ($person['foto']): ?>
                <div class="form-check mt-2">
                  <input class="form-check-input" id="quitar_foto" name="quitar_foto" type="checkbox" value="1">
                  <label class="form-check-label" for="quitar_foto">Quitar la foto actual</label>
                </div>
<?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="foto_credito">Crédito de la foto</label>
                <input class="form-control<?= isset($errors['foto_credito']) ? ' is-invalid' : '' ?>" id="foto_credito" name="foto_credito" type="text" <?= textRules('el crédito', 3, 120, false, true) ?> value="<?= e($person['foto_credito']) ?>">
                <div class="form-text">Quién tomó la foto. Obligatorio si hay foto.</div>
                <div class="invalid-feedback"><?= e($errors['foto_credito'] ?? 'Escribe el autor de la foto.') ?></div>
              </div>

            </div>
          </div>
        </section>

        <section class="card mb-4" aria-labelledby="publish-title">
          <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3" id="publish-title">Publicación</h2>
            <div class="row g-3">

              <div class="col-md-4">
                <label class="form-label" for="orden">Orden</label>
                <input class="form-control<?= isset($errors['orden']) ? ' is-invalid' : '' ?>" id="orden" name="orden" type="number" min="0" max="999" value="<?= (int) $person['orden'] ?>">
                <div class="form-text">El número más bajo sale primero.</div>
                <div class="invalid-feedback"><?= e($errors['orden'] ?? 'Orden: de 0 a 999.') ?></div>
              </div>

              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" id="autorizado" name="autorizado" type="checkbox" value="1"<?= $person['autorizado'] ? ' checked' : '' ?>>
                  <label class="form-check-label" for="autorizado">Autorizado para publicarse en el sitio</label>
                </div>
              </div>

            </div>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-3">
          <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Agregar persona' ?></button>
          <a class="btn btn-outline-primary" href="personal.php">Cancelar</a>
        </div>
      </form>

<?php require __DIR__ . '/../../includes/panel-layout-end.php'; ?>
