<?php

declare(strict_types=1);

require __DIR__ . '/../src/pagina.php';

$error = errorPeticion();

inicio('1. Recibir y diagnosticar', '3–13', $error !== null ? ['peticion' => $error] : []);

?>

<p>Este primer paso muestra lo que PHP ha recibido. No mueve el temporal.</p>
<form method="post" enctype="multipart/form-data">
    <p><label>Título <input name="titulo" value="Dune"></label></p>
    <p><label>Portada <input type="file" name="portada" accept="image/jpeg,image/png,image/webp"></label></p>
    <button>Examinar recepción</button>
</form>
<h2>Configuración efectiva</h2>
<dl>
    <?php foreach (['upload_max_filesize', 'post_max_size', 'max_file_uploads', 'upload_tmp_dir'] as $directiva): ?>
        <dt><?= e($directiva) ?></dt>
        <dd><?= e((string) ini_get($directiva)) ?: '(predeterminado del sistema)' ?></dd>
    <?php endforeach ?>
</dl>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($error !== null) {
        resultado(['peticion' => $error], []);
    }

    // Escapar también la salida de diagnóstico: name y type vienen del cliente.
    echo '<h2>$_POST</h2><pre>' . e(var_export($_POST, true)) . '</pre>';
    echo '<h2>$_FILES</h2><pre>' . e(var_export($_FILES, true)) . '</pre>';
    $archivo = $_FILES['portada'] ?? null;

    if ($archivo !== null && !estructuraValida($archivo)) {
        echo '<p>Entrada mal formada: se esperaba portada, no portada[].</p>';
    } elseif ($archivo !== null) {
        echo '<p>' . e(mensajeRecepcion($archivo['error']) ?? 'Recepción completa; todavía NO validado.') . '</p>';
    }

    echo '<p>Al terminar esta petición, PHP elimina el temporal que no hemos movido.</p>';
}
?>
</body>

</html>
