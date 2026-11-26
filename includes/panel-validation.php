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
