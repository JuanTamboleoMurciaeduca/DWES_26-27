<?php

use App\Model\Libro;

require_once __DIR__ . '/../vendor/autoload.php';

$libro = new Libro('Dune');

echo $libro->titulo() . PHP_EOL;