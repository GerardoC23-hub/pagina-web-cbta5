<?php
// Validaciones de texto del panel. Regresa el mensaje de error, o '' si está bien.

// Texto con largo mínimo y máximo, que lleve letras (no solo números ni símbolos).
// $label va con artículo: 'el título', 'la descripción'. Si no es obligatorio, vacío es válido.
function textError(string $value, string $label, int $min, int $max, bool $required = true): string
{
  $name = ucfirst($label);

  if ($value === '') {
    return $required ? "Escribe $label." : '';
  }
  if (mb_strlen($value) < $min) {
    return "$name debe tener al menos $min caracteres.";
  }
  if (mb_strlen($value) > $max) {
    return "$name admite máximo $max caracteres.";
  }
  if (!preg_match('/\p{L}/u', $value)) {
    return "$name debe llevar letras, no solo números o símbolos.";
  }
  return '';
}

// Nombre de una persona: letras, espacios, puntos, apóstrofes y guiones (sin números)
function nameError(string $value, int $min = 3, int $max = 120): string
{
  if ($value === '') {
    return 'Escribe el nombre.';
  }
  if (mb_strlen($value) < $min) {
    return "El nombre debe tener al menos $min caracteres.";
  }
  if (mb_strlen($value) > $max) {
    return "El nombre admite máximo $max caracteres.";
  }
  if (!preg_match('/^\p{L}[\p{L}\s.\'’\-]*$/u', $value)) {
    return 'El nombre solo lleva letras, espacios, puntos y guiones.';
  }
  return '';
}

// Número entero entre $min y $max (vacío cuenta como 0)
function orderError(string $value, int $min = 0, int $max = 999): string
{
  if ($value === '') {
    return '';
  }
  if (!ctype_digit($value) || (int) $value < $min || (int) $value > $max) {
    return "El orden es un número de $min a $max.";
  }
  return '';
}

// Atributos HTML de un campo de texto, iguales a las reglas de textError() para que el navegador
// avise lo mismo que el servidor: largo, que lleve letras, y un mensaje específico bajo el campo.
// Ejemplo de uso en una vista: textRules('el título', 3, 150) dentro de la etiqueta input
function textRules(string $label, int $min, int $max, bool $required = true, bool $letters = true): string
{
  $name  = ucfirst($label);
  $attrs = ' minlength="' . $min . '" maxlength="' . $max . '"';

  if ($letters) {
    $attrs .= ' pattern=".*\p{L}.*" data-msg-pattern="' . htmlspecialchars("$name debe llevar letras, no solo números o símbolos.", ENT_QUOTES, 'UTF-8') . '"';
  }
  $attrs .= ' data-msg-min="' . htmlspecialchars("$name debe tener al menos $min caracteres.", ENT_QUOTES, 'UTF-8') . '"';

  if ($required) {
    $attrs .= ' data-msg-required="' . htmlspecialchars("Escribe $label.", ENT_QUOTES, 'UTF-8') . '" required';
  }
  return $attrs;
}
