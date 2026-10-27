<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$entrada = $_POST['titulo'] ?? '';
if (!is_string($entrada)) {
    http_response_code(400);
    exit('El título debe ser texto');
}

$titulo = trim($entrada);
if ($titulo === '') {
    http_response_code(400);
    exit('El título no puede estar vacío');
}

echo 'Libro recibido: ' .
    htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
