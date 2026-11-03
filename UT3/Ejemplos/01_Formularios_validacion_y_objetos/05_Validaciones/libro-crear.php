<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$errores = [];

// 1. Comprobar que el título sea realmente una cadena.
$entradaTitulo = $_POST['titulo'] ?? '';
if (!is_string($entradaTitulo)) {
    $errores['titulo'] = 'El título debe ser texto.';
    $entradaTitulo = '';
}
$titulo = trim($entradaTitulo);

// 2. Validar obligatorio y longitud (en caracteres, no bytes).
if (!isset($errores['titulo'])) {
    if ($titulo === '') {
        $errores['titulo'] = 'Escribe el título.';
    } elseif (mb_strlen($titulo, 'UTF-8') > 120) {
        $errores['titulo'] = 'Máximo 120 caracteres.';
    }
}

// 3. Validar antes de convertir el entero.
$entradaPaginas = $_POST['paginas'] ?? '';
$paginas = is_string($entradaPaginas)
    ? filter_var($entradaPaginas, FILTER_VALIDATE_INT)
    : false;
if ($paginas === false || $paginas < 1 || $paginas > 3000) {
    $errores['paginas'] = 'Entre 1 y 3000 páginas.';
}

// 4. El select no impide manipular los valores enviados.
$genero = $_POST['genero'] ?? '';
$permitidos = ['ciencia', 'fantasia', 'terror'];
if (!is_string($genero) || !in_array($genero, $permitidos, true)) {
    $errores['genero'] = 'Elige un género válido.';
}

// 5. Casilla ausente = false; presente solo puede valer "1".
$marca = $_POST['destacado'] ?? null;
if ($marca !== null && $marca !== '1') {
    $errores['destacado'] = 'Valor de casilla no válido.';
}
$destacado = $marca === '1';

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if ($errores !== []) {
    http_response_code(422);
    echo '<h1>Errores del formulario</h1><ul>';
    foreach ($errores as $campo => $mensaje) {
        echo '<li>' . e($campo) . ': ' . e($mensaje) . '</li>';
    }
    echo '</ul><a href="libro-formulario.php">Volver</a>';
    exit;
}

// Solo con errores=[] disponemos de valores válidos.
echo '<h1>Libro validado</h1>';
echo 'Título: ' . e($titulo) . '<br>';
echo 'Páginas: ' . $paginas . '<br>';
echo 'Género: ' . e($genero) . '<br>';
echo 'Destacado: ' . ($destacado ? 'Sí' : 'No');
