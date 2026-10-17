<?php
declare(strict_types=1);

require __DIR__ . '/funciones.php';
$datos = require __DIR__ . '/datos.php';
foreach ([['  ROBÓTICA  ',5],['',0],['Diseño',0],['Diseño',1],['Música',0]] as [$aula,$minimo]) {
    echo "Consulta [$aula], mínimo $minimo" . PHP_EOL;
    $filas = filasConCoste(seleccionar($datos,$aula,$minimo));
    echo json_encode(ordenar($filas), JSON_UNESCAPED_UNICODE) . PHP_EOL;
    echo json_encode(agrupar($filas), JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
echo 'Original: ' . implode(',',array_keys($datos)) . PHP_EOL;
echo 'Vacío: ' . json_encode(agrupar([])) . PHP_EOL;
try { seleccionar($datos,'',-1); }
catch (InvalidArgumentException $e) { echo 'Rechazo: ' . $e->getMessage() . PHP_EOL; }
