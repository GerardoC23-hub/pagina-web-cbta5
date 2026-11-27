<?php
// Datos de Productos (lista pública): lee la base y deja las variables listas para la vista.
// Deja: $products, $phone

require_once __DIR__ . '/../../includes/site-data.php';

// 1. Solo los productos disponibles
$sql = "SELECT id_producto, nombre, descripcion, precio, unidad, imagen
        FROM tblweb_productos
        WHERE disponible = 1
        ORDER BY orden ASC, nombre ASC";
$products = mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);

// 2. Precio listo para mostrar: "$25.00 / kg" (si el precio es texto, se deja tal cual)
foreach ($products as &$product) {
    $price = trim((string) $product['precio']);
    $unit  = trim((string) $product['unidad']);
    if (is_numeric($price)) {
        $price = '$' . number_format((float) $price, 2);
    }
    $product['price_text'] = ($price !== '' && $unit !== '') ? $price . ' / ' . $unit : $price;
}
unset($product);

// 3. Teléfono del plantel (para el aviso de arriba)
$phone = siteContact()['phone'];
