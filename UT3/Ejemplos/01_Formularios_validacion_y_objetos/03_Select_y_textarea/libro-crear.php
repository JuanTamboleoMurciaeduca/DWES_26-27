<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$genero = $_POST['genero'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

$permitidos = ['ciencia', 'fantasia'];
if (!is_string($genero) || !in_array($genero, $permitidos, true)) {
    http_response_code(400);
    exit('Género no válido');
}
if (!is_string($observaciones)) {
    http_response_code(400);
    exit('Las observaciones deben ser texto');
}
$observaciones = trim($observaciones);

echo 'Género: ' . htmlspecialchars($genero, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo '<br>';
echo 'Observaciones: ' . nl2br(
    htmlspecialchars($observaciones, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
);
