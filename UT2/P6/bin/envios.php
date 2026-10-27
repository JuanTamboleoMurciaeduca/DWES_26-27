<?php

declare(strict_types=1);

use App\Excepcion\EnvioException;
use App\Modelo\{Envio, EnvioNormal, EnvioUrgente, EstadoEnvio};
use App\Repositorio\RepositorioEnviosMemoria;
use App\Servicio\ServicioEnvios;

require dirname(__DIR__) . '/vendor/autoload.php';

$envios = [
    new EnvioNormal(101, 'Librería Órbita', 1000, EstadoEnvio::Pendiente),
    new EnvioUrgente(102, 'Taller Norte', 1500, EstadoEnvio::Enviado),
    new EnvioNormal(103, 'Café Álamo', 2500, EstadoEnvio::Entregado),
    new EnvioUrgente(104, 'Estudio Prisma', 500, EstadoEnvio::Cancelado),
    new EnvioNormal(105, 'Papelería Sol', 2000, EstadoEnvio::Pendiente),
    new EnvioUrgente(106, 'Órbita Creativa', 1001, EstadoEnvio::Pendiente),
];
$repositorio = new RepositorioEnviosMemoria();
foreach ($envios as $envio) {
    $repositorio->registrar($envio);
}
$servicio = new ServicioEnvios($repositorio);

function mostrar(string $titulo, array $envios, ServicioEnvios $servicio): void
{
    echo "\n$titulo\n";
    echo $envios === [] ? "Sin resultados\n" : implode("\n", $servicio->etiquetas($envios)) . "\n";
    echo json_encode($servicio->resumir($envios), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . "\n";
}

function rechazoEsperado(string $titulo, callable $operacion): void
{
    try {
        $operacion();
    } catch (EnvioException | InvalidArgumentException $error) {
        echo $titulo . ': rechazo - ' . $error->getMessage() . "\n";
        return;
    }
    throw new LogicException('No se produjo el rechazo esperado: ' . $titulo);
}

mostrar('Catálogo inicial en orden de registro', $repositorio->todos(), $servicio);
$consultas = [
    ['Sin filtros', '', null, null, 'precio'],
    ['Texto ÓRBITA', '  ÓRBITA  ', null, null, 'precio'],
    ['Urgentes', '', 'urgente', null, 'precio'],
    ['Pendientes', '', null, EstadoEnvio::Pendiente, 'precio'],
    ['ÓRBITA + urgente + pendiente', 'ÓRBITA', 'urgente', EstadoEnvio::Pendiente, 'precio'],
    ['Espacios interiores', '  ÓRBITA   CREATIVA  ', null, null, 'precio'],
    ['Texto xyz', 'xyz', null, null, 'precio'],
    ['Orden por peso', '', null, null, 'peso'],
];
foreach ($consultas as [$titulo, $texto, $modalidad, $estado, $orden]) {
    mostrar($titulo, $servicio->consultar($texto, $modalidad, $estado, $orden), $servicio);
}
echo 'Identidad en búsquedas: ' . ($repositorio->buscar(101) === $envios[0] ? 'true' : 'false') . "\n";
mostrar('Catálogo tras las consultas, sin reordenar', $repositorio->todos(), $servicio);

// Apartado 6.1: no se reconstruyen los objetos entre operaciones.
$operaciones = [
    ['enviar', 101],
    ['cancelar', 101],
    ['entregar', 101],
    ['enviar', 101],
    ['cancelar', 105],
    ['enviar', 105],
    ['entregar', 102],
    ['cancelar', 104],
    ['entregar', 106],
    ['enviar', 999],
];
foreach ($operaciones as [$accion, $id]) {
    echo "$accion $id: ";
    try {
        $servicio->$accion($id);
        echo 'aceptado';
    } catch (EnvioException | InvalidArgumentException $error) {
        echo 'rechazo - ' . $error->getMessage();
    }
    echo ' | estado: ' . ($repositorio->buscar($id)?->estado()->value ?? 'id ausente') . "\n";
}
mostrar('Resumen final', $repositorio->todos(), $servicio);
mostrar('Pendientes al terminar', $servicio->consultar(estado: EstadoEnvio::Pendiente), $servicio);

// Apartado 6.2: construcciones independientes del catálogo.
foreach (
    [
        [0, 'Cliente', 1],
        [-1, 'Cliente', 1],
        [1, '', 1],
        [1, '   ', 1],
        [1, 'Cliente', 0],
        [1, 'Cliente', -1],
        [1, 'Cliente', 20001]
    ] as [$id, $cliente, $peso]
) {
    rechazoEsperado("Construir id=$id cliente='$cliente' peso=$peso", fn() =>
    new EnvioNormal($id, $cliente, $peso, EstadoEnvio::Pendiente));
}
$limpio = new EnvioNormal(1, '  Cliente   Norte  ', 1, EstadoEnvio::Pendiente);
echo "Cliente recortado: [{$limpio->cliente()}]\n";
foreach ([1, 1000, 1001, 20000] as $peso) {
    $normal = new EnvioNormal(1, 'Cliente', $peso, EstadoEnvio::Pendiente);
    $urgente = new EnvioUrgente(2, 'Cliente', $peso, EstadoEnvio::Pendiente);
    echo "$peso g: normal={$normal->tarifa()} urgente={$urgente->tarifa()} céntimos\n";
}
rechazoEsperado('Id duplicado', fn() => $repositorio->registrar($limpio = new EnvioNormal(101, 'Otro', 1, EstadoEnvio::Pendiente)));
echo 'Catálogo tras duplicado: ' . count($repositorio->todos()) . ' envíos; instancia anterior: '
    . ($repositorio->buscar(101) === $envios[0] ? 'conservada' : 'alterada') . "\n";
echo 'Búsqueda 999: ' . ($repositorio->buscar(999) === null ? 'null' : 'inesperado') . "\n";
rechazoEsperado('Buscar id 0', fn() => $repositorio->buscar(0));
rechazoEsperado('Enviar id 0', fn() => $servicio->enviar(0));
foreach ([$servicio, new ServicioEnvios(new RepositorioEnviosMemoria())] as $s) {
    rechazoEsperado('Modalidad desconocida', fn() => $s->consultar(modalidad: 'inventada'));
    rechazoEsperado('Orden desconocido', fn() => $s->consultar(orden: 'inventado'));
}
