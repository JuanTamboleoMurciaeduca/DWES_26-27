<?php
declare(strict_types=1);
require __DIR__ . '/../src/pagina.php';
require __DIR__ . '/../src/Libro.php';
require __DIR__ . '/../src/escenario.php';
$valores = ['titulo' => 'Dune', 'paginas' => '688', 'modo' => 'alta'];
$errores = $traza = [];
$libro = null;
$anterior = $nuevo = null;
$exito = false;
$simularFallo = false;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (($error = errorPeticion()) !== null) {
        $errores['peticion'] = $error;
    } else {
        // 1. Validar el formulario. Conservar solo cadenas; no convertir arrays a texto.
        foreach ($valores as $campo => $inicial) {
            $entrada = $_POST[$campo] ?? '';
            if (!is_string($entrada)) {
                $errores[$campo] = 'Se esperaba un valor simple.';
                $valores[$campo] = '';
            } else {
                $valores[$campo] = trim($entrada);
            }
        }
        if ($valores['titulo'] === '' || mb_strlen($valores['titulo'], 'UTF-8') > 120) {
            $errores['titulo'] = 'Escribe un título de 1 a 120 caracteres.';
        }
        $paginas = filter_var($valores['paginas'], FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 3000]]);
        if ($paginas === false) {
            $errores['paginas'] = 'Escribe un entero entre 1 y 3000.';
        }
        if (!in_array($valores['modo'], ['alta', 'editar'], true)) {
            $errores['modo'] = 'Operación no válida.';
        }
        $simularFallo = ($_POST['fallar'] ?? null) === '1';

        // Preparar el estado previo de la demostración de edición, incluso si hay error.
        $repo = new LibroRepositoryMemoria();
        if ($valores['modo'] === 'editar') {
            try {
                $anterior = prepararPortadaAnterior();
                $libro = new Libro('Dune', 688, $anterior);
                $id = $repo->guardar($libro);
                $traza[] = 'Escenario previo: Dune, 688 páginas, portada ' . $anterior;
            } catch (Throwable $e) {
                $errores['escenario'] = 'No se pudo preparar la edición. Revisa el servidor.';
                error_log($e->getMessage());
            }
        }

        // 2. Validar la portada opcional. Todavía no se mueve nada del usuario.
        $validado = validarArchivo($_FILES['portada'] ?? null);
        if ($validado['error'] !== null) {
            $errores['portada'] = $validado['error'];
        }
        if ($errores !== []) {
            $traza[] = 'Hay errores: no se mueve la nueva portada ni se guarda el libro.';
        } else {
            // Construir el candidato antes de mover: también puede fallar su constructor.
            $candidato = new Libro($valores['titulo'], $paginas, $anterior);
            $traza[] = 'Formulario y portada válidos. Libro candidato construido.';
            try {
                // 3 y 4. Preparar destino y mover. null significa conservar la referencia.
                $nuevo = guardarArchivo($validado);
                $traza[] = $nuevo === null ? 'Sin archivo nuevo: se conserva la portada.'
                    : 'Nueva portada movida: ' . $nuevo;
                if ($nuevo !== null) {
                    $candidato->cambiarPortada($nuevo);
                }
                // 5. Solo ahora se ejecuta la operación del repositorio.
                if ($valores['modo'] === 'editar') {
                    $repo->actualizar($id, $candidato, $simularFallo);
                } else {
                    $id = $repo->guardar($candidato, $simularFallo);
                }
                $libro = $repo->obtener($id);
                $exito = true;
                $traza[] = 'Operación correcta. Referencia del libro actualizada.';
            } catch (Throwable $e) {
                // Compensación: el repositorio conserva el original; restaurar candidato.
                $candidato->cambiarPortada($anterior);
                $limpio = retirarArchivo($nuevo);
                $errores['operacion'] = 'La operación falló. Se conserva el estado anterior.';
                $traza[] = $limpio ? 'Compensación completada: retirado el nuevo, conservado el anterior.'
                    : 'La nueva referencia no se guarda; limpieza pendiente en el servidor.';
                error_log($e->getMessage());
            }
            // Fuera del catch: un fallo al retirar la antigua no deshace el éxito.
            if ($exito && $nuevo !== null && $anterior !== null && $anterior !== $nuevo) {
                $traza[] = retirarArchivo($anterior)
                    ? 'Portada antigua retirada DESPUÉS de actualizar. No estaba compartida.'
                    : 'Operación correcta; no se pudo retirar la antigua. Revisa el registro.';
            }
        }
    }
}
inicio('4. Alta, edición y compensación', '7–8, 28–32 y 34', $errores);
?>
<p>La portada es opcional. Cualquier error de título, páginas o archivo impide mover la subida.</p>
<p>En modo edición, cada envío prepara un libro previo independiente (Dune, 688 páginas y una copia de la muestra).
El repositorio está en memoria: otro envío reconstruye el escenario. Las imágenes guardadas sí permanecen en disco.</p>
<form method="post" enctype="multipart/form-data">
    <p><label>Operación <select name="modo">
        <option value="alta" <?= $valores['modo'] === 'alta' ? 'selected' : '' ?>>Alta</option>
        <option value="editar" <?= $valores['modo'] === 'editar' ? 'selected' : '' ?>>Editar el libro de muestra</option>
    </select></label></p>
    <p><label>Título <input name="titulo" value="<?= e($valores['titulo']) ?>"></label></p>
    <p><label>Páginas <input name="paginas" value="<?= e($valores['paginas']) ?>"></label></p>
    <p><label>Portada nueva <input type="file" name="portada" accept="image/jpeg,image/png,image/webp"></label></p>
    <p><label><input type="checkbox" name="fallar" value="1" <?= $simularFallo ? 'checked' : '' ?>>Simular fallo del repositorio después del movimiento</label></p>
    <button>Procesar libro</button>
</form>
<?php resultado($errores, $traza); ?>
<?php if ($libro !== null): ?>
    <h2><?= $exito ? 'Libro tras el éxito' : 'Libro anterior conservado' ?></h2>
    <p><?= e($libro->titulo()) ?> · <?= $libro->paginas() ?> páginas</p>
    <?php if ($libro->portada() !== null) { imagen($libro->portada()); } else { echo '<p>Sin portada.</p>'; } ?>
<?php endif ?>
<p>Prueba: alta sin archivo; páginas 0 con un PNG válido; texto.jpg; edición sin archivo;
edición con imagen nueva; edición con imagen nueva y fallo simulado.</p>
</body></html>
