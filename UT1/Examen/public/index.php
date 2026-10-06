<?php

declare(strict_types=1);


// Importar librerías
require_once __DIR__ . '/../src/datos.php';
require_once __DIR__ . '/../src/funciones.php';

date_default_timezone_set('Europe/Madrid');

$genero = normalizarTexto($_GET['genero'] ?? 'todos');
$plataforma = normalizarTexto($_GET['plataforma'] ?? 'todas');
$busqueda = normalizarTexto($_GET['q'] ?? '');
$orden = normalizarTexto($_GET['orden'] ?? 'titulo');

if (!array_key_exists($plataforma, $plataformas))
    $plataforma = 'todas';

if (!in_array($orden, ['titulo', 'precio', 'puntuacion'], true))
    $orden = 'titulo';

$resultados = $videojuegos;

if ($genero !== 'todos')
    $resultados = filtrarPorGenero($resultados, $genero);
if ($plataforma !== 'todas')
    $resultados = filtrarPorPlataforma($resultados, $plataforma);
if ($busqueda !== '')
    $resultados = buscarPorTexto($resultados, $busqueda);

$resultados = ordenarVideojuegos($resultados, $orden);

$ventasOrdenadas = $ventasSemana;
$plataformasOrdenadas = $plataformas;

ksort($plataformasOrdenadas);
asort($ventasOrdenadas);


$timestampConsulta = time();
$fechaConsulta = date('d/m/Y H:i', $timestampConsulta);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Catálogo de videojuegos</title>
</head>

<body>
    <h1>Catálogo de videojuegos</h1>

    <form method="get">
        <label>
            Género:
            <input type="text" name="genero" value="<?= htmlspecialchars($genero) ?>">
        </label>

        <label>
            Plataforma:
            <select name="plataforma">
                <option value="todas">Todas</option>
                <?php foreach ($plataformas as $codigo => $nombre): ?>
                    <option value="<?= htmlspecialchars($codigo) ?>">
                        <?= htmlspecialchars($nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Buscar:
            <input type="text" name="q" value="<?= $busqueda ?>">
        </label>

        <label>
            Orden:
            <select name="orden">
                <option value="titulo">Título</option>
                <option value="precio">Precio</option>
                <option value="puntuacion">Puntuación</option>
            </select>
        </label>

        <button type="submit">Aplicar</button>
    </form>

    <p>Resultados: <?= count($resultados) ?></p>

    <ul>
        <?php foreach ($resultados as $videojuego): ?>
            <li>
                <a href="videojuego.php?id=<?= $videojuego['id'] ?>">
                    <?= htmlspecialchars($videojuego['titulo']) ?>
                </a>
                · <?= $videojuego['genero'] ?>
                · <?= $videojuego['plataforma'] ?>
                · <?= number_format($videojuego['precio'], 2, ',', '.') ?> €
                · <?= $videojuego['puntuacion'] ?>/10
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Plataformas por código</h2>
    <ul>
        <?php foreach ($plataformasOrdenadas as $codigo => $nombre): ?>
            <li><?= htmlspecialchars((string) $codigo) ?>: <?= htmlspecialchars((string) $nombre) ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Ventas de la semana</h2>
    <ul>
        <?php foreach ($ventasOrdenadas as $codigo => $ventas): ?>
            <li><?= htmlspecialchars((string) $codigo) ?>: <?= $ventas ?></li>
        <?php endforeach; ?>
    </ul>

    <p>Consulta generada: <?= htmlspecialchars($fechaConsulta) ?></p>
</body>

</html>