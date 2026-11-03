<?php
declare(strict_types=1);
require __DIR__ . '/../src/subidas.php';
// Diap. 23-27. Este archivo devuelve bytes de imagen, no una página HTML.
if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'HEAD'], true)) {
    header('Allow: GET, HEAD');
    http_response_code(405);
    exit;
}
$nombre = $_GET['nombre'] ?? '';
if (!nombreValido($nombre)) {
    http_response_code(404);
    exit;
}
$ruta = DIRECTORIO . '/' . $nombre;
if (is_link($ruta) || !is_file($ruta) || !is_readable($ruta)) {
    http_response_code(404);
    exit;
}
if (!class_exists(finfo::class)) {
    http_response_code(503);
    exit;
}
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($ruta);
if ($mime === false || !isset(TIPOS[$mime]) || TIPOS[$mime] !== pathinfo($nombre, PATHINFO_EXTENSION)) {
    http_response_code(404);
    exit;
}
header('Content-Type: ' . $mime);
header('X-Content-Type-Options: nosniff');
if ($_SERVER['REQUEST_METHOD'] !== 'HEAD') {
    // No hacer echo readfile(): añadiría el número de bytes al contenido de la imagen.
    if (@readfile($ruta) === false) {
        error_log('No se pudo enviar una portada.');
    }
}
exit;
