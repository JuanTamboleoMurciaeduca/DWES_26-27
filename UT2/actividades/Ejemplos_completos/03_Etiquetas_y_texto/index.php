<?php
declare(strict_types=1);

// Presentación 1, diapositivas 27-37. Datos fijos, sin formularios.

function limpiarTexto(string $texto): string
{
    // Con este patrón fijo, el resultado esperado es una cadena.
    return preg_replace('/\s+/u', ' ', trim($texto)) ?? '';
}

function ficha(string $titulo, ?string $autor = null): string
{
    return $titulo . ' / ' . ($autor ?? 'Sin autor');
}

function sumarPaginas(int ...$paginas): int
{
    return array_sum($paginas);
}

function idValido(int|string $id): ?int
{
    $validado = filter_var($id, FILTER_VALIDATE_INT);

    return $validado === false || $validado < 1 ? null : $validado;
}

$registros = [
    [
        'id' => '17',
        'codigo' => 'LIB-2026-0042',
        'titulo' => "  ÁRBOLES\t del\n barrio  ",
        'autor' => 'Ana Gil',
        'paginas' => 120
    ],

    [
        'id' => 18,
        'codigo' => 'LIB-2026-0043',
        'titulo' => 'Drácula',
        'autor' => null,
        'paginas' => 250
    ],

    [
        'id' => 'abc',
        'codigo' => 'LIB-26-0044',
        'titulo' => 'Manual PHP',
        'autor' => 'Luis Sol',
        'paginas' => 300
    ],
];

echo "1. Texto UTF-8, posición y fragmentos\n";

foreach ($registros as $registro) {
    ['titulo' => $titulo, 'autor' => $autor, 'codigo' => $codigo] = $registro;

    $limpio = limpiarTexto($titulo);
    $normalizado = mb_strtolower($limpio, 'UTF-8');

    echo ficha(autor: $autor, titulo: $limpio) . PHP_EOL; // Orden nombrado invertido.
    echo 'Normalizado: ' . $normalizado . '; longitud: ' . mb_strlen($limpio, 'UTF-8') . PHP_EOL;
    echo 'Inicio UTF-8: ' . mb_substr($limpio, 0, 3, 'UTF-8') . PHP_EOL;

    echo 'Código: ' . substr($codigo, 0, 3) . ' / ' . substr($codigo, -4) . PHP_EOL;
    echo 'Prefijo LIB-: ' . var_export(str_starts_with($codigo, 'LIB-'), true)
        . '; sufijo 0042: ' . var_export(str_ends_with($codigo, '0042'), true) . PHP_EOL;
    echo 'Contiene barrio: ' . var_export(str_contains($normalizado, 'barrio'), true) . PHP_EOL;
}

$posicion = strpos('PHP para el barrio', 'PHP');

echo 'PHP aparece: ' . ($posicion !== false ? "sí, posición $posicion" : 'no') . PHP_EOL;
echo 'XML aparece: ' . (strpos('PHP para el barrio', 'XML') !== false ? 'sí' : 'no') . PHP_EOL;

echo 'substr de ÁRBOLES, 1 byte en hex: ' . bin2hex(substr('ÁRBOLES', 0, 1)) . PHP_EOL;
echo 'mb_substr de ÁRBOLES, 1 carácter: ' . mb_substr('ÁRBOLES', 0, 1, 'UTF-8') . PHP_EOL;

echo "\n2. Validar formato e identificador antes de publicar\n";

foreach ($registros as $registro) {
    $patron = preg_match('/^LIB-\d{4}-\d{4}$/', $registro['codigo']);
    $id = idValido($registro['id']);

    if ($patron === false) {
        echo "Error del patrón; revisar la expresión\n";
    } elseif ($patron === 0 || $id === null) {
        echo $registro['codigo'] . ": Registro rechazado\n";
    } else {
        echo "ID $id: {$registro['codigo']} aceptado\n";
    }
}

// preg_match devuelve 1, 0 o false. Un patrón mal formado puede emitir un aviso;
// no lo introducimos en el recorrido normal del ejemplo.
echo 'Código con sufijo extra: ' . preg_match('/^LIB-\d{4}-\d{4}$/', 'LIB-2026-0042X') . PHP_EOL;

echo "\n3. Variádica y desempaquetado\n";

$paginas = array_column($registros, 'paginas');

echo 'Con argumentos: ' . sumarPaginas(120, 250, 300) . PHP_EOL;
echo 'Con unpacking: ' . sumarPaginas(...$paginas) . PHP_EOL;
echo 'Sin argumentos: ' . sumarPaginas() . PHP_EOL;

echo 'ID 0: ' . var_export(idValido('0'), true) . PHP_EOL;
