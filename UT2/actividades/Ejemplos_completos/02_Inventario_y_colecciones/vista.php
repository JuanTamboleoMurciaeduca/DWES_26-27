<?php
declare(strict_types=1);

require __DIR__ . '/datos.php';
require __DIR__ . '/funciones.php';

$visibles = seleccionar($inventario, ' TECLADO ');
usort($visibles, fn (array $a, array $b): int => $a['precio'] <=> $b['precio']);

// La búsqueda termina en una presentación HTML; nunca se imprime texto sin escapar.
header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="es">
<meta charset="utf-8">
<title>Inventario: teclados disponibles</title>
<h1>Teclados disponibles</h1>
<ul>
<?php foreach (etiquetas($visibles) as $etiqueta): ?>
    <li><?= htmlspecialchars($etiqueta, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
<?php endforeach; ?>
</ul>
</html>
