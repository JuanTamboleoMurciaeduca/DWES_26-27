<?php

declare(strict_types=1);

use App\Modelo\Libro;

require_once __DIR__ . '/src/Modelo/Libro.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Libro sin formulario · UT3</title></head>
<body>
    <h1>Crear Libro directamente</h1>
    <p>Aquí no intervienen HTML, JavaScript ni LibroFormService.
        Las dos llamadas reciben un entero, pero solo una cumple las reglas del objeto.</p>
    <pre>new Libro('Dune', 'Frank Herbert', 412);
new Libro('Dune', 'Frank Herbert', -20);</pre>
    <?php foreach ([412, -20] as $paginas): ?>
        <h2>Páginas: <?= $paginas ?></h2>
        <?php

        try {
            $libro = new Libro('Dune', 'Frank Herbert', $paginas);
            echo '<p>Objeto creado con ' . $libro->paginas() . ' páginas.</p>';
        } catch (InvalidArgumentException $excepcion) {
            echo '<p>Objeto rechazado: ' . htmlspecialchars(
                $excepcion->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'
            ) . '</p>';
        }
        ?>
    <?php endforeach; ?>
    <p><a href="ejemplo.php">Volver al formulario</a></p>
</body>
</html>
