<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$entradaTitulo = $_POST['titulo'] ?? '';
$entradaFecha = $_POST['fecha'] ?? '';
if (!is_string($entradaTitulo) || trim($entradaTitulo) === '') {
    http_response_code(400);
    exit('El título es obligatorio');
}
if (!is_string($entradaFecha)) {
    http_response_code(400);
    exit('La fecha debe ser texto');
}

$titulo = trim($entradaTitulo);
$textoFecha = trim($entradaFecha);
$fecha = null;

if ($textoFecha !== '') {
    try {
        $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $textoFecha);
    } catch (ValueError) {
        $fecha = false;
    }

    // PHP puede ajustar una fecha imposible, como 2026-02-31.
    if ($fecha === false || $fecha->format('Y-m-d') !== $textoFecha) {
        http_response_code(400);
        exit('La fecha no es válida');
    }
}

echo 'Título: ' . htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo '<br>Fecha: ' . ($fecha?->format('d/m/Y') ?? 'Sin fecha');
