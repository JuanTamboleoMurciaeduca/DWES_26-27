<?php
declare(strict_types=1);
// Enrutador del servidor local: rutas inexistentes devuelven 404 en vez de cargar
// implícitamente index.php. La raíz sigue siendo public; no se sirven sus padres.
$ruta = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$permitidas = ['/', '/index.php', '/01_recepcion.php', '/02_validar_guardar.php',
    '/03_publicar.php', '/04_libro_portada.php', '/05_varias_portadas.php', '/portada.php'];
if (!in_array($ruta, $permitidas, true)) {
    http_response_code(404);
    exit;
}
return false;
