<?php

declare(strict_types=1);

use App\Repository\{ArchivoJson, Catalogo, JsonUsuarioRepository};

require dirname(__DIR__) . '/vendor/autoload.php';

\App\Soporte\iniciarSesion();

$catalogo = new Catalogo(new ArchivoJson(dirname(__DIR__) . '/var/libros.json'));

$usuarios = new JsonUsuarioRepository(new ArchivoJson(dirname(__DIR__) . '/var/usuarios.json'));

return compact('catalogo', 'usuarios');
