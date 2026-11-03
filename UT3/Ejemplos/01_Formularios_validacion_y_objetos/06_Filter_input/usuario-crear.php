<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$entradaNombre = $_POST['nombre'] ?? '';
if (!is_string($entradaNombre)) {
    http_response_code(400);
    exit('El nombre debe ser texto');
}
$nombre = trim($entradaNombre);

// filter_input lee el dato original de la petición.
$correo = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);

if ($nombre === '') {
    http_response_code(400);
    exit('El nombre no puede estar vacío');
}
if ($correo === false || $correo === null) {
    http_response_code(400);
    exit('El correo electrónico no es válido');
}
if ($edad === false || $edad === null || $edad < 18) {
    http_response_code(400);
    exit('Debes tener al menos 18 años');
}

echo 'Nombre: ' . htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo '<br>Correo: ' . htmlspecialchars($correo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo '<br>Edad: ' . $edad;
