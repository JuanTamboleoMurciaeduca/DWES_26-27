<?php
declare(strict_types=1);

require __DIR__ . '/../src/pagina.php';

inicio('3. Guardar y publicar son pasos distintos', '23–27');
$nombre = $_GET['nombre'] ?? '';
?>
<p>Primero guarda una imagen en el <a href="02_validar_guardar.php">ejemplo 2</a> y sigue su enlace.</p>
<p>PHP usa la ruta física storage/portadas/nombre.png. El navegador solicita la URL portada.php?nombre=nombre.png.</p>
<?php if (nombreValido($nombre) && is_file(DIRECTORIO . '/' . $nombre) && !is_link(DIRECTORIO . '/' . $nombre)): ?>
    <?php imagen($nombre); ?>
    <p><a href="portada.php?nombre=<?= e($nombre) ?>">Abrir la respuesta de imagen</a></p>
<?php else: ?>
    <p>No se ha indicado una portada guardada.</p>
<?php endif ?>
<p><a href="portada.php?nombre=../src/subidas.php">Probar nombre con ../: respuesta 404</a></p>
<p><a href="portada.php?nombre[]=imagen.png">Probar nombre como array: respuesta 404</a></p>
<p>La etiqueta img provoca otra petición HTTP. portada.php valida el nombre, comprueba el MIME, envía cabeceras y llama a readfile.</p>
</body></html>
