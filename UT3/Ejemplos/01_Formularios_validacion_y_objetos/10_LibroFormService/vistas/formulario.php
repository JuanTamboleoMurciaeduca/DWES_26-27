<?php use App\Modelo\Libro; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LibroFormService completo · UT3</title>
    <style>
        body { font: 1rem/1.5 system-ui, sans-serif; max-width: 64rem; margin: 2rem auto; padding: 0 1rem; color: #172b36; }
        h1, h2 { line-height: 1.2; }
        fieldset, .resultado { border: 1px solid #aabfc5; padding: 1.25rem; margin: 1.5rem 0; }
        label { display: block; font-weight: 600; }
        input, select, button { font: inherit; padding: .45rem; box-sizing: border-box; }
        input, select { width: 100%; max-width: 34rem; }
        button { margin: .3rem .5rem .3rem 0; cursor: pointer; }
        .campo { margin: 0 0 1.2rem; }
        .error { display: block; color: #9c1b20; }
        .correcto { border-left: 5px solid #087f8c; }
        pre { background: #f1f5f6; padding: 1rem; overflow-wrap: anywhere; white-space: pre-wrap; }
        code { font-size: .95em; }
        dt { font-weight: 600; } dd { margin-bottom: .5rem; }
        a { color: #086776; }
    </style>
    <script src="formulario.js" defer></script>
</head>
<body>
    <p><a href="../index.php">Volver a los ejemplos de UT3</a></p>
    <h1>Del formulario al objeto Libro</h1>
    <p>El navegador comprueba los controles. Después, <code>LibroFormService</code>
        prepara y valida lo recibido. Si no hay errores, <code>ejemplo.php</code>
        construye un <code>Libro</code>, que protege sus propias reglas.</p>
    <p>Este ejemplo desarrolla los cinco campos de las diapositivas 33–46.</p>

    <?php if ($errores !== []): ?>
        <p role="alert" class="error">No se ha creado el libro. Corrige los campos indicados.</p>
    <?php endif; ?>
    <?php if ($errorGeneral !== null): ?>
        <p role="alert" class="error">El modelo ha rechazado los datos: <?= e($errorGeneral) ?></p>
    <?php endif; ?>

    <form method="post" action="ejemplo.php" id="formulario-libro">
        <fieldset>
            <legend>Ficha del libro</legend>
            <div class="campo">
                <label for="titulo">Título</label>
                <input id="titulo" name="titulo" type="text" required maxlength="<?= Libro::MAX_TITULO ?>"
                    value="<?= e($valores['titulo']) ?>" aria-describedby="error-titulo">
                <span id="error-titulo" class="error"><?= e($errores['titulo'] ?? '') ?></span>
            </div>
            <div class="campo">
                <label for="autor">Autor</label>
                <input id="autor" name="autor" type="text" required
                    value="<?= e($valores['autor']) ?>" aria-describedby="error-autor">
                <span id="error-autor" class="error"><?= e($errores['autor'] ?? '') ?></span>
            </div>
            <div class="campo">
                <label for="paginas">Páginas (<?= Libro::MIN_PAGINAS ?>–<?= Libro::MAX_PAGINAS ?>)</label>
                <!-- Texto permite conservar "12abc" al estudiar un envío incorrecto. -->
                <input id="paginas" name="paginas" type="text" inputmode="numeric" required
                    data-min="<?= Libro::MIN_PAGINAS ?>" data-max="<?= Libro::MAX_PAGINAS ?>"
                    value="<?= e($valores['paginas']) ?>" aria-describedby="error-paginas">
                <span id="error-paginas" class="error"><?= e($errores['paginas'] ?? '') ?></span>
            </div>
            <div class="campo">
                <label for="genero">Género</label>
                <select id="genero" name="genero" required aria-describedby="error-genero">
                    <option value="">Elige un género</option>
                    <?php foreach (Libro::GENEROS as $genero): ?>
                        <option value="<?= e($genero) ?>" <?= $valores['genero'] === $genero ? 'selected' : '' ?>>
                            <?= e(['ciencia' => 'Ciencia', 'fantasia' => 'Fantasía', 'terror' => 'Terror'][$genero]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span id="error-genero" class="error"><?= e($errores['genero'] ?? '') ?></span>
            </div>
            <div class="campo">
                <label for="fechaAlta">Fecha de alta (opcional, AAAA-MM-DD)</label>
                <input id="fechaAlta" name="fechaAlta" type="text" placeholder="2026-10-29"
                    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                    value="<?= e($valores['fechaAlta']) ?>" aria-describedby="error-fechaAlta">
                <span id="error-fechaAlta" class="error"><?= e($errores['fechaAlta'] ?? '') ?></span>
            </div>
            <button type="button" id="rellenar">Rellenar con Dune</button>
            <button type="submit">Validar y crear Libro</button>
            <!-- formnovalidate omite las comprobaciones del navegador, nunca las de PHP. -->
            <button type="submit" formnovalidate>Enviar sin validación del navegador</button>
        </fieldset>
    </form>
    <p>Para comprobar el servidor, rellena el ejemplo, cambia páginas por <code>12abc</code>
        y pulsa «Enviar sin validación del navegador». El texto se conserva y aparece su error.</p>

    <?php if ($libro !== null): ?>
        <section class="resultado correcto" id="libro-creado">
            <h2>Libro creado en esta petición</h2>
            <dl>
                <dt>Título</dt><dd><?= e($libro->titulo()) ?></dd>
                <dt>Autor</dt><dd><?= e($libro->autor()) ?></dd>
                <dt>Páginas</dt><dd><?= $libro->paginas() ?> (<?= e(get_debug_type($libro->paginas())) ?>)</dd>
                <dt>Género</dt><dd><?= e($libro->genero()) ?></dd>
                <dt>Fecha</dt><dd><?= e($libro->fechaAlta()?->format('Y-m-d') ?? 'Sin fecha') ?></dd>
            </dl>
            <p>Se ha construido el objeto. No se ha guardado en un repositorio;
                al volver mediante GET comienza otra petición.</p>
        </section>
    <?php endif; ?>

    <h2>Qué devuelve procesar()</h2>
    <?php if ($resultado === null): ?>
        <p>En este GET inicial todavía no se ha llamado al servicio. Envía el formulario para ver su resultado.</p>
    <?php else: ?>
        <?php foreach ([
            'valores' => 'Cadenas para volver a rellenar el formulario, incluso si son incorrectas.',
            'errores' => 'Mensajes por campo. Un array vacío significa que todos los campos son válidos.',
            'datos' => 'Datos tipados para crear Libro. Si hay algún error, es null.',
        ] as $clave => $explicacion): ?>
            <h3><?= e($clave) ?></h3>
            <p><?= e($explicacion) ?></p>
            <pre id="resultado-<?= e($clave) ?>"><?= e(var_export($resultado[$clave], true)) ?></pre>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>Dónde está cada parte</h2>
    <ol>
        <li><code>ejemplo.php</code>: recibe GET o POST, llama al servicio y decide si crea el objeto.</li>
        <li><code>src/Servicio/LibroFormService.php</code>: contiene juntos el constructor,
            <code>validarPaginas()</code> y el método <code>procesar()</code> completo.</li>
        <li><code>src/Validacion/Validator.php</code>: aporta la regla reutilizable de campo obligatorio.</li>
        <li><code>src/Modelo/Libro.php</code>: conserva propiedades privadas y rechaza estados inválidos.</li>
        <li><code>vistas/formulario.php</code>: representa valores, mensajes y resultado con escape HTML.</li>
        <li><code>formulario.js</code>: comprueba páginas en el navegador y permite rellenar el ejemplo.</li>
    </ol>
    <p><a href="modelo-directo.php">Comprobar qué ocurre al crear Libro directamente, sin formulario</a>.</p>
</body>
</html>
