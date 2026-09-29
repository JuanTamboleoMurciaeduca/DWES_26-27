<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/datos.php';
require_once __DIR__ . '/../src/funciones.php';

$id = (int) ($_GET['id'] ?? 0);

$libro = buscarPorId($libros, $id);
if ($libro === null) {
    echo 'Libro no encontrado';
}

$hoy = new DateTimeImmutable('today');
$fechaAlta = new DateTimeImmutable($libro['fechaAlta']);
$diasDesdeAlta = (int) $fechaAlta->diff($hoy)->format('%a');
$fechaDevolucion = null;
if ($libro['disponible']) {
    $fechaDevolucion = $hoy->modify('+15 days');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($libro['titulo']) ?></title>
</head>

<body>
    <h1><?= htmlspecialchars($libro['titulo']) ?></h1>
    <p><strong>ID:</strong> <?= $libro['id'] ?></p>
    <p><strong>Autor:</strong> <?= htmlspecialchars($libro['autor']) ?></p>
    <p><strong>Género:</strong> <?= htmlspecialchars($libro['genero']) ?></p>
    <p><strong>Páginas:</strong> <?= $libro['paginas'] ?></p>
    <p><strong>Disponible:</strong> <?= $libro['disponible'] ? 'Sí' : 'No' ?></p>
    <p><strong>Fecha de alta:</strong> <?= htmlspecialchars($libro['fechaAlta']) ?></p>
    <p><strong>Días desde el alta:</strong> <?= $diasDesdeAlta ?></p>
    <?php if ($fechaDevolucion !== null): ?>
        <p>
            <strong>Fecha de devolución simulada:</strong>
            <?= $fechaDevolucion->format('d/m/Y') ?>
        </p>
    <?php endif; ?>
    <p><a href="index.php">Volver al catálogo</a></p>
</body>

</html>