<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/datos/libros.php';

use Juan\Biblioteca\Servicio\BibliotecaService;

$bibService = new BibliotecaService($libros);

require dirname(__DIR__) . '/vistas/alta.php';
