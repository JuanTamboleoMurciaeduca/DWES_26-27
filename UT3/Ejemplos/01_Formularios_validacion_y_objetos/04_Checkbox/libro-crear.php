<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$entrada = $_POST['titulo'] ?? '';
if (!is_string($entrada) || trim($entrada) === '') {
    http_response_code(400);
    exit('El título no puede estar vacío y debe ser texto');
}
$titulo = trim($entrada);

// Si no está marcado, el navegador no envía la clave.
$marca = $_POST['disponible'] ?? null;
if ($marca !== null && $marca !== '1') {
    http_response_code(400);
    exit('Valor de checkbox no válido');
}
$disponible = $marca === '1';

echo 'Título: ' . htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo '<br>Disponible: ' . ($disponible ? 'Sí' : 'No');
