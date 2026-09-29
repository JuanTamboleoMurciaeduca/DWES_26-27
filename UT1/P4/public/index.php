<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/datos.php';
require_once __DIR__ . '/../src/funciones.php';

$zonaHoraria = new DateTimeZone('Europe/Madrid');
$hoy = new DateTimeImmutable('today', $zonaHoraria);
$ahora = new DateTimeImmutable('now', $zonaHoraria);
$fechaRevision = $ahora->modify('+30 days');

$genero = strtolower(trim((string) ($_GET['genero'] ?? '')));

$soloDisponibles = ($_GET['disponible'] ?? '') === '1';

$resultados = $libros;

if ($genero !== '') {
    $resultados = filtrarPorGenero($resultados, $genero);
}

if ($soloDisponibles) {
    $resultados = filtrarDisponibles($resultados);
}

$numeroResultados = count($resultados);

$mediaPaginas = calcularMediaPaginas($resultados);

$libroMasLargo = obtenerLibroMasLargo($resultados);

function escapar(string $texto): string
{
    return htmlspecialchars(
        $texto,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Catálogo de libros</title>
</head>

<body>
    <h1>Catálogo de libros</h1>
    <?php if ($genero !== ''): ?>
        <p>Género: <?= escapar($genero) ?></p>
    <?php endif; ?>
    <?php if ($soloDisponibles): ?>
        <p>Filtro: solo libros disponibles.</p>
    <?php endif; ?>
    <p>
        Resultados: <?= $numeroResultados ?>.
        Media de páginas:
        <?= number_format($mediaPaginas, 2, ',', '.') ?>.
    </p>
    <?php if ($libroMasLargo !== null): ?>
        <p>
            Libro con más páginas:
            <strong><?= escapar($libroMasLargo['titulo']) ?></strong>
            (<?= $libroMasLargo['paginas'] ?> páginas).
        </p>
    <?php else: ?>
        <p>No hay libros que cumplan los filtros.</p>
    <?php endif; ?>
    <table border="1" cellpadding="6" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Género</th>
                <th>Páginas</th>
                <th>Disponible</th>
                <th>Fecha de alta</th>
                <th>Días desde el alta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $libro): ?>
                <?php
                $fechaAlta = new DateTimeImmutable($libro['fechaAlta'], $zonaHoraria);
                $diasDesdeAlta = $fechaAlta <= $hoy ? (int) $fechaAlta->diff($hoy)->days : 0;
                ?>
                <tr>
                    <td><?= $libro['id'] ?></td>
                    <td><?= escapar($libro['titulo']) ?></td>
                    <td><?= escapar($libro['autor']) ?></td>
                    <td><?= escapar($libro['genero']) ?></td>
                    <td><?= $libro['paginas'] ?></td>
                    <td>
                        <?= $libro['disponible'] ? 'Sí' : 'No' ?>
                    </td>
                    <td><?= $fechaAlta->format('d/m/Y') ?></td>
                    <td><?= $diasDesdeAlta ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        Próxima revisión del catálogo:
        <?= $fechaRevision->format('d/m/Y H:i') ?>
    </p>
</body>

</html>