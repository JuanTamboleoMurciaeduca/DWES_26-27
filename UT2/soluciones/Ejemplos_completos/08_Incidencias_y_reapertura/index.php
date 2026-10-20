<?php
declare(strict_types=1);

require __DIR__ . '/Incidencia.php';

$i = new Incidencia(81, '  Proyector sin señal  ');
function mostrar(Incidencia $i): void {
    echo $i->id . ' | ' . $i->estado()->value . ' | ' . ($i->tecnico() ?? 'Sin técnico') . PHP_EOL;
}
mostrar($i);
$acciones = [fn () => $i->resolver(), fn () => $i->asignar('  Ana  '),
    fn () => $i->asignar('Ana'), fn () => $i->asignar('Luis'), fn () => $i->resolver(),
    fn () => $i->asignar('Eva'), fn () => $i->reabrir(), fn () => $i->reabrir(),
    fn () => $i->asignar('  '), fn () => $i->asignar('Eva')];
foreach ($acciones as $paso => $accion) {
    echo 'Paso ' . ($paso + 1) . ': ';
    try { $accion(); echo 'OK'; }
    catch (InvalidArgumentException | IncidenciaException $e) { echo 'Rechazo: ' . $e->getMessage(); }
    echo PHP_EOL; mostrar($i);
}
// La lista conserva la identidad del objeto; no se reconstruye entre operaciones.
$lista = [$i];
$alias = $lista[0];
echo 'Mismo objeto: ' . var_export($alias === $i, true) . PHP_EOL;
$alias->resolver(); mostrar($lista[0]);
foreach ([[0,'Pantalla'],[1,'  ']] as [$id,$descripcion]) {
    try { new Incidencia($id,$descripcion); }
    catch (InvalidArgumentException $e) { echo 'Construcción rechazada' . PHP_EOL; }
}
