<?php

declare(strict_types=1);

$ejemplos = require __DIR__ . '/ejemplos.php';
$numero = $_GET['n'] ?? '';

if (!is_string($numero) || !isset($ejemplos[$numero])) {
    http_response_code(404);
    exit('Ejemplo no encontrado.');
}

$ejemplo = $ejemplos[$numero];

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($ejemplo['numero'] . ' · ' . $ejemplo['titulo']) ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <p><a href="index.php">Índice de ejemplos</a></p>
    <h1><?= e($ejemplo['numero'] . ' · ' . $ejemplo['titulo']) ?></h1>
    <p><?= e($ejemplo['concepto']) ?></p>
    <p>En la terminal, desde <code>03_Pruebas_unitarias_PHPUnit</code>:</p>
    <pre><code>composer test -- <?= e($ejemplo['carpeta']) ?></code></pre>
    <p><?= e($ejemplo['esperado']) ?></p>

    <?php foreach ($ejemplo['archivos'] as $archivo): ?>
        <?php
        // La ruta procede de la lista fija de ejemplos, no de un parámetro de archivo.
        $ruta = __DIR__ . '/../' . $ejemplo['carpeta'] . '/' . $archivo;
        $codigo = file_get_contents($ruta);

        if ($codigo === false) {
            throw new RuntimeException('No se pudo leer el código del ejemplo.');
        }
        ?>
        <h2><?= e($archivo) ?></h2>
        <pre><code><?= e($codigo) ?></code></pre>
    <?php endforeach; ?>

    <?php if ($ejemplo['nota'] !== ''): ?>
        <p class="nota"><?= e($ejemplo['nota']) ?></p>
    <?php endif; ?>

    <p><code><?= e($ejemplo['carpeta']) ?></code> · Diapositivas <?= e($ejemplo['diapositivas']) ?></p>
</body>
</html>
