<?php

declare(strict_types=1);

require_once __DIR__ . '/Libro.php';
session_start();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    exit('Método no permitido');
}

$libros = $_SESSION['libros'] ?? [];

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        http_response_code(400);
        exit('ID no válido');
    }
    foreach ($libros as $libro) {
        if ($libro->id === $id) {
            echo 'Libro encontrado: ' . e($libro->titulo);
            exit;
        }
    }
    http_response_code(404);
    exit('Libro no encontrado');
}

echo '<h1>Libros de esta sesión</h1>';
foreach ($libros as $libro) {
    echo '<p><a href="libros.php?id=' . $libro->id . '">' . e($libro->titulo) . '</a></p>';
}
echo '<p><a href="libro-formulario.php">Crear otro libro</a></p>';
