// panel-forms.js · comportamiento de los formularios del panel (demo, sin servidor todavía):
// validación de Bootstrap, llenado de ventanas, confirmación de eliminar y mensajes de éxito/error.
(function () {

  function showToast(message, type) {
    var box = document.getElementById('panel-toasts');
    if (!box) {
      box = document.createElement('div');
      box.id = 'panel-toasts';
      box.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      document.body.appendChild(box);
    }
    var toast = document.createElement('div');
    toast.className = 'toast align-items-center border-0 text-bg-' + type;
    toast.setAttribute('role', type === 'danger' ? 'alert' : 'status');
    toast.innerHTML = '<div class="d-flex"><div class="toast-body"></div>' +
      '<button class="btn-close btn-close-white me-2 m-auto" type="button" data-bs-dismiss="toast" aria-label="Cerrar"></button></div>';
    toast.querySelector('.toast-body').textContent = message;
    box.appendChild(toast);
    toast.addEventListener('hidden.bs.toast', function () { toast.remove(); });
    new bootstrap.Toast(toast, { delay: 4000 }).show();
  }

  // Mensaje específico bajo cada campo, según lo que falló (data-msg-required, -min, -pattern, -type, -range)
  function showFieldMessage(field) {
    var feedback = field.parentElement.querySelector('.invalid-feedback');
    var v = field.validity;
    var key = v.customError ? '' : v.valueMissing ? 'msgRequired' : v.tooShort ? 'msgMin' : v.patternMismatch ? 'msgPattern' :
      v.typeMismatch ? 'msgType' : (v.rangeUnderflow || v.rangeOverflow || v.badInput) ? 'msgRange' : '';
    if (!feedback) { return; }
    if (v.customError) { feedback.textContent = field.validationMessage; }
    else if (key && field.dataset[key]) { feedback.textContent = field.dataset[key]; }
  }

  // Archivos: revisa el formato (accept) y el peso máximo (data-max-mb) antes de enviar
  document.querySelectorAll('input[type="file"][data-max-mb]').forEach(function (input) {
    input.addEventListener('change', function () {
      var file = input.files[0];
      var types = input.accept.split(',').map(function (type) { return type.trim(); });
      var byMime = types.length > 0 && types.every(function (type) { return type.indexOf('/') > 0; });
      var message = '';
      if (file && byMime && types.indexOf(file.type) === -1) {
        message = input.dataset.msgType || 'Formato no permitido.';
      } else if (file && file.size > parseFloat(input.dataset.maxMb) * 1024 * 1024) {
        message = 'El archivo pesa más de ' + input.dataset.maxMb + ' MB.';
      }
      input.setCustomValidity(message);
      showFieldMessage(input);
    });
  });

  // Formularios con validación: mensaje bajo cada campo; si todo está bien, se muestra el éxito
  document.querySelectorAll('form.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        form.querySelectorAll(':invalid').forEach(showFieldMessage);
        var firstInvalid = form.querySelector(':invalid');
        if (firstInvalid) { firstInvalid.focus(); }
        return;
      }
      // Formulario real (action con archivo PHP): se envía al servidor. Con action="#" es solo demostración.
      if (form.getAttribute('action') !== '#') {
        form.submit();
        return;
      }
      var modal = form.closest('.modal');
      if (modal) { bootstrap.Modal.getInstance(modal).hide(); }
      form.classList.remove('was-validated');
      showToast(form.dataset.success || 'Cambios guardados.', 'success');
    });
    form.addEventListener('reset', function () { form.classList.remove('was-validated'); });
    // Mientras corrige, el mensaje bajo el campo cambia según lo que falte
    form.addEventListener('input', function (event) { showFieldMessage(event.target); });
  });

  // Ventanas: título y datos del registro que se abre (data-modal-title y data-record en el botón)
  document.querySelectorAll('.modal').forEach(function (modal) {
    modal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      if (!button) { return; }
      var record = JSON.parse(button.dataset.record || '{}');
      var title = modal.querySelector('.modal-title');
      var form = modal.querySelector('form');

      if (title && button.dataset.modalTitle) { title.textContent = button.dataset.modalTitle; }
      if (form) {
        form.reset();
        Object.keys(record).forEach(function (name) {
          var field = form.elements[name];
          if (field && field.type !== 'file') { field.value = record[name]; }
        });
      }
      modal.querySelectorAll('[data-fill]').forEach(function (element) {
        element.textContent = record[element.dataset.fill] || '';
      });

      // Eliminar: si no se puede (por ejemplo un álbum con fotos) se muestra el motivo y se bloquea el botón
      var blocked = modal.querySelector('[data-blocked]');
      var confirmButton = modal.querySelector('[data-confirm]');
      if (blocked && confirmButton) {
        blocked.textContent = record.blocked || '';
        blocked.classList.toggle('d-none', !record.blocked);
        confirmButton.disabled = !!record.blocked;
        confirmButton.dataset.name = record.name || '';
      }
    });
  });

  document.querySelectorAll('[data-confirm]').forEach(function (button) {
    button.addEventListener('click', function () {
      bootstrap.Modal.getInstance(button.closest('.modal')).hide();
      showToast('Se eliminó «' + button.dataset.name + '».', 'success');
    });
  });

  // Publicar / Despublicar: cambia el texto del botón y la insignia de estado de la fila
  document.querySelectorAll('[data-toggle-status]').forEach(function (button) {
    button.addEventListener('click', function () {
      var badge = button.closest('tr').querySelector('.badge');
      var publish = button.textContent.trim() === 'Publicar';
      badge.textContent = publish ? 'Publicado' : 'Borrador';
      badge.classList.toggle('text-bg-success', publish);
      badge.classList.toggle('text-bg-secondary', !publish);
      button.textContent = publish ? 'Despublicar' : 'Publicar';
      showToast(publish ? 'Se publicó.' : 'Se despublicó. Ya no se ve en el sitio.', 'success');
    });
  });

  // Error de un campo: se quita en cuanto la persona empieza a corregirlo
  document.querySelectorAll('form.needs-validation .is-invalid').forEach(function (field) {
    field.addEventListener('input', function () { field.classList.remove('is-invalid'); });
    field.addEventListener('change', function () { field.classList.remove('is-invalid'); });
  });

})();
