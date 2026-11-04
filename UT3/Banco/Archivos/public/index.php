<?php

declare(strict_types=1);

$esPost = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

if ($esPost) {
    $titulo = $_POST['titulo'] ?? '';
    $archivo = $_FILES['portada'] ?? null;
    $estructuraValida = is_array($archivo)
        && isset($archivo['error'], $archivo['size'])
        && is_int($archivo['error'])
        && is_int($archivo['size'])
        && isset($archivo['tmp_name'])
        && is_string($archivo['tmp_name']);
    if ($archivo !== null && !$estructuraValida) {
        $errores['portada'] = 'Entrada no válida.';
    }
    if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
        $errores['portada'] = null;
    }
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        if ($archivo['error'] === UPLOAD_ERR_INI_SIZE) {
            $errores['portada'] = 'Supera el tamaño';
        }
        if ($archivo['error'] === UPLOAD_ERR_PARTIAL) {
            $errores['portada'] = 'Falló la recepción';
        }

        $tamMax = 2 * 1024 * 1024;
        if ($archivo['size'] <= 0 || $archivo['size'] > $tamMax) {
            $errores['portada'] = 'Entre 1byte y 2MB';
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($archivo['tmp_name']);

            $permitidos = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];
            if ($mime === false || !isset($permitidos[$mime])) {
                $errores['portada'] = 'Formato no permitido.';
            } else {
                $extension = $permitidos[$mime];

                try {
                    $nombre = bin2hex(random_bytes(16))
                        . '.' . $extension;

                    $directorio = __DIR__ . '/../storage/portadas';
                    $destino = $directorio . '/' . $nombre;
                    if (!is_dir($directorio) || !is_writable($directorio)) {
                        $errores['portada'] = 'Almacenamiento no disponible.';
                    }
                    if (file_exists($destino)) {
                        $errores['portada'] = 'Nombre ocupado; vuelve a enviar.';
                    }

                    $guardado = move_uploaded_file($archivo['tmp_name'], $destino);
                    if (!$guardado) {
                        $errores['portada'] = 'No se pudo guardar la portada.';
                    }
                } catch (Random\RandomException) {
                    $errores['portada'] = 'No se pudo preparar el archivo.';
                }
            }
        }
    }
}

?>

<form method="post" enctype="multipart/form-data">
    <input name="titulo">
    <input name="portada" type="file" accept="image/jpeg,image/png,image/webp">
    <button type="submit">Guardar</button>
</form>

<?php
if ($esPost) {
    echo 'Título: ' . $titulo . '<br>';
    echo 'Fichero: ' . $nombre . '<br>';
}
?>