<?php
declare(strict_types=1);

require __DIR__ . '/modelo.php';

$envios = [new Ordinario(71, '  Lorca  ', 500), new Ordinario(72, 'Murcia', 501),
    new Urgente(73, 'Águilas', 1000), new Urgente(74, 'Cartagena', 1001)];
foreach ($envios as $e) {
    echo $e->referencia() . ' | ' . $e->destino() . ' | ' . $e->coste() . PHP_EOL;
}
echo 'Prefijo alternativo: ' . $envios[0]->referencia('Caja: ') . PHP_EOL;
echo 'Total: ' . totalCotizado(...$envios) . PHP_EOL;
echo 'Vacío: ' . totalCotizado() . PHP_EOL;
foreach ([1, 5000] as $g) {
    echo "Límite $g: " . (new Ordinario(1, 'Lorca', $g))->coste()
        . ' / ' . (new Urgente(2, 'Lorca', $g))->coste() . PHP_EOL;
}
foreach ([[0,'Lorca',500],[1,'  ',500],[1,'Lorca',0],[1,'Lorca',5001]] as [$id,$d,$g]) {
    try { new Ordinario($id,$d,$g); echo "ERROR: aceptado inválido\n"; }
    catch (InvalidArgumentException $e) { echo 'Rechazo: ' . $e->getMessage() . PHP_EOL; }
}
