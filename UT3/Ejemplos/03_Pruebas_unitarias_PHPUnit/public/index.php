<?php

declare(strict_types=1);

$ejemplos = require __DIR__ . '/ejemplos.php';

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
    <title>UT3 · Presentación 03 · Ejemplos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Presentación 03 · Pruebas unitarias con PHPUnit</h1>
    <p>Ejemplos pequeños en el orden de la presentación. Cada carpeta trata un concepto
        y contiene un test breve con el código mínimo que necesita.</p>

    <ol>
        <?php foreach ($ejemplos as $ejemplo): ?>
            <li>
                <a href="ejemplo.php?n=<?= e($ejemplo['numero']) ?>"><?= e($ejemplo['titulo']) ?></a>
                (diap. <?= e($ejemplo['diapositivas']) ?>)<?= $ejemplo['numero'] === '03' ? ' · fallo deliberado' : '' ?>
            </li>
        <?php endforeach; ?>
    </ol>

    <h2>Preparación común</h2>
    <p>Abre una terminal en <code>03_Pruebas_unitarias_PHPUnit</code>.
        Necesitas PHP CLI 8.3 o superior y Composer. Instala las dependencias una vez:</p>
    <pre><code>composer install</code></pre>
    <p>Después ejecuta el comando del ejemplo que vayas a explicar. Por ejemplo:</p>
    <pre><code>composer test -- 01_Primera_prueba</code></pre>
    <p>Las carpetas son independientes. El código de un ejemplo no carga el de otro.
        Solo comparten la instalación de PHPUnit.</p>

    <h2>Abrir este índice</h2>
    <p>Desde la carpeta del bloque 03:</p>
    <pre><code>php -S 127.0.0.1:8004 -t public</code></pre>
    <p>Abre <a href="http://127.0.0.1:8004/">http://127.0.0.1:8004/</a>.
        El navegador permite leer cada ejemplo. Los tests se ejecutan en la terminal.</p>
    <p><code>composer test</code> ejecuta todos los ejemplos correctos.
        El ejemplo 03 queda excluido porque falla deliberadamente.</p>
</body>
</html>
