// Show / hide the password. Buttons use data-toggle-password="<input id>".
document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
  button.addEventListener('click', function () {
    var input = document.getElementById(button.dataset.togglePassword);
    var show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    button.textContent = show ? 'Ocultar' : 'Mostrar';
    button.setAttribute('aria-pressed', show ? 'true' : 'false');
  });
});
