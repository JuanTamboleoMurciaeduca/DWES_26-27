<?php

declare(strict_types=1);

require_once __DIR__ . '/Validator.php';

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($metodo !== 'GET' && $metodo !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$entrada = $metodo === 'POST' ? ($_POST['titulo'] ?? '') : '';
$titulo = is_string($entrada) ? trim($entrada) : '';
$error = null;

if ($metodo === 'POST') {
    $validador = new Validator();
    $error = !is_string($entrada)
        ? 'El título debe ser texto.'
        : $validador->requerido($titulo, 'Escribe el título.');
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Introducción a Validator</title></head>
<body>
    <h1>Regla reutilizable Validator::requerido()</h1>
    <form method="post" action="ejemplo.php">
        <label for="titulo">Título del libro</label>
        <input id="titulo" name="titulo" type="text" value="<?= e($titulo) ?>">
        <button type="submit">Validar</button>
    </form>
    <?php if ($error !== null): ?>
        <p><?= e($error) ?></p>
    <?php elseif ($metodo === 'POST'): ?>
        <p>Título válido: <?= e($titulo) ?></p>
    <?php endif; ?>
</body>
</html>
