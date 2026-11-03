<?php
declare(strict_types=1);
require __DIR__ . '/../src/pagina.php';
inicio('Presentación 02 · Subida segura de archivos · Ejemplos 01–05', '3–34');
?>
<p>Corresponden a la presentación UT3_02_Subida_segura_archivos (34 diapositivas).
Los números siguientes son páginas de esa presentación, incluida su portada.</p>
<table border="1" cellpadding="8">
    <thead><tr><th>Ejemplo ejecutable</th><th>Diapositivas y fragmentos asociados</th><th>Código que conviene abrir</th></tr></thead>
    <tbody>
        <tr><td><a href="01_recepcion.php">1. Recibir y diagnosticar</a></td>
            <td>3: temporal; 4–5: multipart, $_POST y $_FILES; 6–10: forma, ausencia y códigos; 12–13: límites del servidor.</td>
            <td>01_recepcion.php; src/subidas.php: estructuraValida, mensajeRecepcion, errorPeticion.</td></tr>
        <tr><td><a href="02_validar_guardar.php">2. Validar y guardar</a></td>
            <td>6–8: retornos y portada obligatoria; 11: tamaño; 14–17: MIME y extensión; 18–22: nombre, destino y movimiento.</td>
            <td>02_validar_guardar.php; src/subidas.php: validarArchivo y guardarArchivo.</td></tr>
        <tr><td><a href="03_publicar.php">3. Publicar mediante una URL</a></td>
            <td>23: fuera de public; 24: nombre permitido; 25–26: readfile y cabeceras; 27: img solicita otra URL.</td>
            <td>03_publicar.php; portada.php; src/pagina.php: imagen.</td></tr>
        <tr><td><a href="04_libro_portada.php">4. Alta, edición y compensación</a></td>
            <td>7–8: opcional; 28–29: condición global y orden completo; 30–31: conservar/restaurar y retirar nueva; 32: retirar antigua tras éxito; 34: casos.</td>
            <td>04_libro_portada.php; src/Libro.php; src/subidas.php: retirarArchivo.</td></tr>
        <tr><td><a href="05_varias_portadas.php">5. Varias portadas</a></td>
            <td>33: portadas[], arrays paralelos, forma e índices, validar todo antes de mover y compensar el lote.</td>
            <td>05_varias_portadas.php; src/subidas.php: normalizarMultiples.</td></tr>
    </tbody>
</table>
<h2>Cómo arrancarlos</h2>
<p>Desde la carpeta 02_Subida_segura_archivos, ejecuta:</p>
<pre><code>php -d upload_max_filesize=4M -d post_max_size=8M -d max_file_uploads=20 -S 127.0.0.1:8003 -t public public/router.php</code></pre>
<p>Abre <a href="http://127.0.0.1:8003/">http://127.0.0.1:8003/</a>. Detén el servidor con Ctrl+C.
Si ese puerto está ocupado, cambia <code>8003</code> por otro puerto en el comando y en la URL.</p>
<p>Necesitas PHP disponible en PATH, con fileinfo y mbstring activadas.
Si fileinfo está instalada pero desactivada, añade <code>-d extension=fileinfo</code> después de <code>php</code>.</p>
<p>La raíz HTTP debe ser <strong>public</strong>. Apuntar el servidor a toda la carpeta
Ejemplos expondría storage. Los directorios src, muestras y storage quedan fuera de la raíz pública.</p>
<p>El comando usa upload_max_filesize=4M y post_max_size=8M. El límite de la aplicación sigue siendo 2 MiB:
un archivo de 3 MiB llega a la validación; uno de más de 4 MiB permite observar el error de PHP.
Un cuerpo completo de más de 8 MiB permite observar el diagnóstico de POST vacío.</p>
<h2>Recorrido completo</h2>
<ol>
    <li>El navegador envía campos y archivo con POST multipart. PHP ya ha recibido el archivo antes de ejecutar nuestro script.</li>
    <li>El controlador comprueba límites de la petición y valida campos, forma de $_FILES, error, tamaño y MIME del temporal.</li>
    <li>Solo cuando no hay errores se construye el libro candidato y se prepara un nombre aleatorio con extensión del MIME.</li>
    <li>move_uploaded_file mueve el temporal a storage/portadas. Si falla, no se cambia la referencia.</li>
    <li>El repositorio guarda o actualiza el libro con el nombre generado. Si falla, se retira el archivo nuevo de ese intento.</li>
    <li>Después del éxito se retira la portada antigua si era exclusiva del libro. Un fallo de limpieza se registra.</li>
    <li>El HTML contiene una URL. En otra petición, portada.php valida el nombre y envía la imagen guardada.</li>
</ol>
<p>validarArchivo devuelve un resultado; no mueve ni imprime. Un return termina esa función y el controlador continúa.
guardarArchivo mueve; portada.php sirve; el Libro conserva solo el nombre.</p>
<h2>Pruebas para seguir las diapositivas</h2>
<p>En muestras hay portada.png, portada.jpg, portada.webp y texto.jpg (este último contiene texto, no una imagen).
Selecciona esos archivos desde el formulario. accept orienta el selector; utiliza «Todos los archivos» para elegir texto.jpg.</p>
<ul>
    <li>Ejemplo 1: enviar con y sin archivo; observar error, tmp_name, size, name y type.</li>
    <li>Ejemplo 2: los tres formatos válidos; texto.jpg debe rechazarse por contenido; sin archivo debe pedirlo.</li>
    <li>Ejemplo 3: seguir el enlace tras guardar; comprobar la imagen y las peticiones con nombre incorrecto (404).</li>
    <li>Ejemplo 4: alta sin portada; páginas 0 y PNG válido; texto.jpg; edición sin archivo;
        sustitución correcta; sustitución con fallo del repositorio.</li>
    <li>Ejemplo 5: dos imágenes; imagen y texto.jpg; dos imágenes con fallo al llegar al segundo movimiento.</li>
</ul>
<p>En el ejemplo 4 cada POST de edición crea una copia exclusiva de la muestra como portada anterior.
Eso prepara el escenario y no es una subida del usuario. El repositorio se reconstruye en cada petición;
los archivos guardados o conservados permanecen en storage/portadas para poder comprobarlos.</p>
<p>El control del MIME es una comprobación de formato, no una garantía absoluta de inocuidad.
El bloqueo de guardado coordina únicamente a los escritores que lo respetan.</p>
<p>Referencias: <a href="https://www.php.net/manual/en/features.file-upload.post-method.php">Recepción en PHP</a>,
<a href="https://www.php.net/manual/en/features.file-upload.errors.php">códigos de recepción</a>,
<a href="https://www.php.net/manual/en/function.move-uploaded-file.php">move_uploaded_file</a>,
<a href="https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html">OWASP File Upload</a>.</p>
</body></html>
