<?php
declare(strict_types=1);

require __DIR__ . '/LibroProtegido.php';

// Ejecutar por separado. Se espera Error al escribir una propiedad private.
$libro = new LibroProtegido('Atlas del barrio', 120);

$libro->paginas = 0;
