<?php
declare(strict_types=1);

require __DIR__ . '/../src/pagina.php';

$errores = $traza = $guardados = [];
$fallarSegundo = ($_POST['fallarSegundo'] ?? null) === '1';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (($error = errorPeticion()) !== null) {
        $errores['peticion'] = $error;
    } else {
        try {
            // Primero normalizar y validar TODO el conjunto.
            $archivos = normalizarMultiples($_FILES['portadas'] ?? null);

            if ($archivos === []) {
                $errores['portadas'] = 'Selecciona entre 1 y 3 portadas.';
            }

            $validados = [];

            foreach ($archivos as $i => $archivo) {
                $validados[$i] = validarArchivo($archivo, true);

                if ($validados[$i]['error'] !== null) {
                    $errores['portadas[' . $i . ']'] = $validados[$i]['error'];
                }
            }

            if ($errores === []) {
                $traza[] = 'Conjunto completo válido; comienza el movimiento.';
                $posicion = 0;

                try {
                    foreach ($validados as $validado) {
                        ++$posicion;

                        if ($fallarSegundo && $posicion === 2) {
                            throw new RuntimeException('Fallo simulado antes del segundo movimiento.');
                        }

                        $nombre = guardarArchivo($validado);
                        $guardados[] = $nombre;
                        $traza[] = 'Movida portada ' . $posicion . ': ' . $nombre;
                    }

                    $traza[] = 'Lote completo guardado.';
                } catch (Throwable $e) {
                    // Solo los nombres generados por ESTE intento, nunca otros archivos.
                    $limpio = true;

                    foreach ($guardados as $nombre) {
                        $limpio = retirarArchivo($nombre) && $limpio;
                    }

                    $guardados = [];
                    $traza[] = $limpio ? 'Compensación: retirados todos los nuevos de este intento.'
                        : 'Compensación con limpieza pendiente. Revisa el servidor.';
                    throw $e;
                }
            } else {
                $traza[] = 'Lote rechazado: no se ha movido ningún archivo.';
            }
        } catch (InvalidArgumentException $e) {
            $errores['portadas'] = $e->getMessage();
        } catch (Throwable $e) {
            $errores['operacion'] = 'No se pudo completar el lote.';
            error_log($e->getMessage());
        }
    }
}

inicio('5. Varias portadas: todo el lote', '33', $errores);
?>
<p>Este ejemplo admite entre 1 y 3 archivos; cada uno, de hasta 2 MiB. Primero se validan todos y después se mueven.</p>
<form method="post" enctype="multipart/form-data">
    <p><input type="file" name="portadas[]" multiple accept="image/jpeg,image/png,image/webp"></p>
    <p><label><input type="checkbox" name="fallarSegundo" value="1" <?= $fallarSegundo ? 'checked' : '' ?>>Simular fallo al llegar al segundo movimiento (selecciona al menos dos archivos)</label></p>
    <button>Procesar lote</button>
</form>
<?php resultado($errores, $traza); ?>
<?php
foreach ($guardados as $nombre) {
    imagen($nombre);
}
?>
<p>Prueba dos imágenes válidas; después una imagen y texto.jpg. Si una no valida, no se guarda ninguna.</p>
</body></html>
