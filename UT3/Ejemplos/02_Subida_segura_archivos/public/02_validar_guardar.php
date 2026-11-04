<?php
declare(strict_types=1);

require __DIR__ . '/../src/pagina.php';

$errores = $traza = [];
$nombre = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (($error = errorPeticion()) !== null) {
        $errores['peticion'] = $error;
    } else {
        $validado = validarArchivo($_FILES['portada'] ?? null, true);

        if ($validado['error'] !== null) {
            $errores['portada'] = $validado['error'];
        } else {
            $traza[] = 'Forma, recepción, tamaño y MIME correctos: ' . $validado['mime'];

            try {
                $nombre = guardarArchivo($validado);
                $traza[] = 'Temporal movido con nombre generado: ' . $nombre;
            } catch (Throwable $e) {
                $errores['portada'] = 'No se pudo preparar o guardar la portada. Revisa el servidor.';
                error_log($e->getMessage());
            }
        }
    }
}

inicio('2. Validar y guardar', '6–22', $errores);
?>
<p>Aquí la portada es obligatoria. Se aceptan JPEG, PNG y WebP de 1 byte a 2 MiB, ambos límites incluidos.</p>
<p>Prueba una imagen; después, un texto renombrado a texto.jpg. El MIME declarado y el nombre no deciden el formato.</p>
<form method="post" enctype="multipart/form-data">
    <p><input type="file" name="portada" accept="image/jpeg,image/png,image/webp"></p>
    <button>Validar y guardar</button>
</form>
<?php resultado($errores, $traza); ?>
<?php if ($nombre !== null): ?>
    <p>El archivo está en storage/portadas, fuera de public. Nombre: <code><?= e($nombre) ?></code>.</p>
    <p><a href="03_publicar.php?nombre=<?= e($nombre) ?>">Ver cómo se publica en el ejemplo 3</a></p>
<?php endif ?>
</body></html>
