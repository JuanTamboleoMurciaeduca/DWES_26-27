<?php
declare(strict_types=1);
require __DIR__ . '/Equipo.php';

$datos = [
    ['id' => 10, 'nombre' => 'Cámara'],
    ['id' => 20, 'nombre' => 'Trípode'],
    ['id' => 30, 'nombre' => 'Micrófono'],
];
$equipos = array_map(fn (array $dato): Equipo =>
    new Equipo(nombre: $dato['nombre'], id: $dato['id']), $datos);
echo 'El array original contiene arrays: ' . var_export(is_array($datos[0]), true) . PHP_EOL;

$camara = $equipos[0];
$alias = $camara;
$otraCamara = new Equipo(10, 'Cámara'); // Mismos datos, otra identidad.
echo 'Original === alias: ' . var_export($camara === $alias, true) . PHP_EOL;
echo 'Original === otro new: ' . var_export($camara === $otraCamara, true) . PHP_EOL;
$alias->setNombre('Cámara del aula');
echo 'Nombre original tras cambio por alias: ' . $camara->getNombre() . PHP_EOL;
echo 'Nombre del otro objeto: ' . $otraCamara->nombre . PHP_EOL;

$disponiblesAntes = array_filter($equipos, fn (Equipo $e): bool => $e->disponible);
$encontrado = array_find($equipos, fn (Equipo $e): bool => $e->id === 10);
if ($encontrado !== null) {
    $encontrado->prestar();
    $encontrado->prestar(); // La regla impide repetir el préstamo.
}
echo 'Catálogo completo: ' . $equipos[0]->ficha() . PHP_EOL;
echo 'Lista antigua: ' . $disponiblesAntes[0]->ficha() . PHP_EOL;
echo 'Alias: ' . $alias->ficha() . PHP_EOL;
echo 'Objeto independiente: ' . $otraCamara->ficha() . PHP_EOL;
echo 'Otro equipo del catálogo: ' . $equipos[1]->ficha() . PHP_EOL;
$disponiblesAhora = array_filter($equipos, fn (Equipo $e): bool => $e->disponible);
echo 'Lista antigua / cantidad: ' . count($disponiblesAntes) . PHP_EOL;
echo 'Lista recalculada / cantidad: ' . count($disponiblesAhora) . PHP_EOL;
// filter crea un array, pero sus elementos son los mismos objetos.
// La pertenencia de la lista anterior no se recalcula al cambiar una propiedad.

$ausente = array_find($equipos, fn (Equipo $e): bool => $e->id === 99);
echo 'Búsqueda ID 99: ' . ($ausente?->nombre ?? 'Equipo no encontrado') . PHP_EOL;
$camara->devolver();
$camara->devolver();
foreach ($equipos as $equipo) {
    echo $equipo->ficha() . PHP_EOL;
}
// Asignar un objeto no copia su estado ni usa el operador & de referencias PHP.
// Las propiedades públicas aún permiten saltarse las reglas de los métodos.
