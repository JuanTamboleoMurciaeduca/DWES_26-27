<?php
declare(strict_types=1);
// Ejecutar desde esta carpeta: php index.php
// Presentación 1, diapositivas 3-9 y 35-37.

// mixed tiene sentido aquí: queremos explicar valores de varios tipos.
function describir(mixed $valor): string
{
    if (is_bool($valor)) {
        return $valor ? 'true' : 'false';
    }
    if ($valor === null) {
        return 'null';
    }
    if (is_array($valor)) {
        return var_export($valor, true);
    }
    return var_export($valor, true);
}

// Acepta un entero de programa o una cadena de entrada. Valida antes del cast.
// null significa rechazo; 0 es una cantidad válida.
function cantidad(int|string $entrada): ?int
{
    $numero = filter_var($entrada, FILTER_VALIDATE_INT);
    return $numero === false || $numero < 0 ? null : $numero;
}

$ficha = ['titulo' => '  Atlas del barrio  ', 'autor' => null,
          'ejemplares' => 0, 'publicado' => false, 'nota' => '', 'temporal' => 'borrador'];

echo "1. Clave existente frente a valor disponible\n";
foreach (['autor', 'ejemplares', 'publicado', 'nota', 'isbn'] as $clave) {
    echo $clave . ': isset=' . describir(isset($ficha[$clave]))
        . ', empty=' . describir(empty($ficha[$clave]))
        . ', existe=' . describir(array_key_exists($clave, $ficha)) . PHP_EOL;
}
unset($ficha['temporal']);
echo 'Temporal tras unset: ' . describir(array_key_exists('temporal', $ficha)) . PHP_EOL;

echo "\n2. Valores por defecto\n";
$ejemplares = $ficha['ejemplares'] ?? 5; // Conserva 0.
$conTernario = $ficha['ejemplares'] ?: 5; // Sustituye 0 porque es falso.
$ficha['autor'] ??= 'Autor desconocido';
$ficha['isbn'] ??= 'Sin ISBN';
$ficha['nota'] ??= 'Sin nota'; // No reemplaza la cadena vacía.
echo "Ejemplares con ??: $ejemplares; con ?:: $conTernario\n";
echo $ficha['autor'] . ' / ' . $ficha['isbn'] . PHP_EOL;
echo 'Nota conservada: ' . describir($ficha['nota']) . PHP_EOL;

echo "\n3. Tipo y validación de entradas simuladas\n";
// Son entradas simuladas: no hacen falta formularios ni URLs.
foreach (['0', '12', 'abc', '-1', '1.5', '1e3', ['12'], 12, false] as $entrada) {
    // No entregamos un array o bool a la función int|string.
    $resultado = is_string($entrada) || is_int($entrada) ? cantidad($entrada) : null;
    echo describir($entrada) . ' => ' . ($resultado === null ? 'No válida' : (string) $resultado)
        . '; is_string=' . describir(is_string($entrada))
        . '; is_int=' . describir(is_int($entrada))
        . '; is_array=' . describir(is_array($entrada))
        . '; is_bool=' . describir(is_bool($entrada)) . PHP_EOL;
}
echo 'is_numeric("1.5"): ' . describir(is_numeric('1.5')) . PHP_EOL;
echo 'is_numeric("1e3"): ' . describir(is_numeric('1e3')) . PHP_EOL;
echo 'Cast de abc (no valida): ' . (int) 'abc' . PHP_EOL;

echo "\n4. Texto obligatorio y tabla de valores vacíos\n";
foreach (['  Atlas del barrio  ', '   ', '0', ['Atlas']] as $entrada) {
    $titulo = is_string($entrada) ? trim($entrada) : '';
    echo describir($entrada) . ' => ' . ($titulo === '' ? 'Título no válido' : $titulo) . PHP_EOL;
}
foreach ([null, false, 0, 0.0, '0', '', [], 'Atlas', ' '] as $valor) {
    $a = ['dato' => $valor];
    echo describir($valor) . ': isset=' . describir(isset($a['dato']))
        . ', empty=' . describir(empty($a['dato']))
        . ', existe=' . describir(array_key_exists('dato', $a)) . PHP_EOL;
}
// isset y empty son construcciones del lenguaje: no son callbacks.
