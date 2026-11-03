<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>UT3 · Ejemplos por presentación</title></head>
<body>
    <h1>UT3 · Ejemplos por presentación</h1>
    <ol>
        <li><a href="01_Formularios_validacion_y_objetos/index.php">Presentación 01 · Formularios, validación y objetos</a>: ejemplos 01–10.</li>
        <li><a href="02_Subida_segura_archivos/public/index.php">Presentación 02 · Subida segura de archivos</a>: ejemplos 01–05.</li>
    </ol>
    <p><a href="Ampliaciones/GET_objetos_sesiones/libro-formulario.php">Ampliación · GET, objetos, sesiones y POST-Redirect-GET</a>.</p>
    <h2>Comandos de arranque</h2>
    <p>Desde la carpeta Ejemplos, para la presentación 01 y la ampliación:</p>
    <pre><code>php -S 127.0.0.1:8001 -t 01_Formularios_validacion_y_objetos
php -S 127.0.0.1:8002 -t Ampliaciones/GET_objetos_sesiones</code></pre>
    <p>Ejecuta cada servidor en su propia terminal y abre el puerto correspondiente.
    En la ampliación, abre /libro-formulario.php.</p>
    <p>Para la presentación 02, sirve únicamente su carpeta public:</p>
    <pre><code>php -d upload_max_filesize=4M -d post_max_size=8M -d max_file_uploads=20 -S 127.0.0.1:8003 -t 02_Subida_segura_archivos/public 02_Subida_segura_archivos/public/router.php</code></pre>
    <p>Abre http://127.0.0.1:8003/. Necesitas fileinfo y mbstring activadas en PHP.
    Si fileinfo está instalada pero desactivada, añade <code>-d extension=fileinfo</code> después de <code>php</code>.
    Detén cada servidor con Ctrl+C.</p>
</body>
</html>
