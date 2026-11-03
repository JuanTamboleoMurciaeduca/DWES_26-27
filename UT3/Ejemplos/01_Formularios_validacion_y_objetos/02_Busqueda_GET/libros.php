<?php

declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    exit('Método no permitido');
}

// Catálogo fijo para practicar GET sin sesiones ni base de datos.
$libros = ['Dune', 'El Hobbit', '1984', 'Fundación', 'Neuromante'];

$entrada = $_GET['q'] ?? '';
if (!is_string($entrada)) {
    http_response_code(400);
    exit('La búsqueda debe ser texto');
}

$busqueda = trim($entrada);

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Buscar libros (GET)</title></head>
<body>
    <h1>Buscar libros</h1>
    <form method="get" action="libros.php">
        <label for="q">Título</label>
        <input id="q" name="q" type="text" value="<?= e($busqueda) ?>">
        <button type="submit">Buscar</button>
    </form>
    <p>Prueba una búsqueda: la URL incluirá el parámetro <code>q</code>.</p>
    <h2>Resultados</h2>
    <ul>
        <?php
        $encontrados = 0;
        foreach ($libros as $titulo):
            if ($busqueda !== '' && stripos($titulo, $busqueda) === false) {
                continue;
            }
            $encontrados++;
        ?>
            <li><?= e($titulo) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php if ($encontrados === 0): ?>
        <p>No hay resultados.</p>
    <?php endif; ?>
</body>
</html>
