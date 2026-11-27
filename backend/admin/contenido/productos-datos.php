<?php
// Datos de la lista de Productos del panel: lee la base y deja las variables listas para la vista.
// Deja: $products, $total

require_once __DIR__ . '/../../../includes/site-data.php';

$sql      = "SELECT id_producto, nombre, precio, unidad, disponible
             FROM tblweb_productos
             ORDER BY orden ASC, nombre ASC";
$products = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

// Precio listo para mostrar: "$25.00 / kg" (vacío si no tiene precio)
foreach ($products as &$product) {
  $price = $product['precio'] === null ? '' : '$' . number_format((float) $product['precio'], 2);
  $unit  = trim((string) $product['unidad']);
  $product['price_text'] = ($price !== '' && $unit !== '') ? $price . ' / ' . $unit : $price;
}
unset($product);

$total = count($products);
