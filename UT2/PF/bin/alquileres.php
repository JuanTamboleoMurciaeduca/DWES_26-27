<?php

declare(strict_types=1);

use Clase\Alquileres\Excepcion\AlquilerException;
use Clase\Alquileres\Modelo\AlquilerPatinete;
use Clase\Alquileres\Modelo\AlquilerBicicleta;
use Clase\Alquileres\Modelo\EstadoAlquiler;
use Clase\Alquileres\Servicio\ServicioAlquileres;

require_once __DIR__ . '/../vendor/autoload.php';

function mostrar(
    string $titulo,
    array $alquileres,
    ServicioAlquileres $servicio
): void {
    echo $titulo . PHP_EOL;
    if ($alquileres === []) {
        echo 'Sin resultados' . PHP_EOL;
    }
    foreach ($alquileres as $a) {
        echo $a->id() . ' | ' . $a->cliente() . ' | ' . $a->modalidad()
            . ' | días: ' . $a->dias() . ' | ' . $a->estado()->value
            . ' | ' . $a->importe() . ' céntimos' . PHP_EOL;
    }
    $resumen = $servicio->resumir($alquileres);
    echo 'Cantidad: ' . $resumen['cantidad']
        . ' | Importe: ' . $resumen['importe'] . ' céntimos'
        . PHP_EOL . PHP_EOL;
}

$alquileres = [
    new AlquilerBicicleta(501, 'Lucía Torres', 2, EstadoAlquiler::Reservado),
    new AlquilerPatinete(502, 'Álex Ruiz', 2, EstadoAlquiler::Activo),
    new AlquilerBicicleta(503, 'Óscar Gil', 3, EstadoAlquiler::Finalizado),
    new AlquilerPatinete(504, 'Lucía Molina', 1, EstadoAlquiler::Reservado)
];

$servicio = new ServicioAlquileres($alquileres);

mostrar('Colección inicial', $alquileres, $servicio);

mostrar('Sin filtros', $servicio->consultar(), $servicio);

mostrar(
    'Texto LUCÍA',
    $servicio->consultar('  LUCÍA  '),
    $servicio
);

mostrar(
    'Estado activo',
    $servicio->consultar('', EstadoAlquiler::Activo),
    $servicio
);

mostrar(
    'Texto MOLINA y estado reservado',
    $servicio->consultar('MOLINA', EstadoAlquiler::Reservado),
    $servicio
);

mostrar(
    'Texto ÓSCAR y estado reservado',
    $servicio->consultar('ÓSCAR', EstadoAlquiler::Reservado),
    $servicio
);

// 4.3 Cambios de estado sobre los objetos del array inicial.
try {
    $alquileres[0]->iniciar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Iniciar 501', [$alquileres[0]], $servicio);

try {
    $alquileres[0]->iniciar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Volver a iniciar 501', [$alquileres[0]], $servicio);

try {
    $alquileres[0]->finalizar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Finalizar 501', [$alquileres[0]], $servicio);

try {
    $alquileres[2]->iniciar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Iniciar 503', [$alquileres[2]], $servicio);

try {
    $alquileres[3]->finalizar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Finalizar 504', [$alquileres[3]], $servicio);

try {
    $alquileres[1]->finalizar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Finalizar 502', [$alquileres[1]], $servicio);

try {
    $alquileres[3]->iniciar();
} catch (AlquilerException $error) {
    echo $error->getMessage() . "\n";
}

mostrar('Iniciar 504', [$alquileres[3]], $servicio);

mostrar(
    'Activos al terminar',
    $servicio->consultar('', EstadoAlquiler::Activo),
    $servicio
);

mostrar(
    'Reservados al terminar',
    $servicio->consultar('', EstadoAlquiler::Reservado),
    $servicio
);

mostrar('Colección final', $servicio->todos(), $servicio);

// 4.4 Datos inválidos y límites.
echo 'Datos inválidos de bicicleta' . "\n";

try {
    new AlquilerBicicleta(0, 'Cliente', 1, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerBicicleta(505, '', 1, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerBicicleta(505, '   ', 1, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerBicicleta(505, 'Cliente', 0, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerBicicleta(505, 'Cliente', 8, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

echo "\n" . 'Datos inválidos de patinete' . "\n";

try {
    new AlquilerPatinete(0, 'Cliente', 1, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerPatinete(505, '', 1, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerPatinete(505, '   ', 1, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerPatinete(505, 'Cliente', 0, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

try {
    new AlquilerPatinete(505, 'Cliente', 8, EstadoAlquiler::Reservado);
} catch (InvalidArgumentException $error) {
    echo $error->getMessage() . "\n";
}

$bicicleta = new AlquilerBicicleta(505, '  Cliente   Norte  ', 1, EstadoAlquiler::Reservado);
$patinete = new AlquilerPatinete(506, '  Cliente   Norte  ', 1, EstadoAlquiler::Reservado);

echo "\n" . 'Clientes sin espacios exteriores:' . "\n";
echo 'Bicicleta: [' . $bicicleta->cliente() . ']' . "\n";
echo 'Patinete: [' . $patinete->cliente() . ']' . "\n" . "\n";

$bicicleta = new AlquilerBicicleta(505, 'Cliente', 1, EstadoAlquiler::Reservado);
$patinete = new AlquilerPatinete(506, 'Cliente', 1, EstadoAlquiler::Reservado);

mostrar('Límite de 1 día', [$bicicleta, $patinete], $servicio);

$bicicleta = new AlquilerBicicleta(505, 'Cliente', 7, EstadoAlquiler::Reservado);
$patinete = new AlquilerPatinete(506, 'Cliente', 7, EstadoAlquiler::Reservado);

mostrar('Límite de 7 días', [$bicicleta, $patinete], $servicio);
