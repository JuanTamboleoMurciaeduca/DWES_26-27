<?php

declare(strict_types=1);

require_once __DIR__ . '/Libro.php';
session_start();

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

$_SESSION['libros'] ??= [];
$_SESSION['siguienteId'] ??= 1;
$id = $_SESSION['siguienteId']++;
$_SESSION['libros'][] = new Libro($id, $titulo);

// POST-Redirect-GET: evita reenviar el POST al recargar el listado.
header('Location: libros.php', true, 303);
exit;
