<?php

declare(strict_types=1);

function prepararPortadaAnterior(): string
{
    $datos = file_get_contents(__DIR__ . '/../muestras/portada.png');
    if ($datos === false) {
        throw new RuntimeException('No se pudo leer la muestra.');
    }
    do {
        $nombre = bin2hex(random_bytes(16)) . '.png';
        $ruta = DIRECTORIO . '/' . $nombre;
    } while (file_exists($ruta));
    $salida = @fopen($ruta, 'x'); // Crear sin sobrescribir, no move_uploaded_file de un local.
    if ($salida === false) {
        throw new RuntimeException('No se pudo preparar el escenario.');
    }
    $completa = false;
    try {
        if (fwrite($salida, $datos) !== strlen($datos)) {
            throw new RuntimeException('No se pudo escribir la muestra completa.');
        }
        $completa = true;
    } finally {
        fclose($salida);
        if (!$completa) {
            retirarArchivo($nombre);
        }
    }
    return $nombre;
}
