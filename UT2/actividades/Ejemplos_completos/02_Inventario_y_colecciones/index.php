<?php
declare(strict_types=1);

require __DIR__ . '/datos.php';
require __DIR__ . '/funciones.php';

// require y __DIR__ solo conectan los archivos de este ejemplo.

echo "1. Seleccionar, transformar y acumular\n";

$visibles = seleccionar($inventario, ' TECLADO ');

echo 'Claves conservadas: ' . implode(', ', array_keys($visibles)) . PHP_EOL;
echo 'Lista reindexada: ' . implode(', ', array_keys(array_values($visibles))) . PHP_EOL;

// Callback por nombre: se pasa sin ejecutarlo.
echo 'Nombres: ' . implode(' / ', transformar($visibles, 'nombreProducto')) . PHP_EOL;

$iva = 0.21;

$calcularConIva = function (array $p) use ($iva): string {
    return $p['nombre'] . ': ' . number_format($p['precio'] * (1 + $iva), 2, '.', '') . ' EUR';
};

$iva = 0.10; // La closure capturó 0.21 por valor; este cambio no altera su cálculo.
$conIva = transformar($visibles, $calcularConIva);

echo implode(' / ', $conIva) . PHP_EOL;

$valor = array_reduce(
    $inventario,
    fn (int $suma, array $p): int => $suma + $p['precio'] * $p['stock'],
    0
);

echo "Valor del stock sin IVA: $valor EUR\n";

// foreach es claro cuando se realizan varias operaciones por elemento.
$unidades = 0;

foreach ($inventario as $id => $p) {
    $unidades += $p['stock'];

    echo "ID $id: {$p['nombre']} / {$p['stock']} unidades\n";
}

echo "Unidades totales: $unidades\n";

echo "\n2. Buscar y comprobar\n";

$agotado = array_find($inventario, fn (array $p): bool => $p['stock'] === 0);
$clave = array_find_key($inventario, fn (array $p, int $id): bool => $id >= 30 && $p['stock'] === 0);

echo 'Primer agotado: ' . ($agotado['nombre'] ?? 'Ninguno') . PHP_EOL;
echo 'Clave de agotado con ID >= 30: ' . ($clave ?? 'Ninguna') . PHP_EOL;
echo 'ID 99: ' . var_export(array_find($inventario, fn (array $p): bool => $p['id'] === 99), true) . PHP_EOL;

echo 'Hay agotados: ' . var_export(array_any($inventario, fn (array $p): bool => $p['stock'] === 0), true) . PHP_EOL;
echo 'Todos con stock no negativo: ' . var_export(array_all($inventario, fn (array $p): bool => $p['stock'] >= 0), true) . PHP_EOL;

$categorias = array_values(array_unique(array_column($inventario, 'categoria')));
$posicion = array_search('perifericos', $categorias, true);

echo 'Categorías: ' . implode(', ', $categorias) . PHP_EOL;
echo 'Posición de perifericos: ' . ($posicion !== false ? $posicion : 'No encontrada') . PHP_EOL;

$porId = array_column(array_values($inventario), null, 'id');

echo 'Registro por ID 30: ' . $porId[30]['nombre'] . PHP_EOL;

$claves = ['nombre', 'stock'];
$valores = ['Adaptador HDMI', 4];

// Se comprueba la longitud antes de combinar para evitar ValueError.
if (count($claves) === count($valores)) {
    echo 'Registro combinado: ' . var_export(array_combine($claves, $valores), true) . PHP_EOL;
}

echo "\n3. Orden y significado de las claves\n";

$lista = $inventario;
usort($lista, fn (array $a, array $b): int => $a['precio'] <=> $b['precio']);

echo 'usort / claves: ' . implode(', ', array_keys($lista)) . PHP_EOL;
echo 'usort / IDs: ' . implode(', ', array_column($lista, 'id')) . PHP_EOL;

$porPrecio = $inventario;
uasort($porPrecio, fn (array $a, array $b): int => $b['precio'] <=> $a['precio']);

echo 'uasort descendente / claves: ' . implode(', ', array_keys($porPrecio)) . PHP_EOL;

echo 'Comparador: ' . (5 <=> 15) . ', ' . (15 <=> 15) . ', ' . (120 <=> 15) . PHP_EOL;

echo "\n4. Cero no es null; una selección puede estar vacía\n";

$stocks = [0, 3, null, 2];

echo 'Sin callback: ' . var_export(array_filter($stocks), true) . PHP_EOL;
echo 'Solo quitar null: ' . var_export(array_filter($stocks, fn ($v): bool => $v !== null), true) . PHP_EOL;

$sinResultados = seleccionar($inventario, 'impresora');

echo 'Impresora: ' . ($sinResultados === [] ? 'Sin resultados' : 'Encontrada') . PHP_EOL;

echo 'any en vacío: ' . var_export(array_any([], fn (array $p): bool => $p['stock'] > 0), true) . PHP_EOL;
echo 'all en vacío: ' . var_export(array_all([], fn (array $p): bool => $p['stock'] > 0), true) . PHP_EOL;

// all([]) es true: no hay ningún elemento que incumpla el predicado.
