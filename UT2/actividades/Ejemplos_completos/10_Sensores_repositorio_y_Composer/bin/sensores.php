<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
use Aula\Sensores\Modelo\Sensor;
use Aula\Sensores\Repositorio\SensorRepositoryMemoria;
use Aula\Sensores\Servicio\MonitorService;

$datos = [new Sensor(91,'Robótica',60,55),new Sensor(92,'Diseño',50,50),
    new Sensor(93,'Robótica',70),new Sensor(94,'Redes',80,0)];
$repo = new SensorRepositoryMemoria($datos);
$monitor = new MonitorService($repo);
function informe(MonitorService $monitor): void {
    echo json_encode($monitor->resumen(),JSON_UNESCAPED_UNICODE) . PHP_EOL;
    echo 'Alertas: ' . implode(',',array_map(fn (Sensor $s): int => $s->id,$monitor->alertas())) . PHP_EOL;
    echo 'Robótica: ' . implode(',',array_map(fn (Sensor $s): int => $s->id,$monitor->alertas('  ROBÓTICA  '))) . PHP_EOL;
}
informe($monitor);
$antes = $monitor->alertas();
foreach ([[91,60],[93,70],[92,10],[94,101],[99,20]] as [$id,$v]) {
    try { $monitor->registrar($id,$v); echo "Registrado $id: $v\n"; }
    catch (InvalidArgumentException | OutOfBoundsException $e) { echo 'Rechazo: ' . $e->getMessage() . PHP_EOL; }
}
informe($monitor);
echo 'Lista antigua: ' . count($antes) . ' / ' . $antes[0]->id . ' / ' . $antes[0]->nivel()->value . PHP_EOL;
echo 'Sin lectura: ' . ($repo->buscar(99)?->sala() ?? 'No existe') . PHP_EOL;
echo 'Identidad: ' . var_export($repo->buscar(91) === $datos[0],true) . PHP_EOL;
echo 'Vacío: ' . json_encode((new MonitorService(new SensorRepositoryMemoria([])))->resumen()) . PHP_EOL;
