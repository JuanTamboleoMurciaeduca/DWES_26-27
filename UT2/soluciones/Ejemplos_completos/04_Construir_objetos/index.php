<?php
declare(strict_types=1);
require __DIR__ . '/clases.php';

// Un array admite una clave escrita por error; la propiedad correcta no cambia.
$datos = ['nombre' => 'Robótica', 'plazas' => 12, 'abierto' => true];
$datos['abiertoo'] = false;
echo 'Array / abierto tras abiertoo: ' . var_export($datos['abierto'], true) . PHP_EOL;

$explicito = new TallerExplicito($datos['nombre'], $datos['plazas']);
$promovido = new TallerPromovido(plazas: 12, nombre: 'Robótica');
$cerrado = new TallerPromovido('Fotografía', 8, false);
echo 'Clase explícita: ' . $explicito->ficha() . PHP_EOL;
echo 'Clase promovida: ' . $promovido->ficha() . PHP_EOL;
echo 'Abierto por defecto: ' . var_export($promovido->abierto, true) . PHP_EOL;
echo 'False explícito: ' . var_export($cerrado->abierto, true) . PHP_EOL;

$texto = $explicito->ficha(); // return permite guardar y reutilizar el resultado.
echo 'Ficha guardada: ' . $texto . PHP_EOL;
$explicito->mostrarFicha(); // echo escribe directamente; el método es void.

$borrador = new TallerBorrador();
echo 'Nombre inicializado: ' . var_export(isset($borrador->nombre), true) . PHP_EOL;
echo "Plazas iniciales: $borrador->plazas\n";
echo 'Observaciones iniciales: ' . var_export($borrador->observaciones, true) . PHP_EOL;
$borrador->nombre = 'Huerto'; // Asignar antes de leer.
echo "Nombre tras asignar: $borrador->nombre\n";

$promovido->nombre = 'Robótica avanzada';
echo 'final permite modificar propiedades públicas: ' . $promovido->nombre . PHP_EOL;
// final prohíbe heredar, no impide new ni hace inmutable el estado.
// No creamos propiedades dinámicas: usamos los nombres declarados en la clase.
// class, new, final, public, function y return son palabras del lenguaje.
// TallerPromovido y ficha son identificadores elegidos; -> es un operador.
