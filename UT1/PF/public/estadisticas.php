<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/datos.php';
require_once __DIR__ . '/../src/funciones.php';

$total = count($libros);
$disponibles = count(filtrarPorDisponibilidad($libros, true));
$noDisponibles = count(filtrarPorDisponibilidad($libros, false));
$mediaPaginas = calcularMediaPaginas($libros);
$libroMasLargo = obtenerLibroMasLargo($libros);
$librosPorGenero = contarPorGenero($libros);
$ahora = new DateTimeImmutable();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
</head>

<body>
    <h1>Estadísticas</h1>
    <p><strong>Total de libros:</strong> <?= $total ?></p>
    <p><strong>Disponibles:</strong> <?= $disponibles ?></p>
    <p><strong>No disponibles:</strong> <?= $noDisponibles ?></p>
    <p>
        <strong>Media de páginas:</strong>
        <?= number_format($mediaPaginas, 2, ',', '.') ?>
    </p>
    <?php if ($libroMasLargo !== null): ?>
        <p>
            <strong>Libro con más páginas:</strong>
            <?= htmlspecialchars($libroMasLargo['titulo']) ?>
            (<?= $libroMasLargo['paginas'] ?> páginas)
        </p>
    <?php endif; ?>
    <h2>Libros por género</h2>
    <ul>
        <?php foreach ($librosPorGenero as $genero => $cantidad): ?>
            <li><?= htmlspecialchars($genero) ?>: <?= $cantidad ?></li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Informe generado:</strong> <?= $ahora->format('d/m/Y H:i:s') ?></p>
</body>

</html>