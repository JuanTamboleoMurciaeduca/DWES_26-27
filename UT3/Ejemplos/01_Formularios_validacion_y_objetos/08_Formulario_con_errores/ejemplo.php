<?php

declare(strict_types=1);

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($metodo !== 'GET' && $metodo !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}
$esPost = $metodo === 'POST';
$valores = [
    'titulo' => '',
    'autor' => '',
    'paginas' => '',
    'genero' => 'ciencia',
    'fechaAlta' => '',
];
$errores = [];
$destacado = false;
$datos = null;

// PASO 1. Obtener, comprobar tipos y normalizar sin perder el texto.
if ($esPost) {
    foreach ($valores as $campo => $inicial) {
        $entrada = $_POST[$campo] ?? '';
        if (!is_string($entrada)) {
            $errores[$campo] = 'Debe ser texto.';
            $entrada = '';
        }
        $valores[$campo] = trim($entrada);
    }

    $marca = $_POST['destacado'] ?? null;
    if ($marca !== null && $marca !== '1') {
        $errores['destacado'] = 'Casilla no válida.';
    }
    $destacado = $marca === '1';

    // PASO 2. Validar campo por campo.
    if (!isset($errores['titulo'])) {
        if ($valores['titulo'] === '') {
            $errores['titulo'] = 'Escribe el título.';
        } elseif (mb_strlen($valores['titulo'], 'UTF-8') > 120) {
            $errores['titulo'] = 'Máximo 120 caracteres.';
        }
    }

    if (!isset($errores['autor']) && $valores['autor'] === '') {
        $errores['autor'] = 'Escribe el autor.';
    }

    $paginas = filter_var($valores['paginas'], FILTER_VALIDATE_INT);
    if (!isset($errores['paginas']) &&
        ($paginas === false || $paginas < 1 || $paginas > 3000)) {
        $errores['paginas'] = 'Entre 1 y 3000 páginas.';
    }

    if (!isset($errores['genero']) &&
        !in_array($valores['genero'], ['ciencia', 'fantasia', 'terror'], true)) {
        $errores['genero'] = 'Elige un género válido.';
    }

    $fecha = null;
    if (!isset($errores['fechaAlta']) && $valores['fechaAlta'] !== '') {
        try {
            $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $valores['fechaAlta']);
        } catch (ValueError) {
            $fecha = false;
        }
        if ($fecha === false || $fecha->format('Y-m-d') !== $valores['fechaAlta']) {
            $errores['fechaAlta'] = 'Fecha no válida.';
        }
    }

    // PASO 3. Solo preparar datos tipados si todas las reglas pasan.
    if ($errores === []) {
        $datos = [
            'titulo' => $valores['titulo'],
            'autor' => $valores['autor'],
            'paginas' => $paginas,       // int
            'genero' => $valores['genero'],
            'fechaAlta' => $fecha,      // DateTimeImmutable|null
            'destacado' => $destacado,  // bool (extra del formulario)
        ];
    } else {
        http_response_code(422);
    }
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Formulario completo de libro</title></head>
<body>
    <h1>Alta de libro con validación</h1>
    <p>Prueba <code>12abc</code> en páginas: los demás campos conservarán su valor.</p>

    <?php if ($datos !== null): ?>
        <h2>Datos válidos (todavía no guardados)</h2>
        <p>Título: <?= e($datos['titulo']) ?></p>
        <p>Autor: <?= e($datos['autor']) ?></p>
        <p>Páginas: <?= $datos['paginas'] ?></p>
        <p>Género: <?= e($datos['genero']) ?></p>
        <p>Fecha: <?= $datos['fechaAlta']?->format('d/m/Y') ?? 'Sin fecha' ?></p>
        <p>Destacado: <?= $datos['destacado'] ? 'Sí' : 'No' ?></p>
    <?php endif; ?>

    <?php if ($esPost && $errores !== []): ?>
        <p>Corrige los errores y vuelve a enviar el formulario.</p>
    <?php endif; ?>

    <form method="post" action="ejemplo.php" novalidate>
        <p>
            <label for="titulo">Título</label>
            <input id="titulo" name="titulo" type="text"
                   value="<?= e($valores['titulo']) ?>">
            <span><?= e($errores['titulo'] ?? '') ?></span>
        </p>
        <p>
            <label for="autor">Autor</label>
            <input id="autor" name="autor" type="text"
                   value="<?= e($valores['autor']) ?>">
            <span><?= e($errores['autor'] ?? '') ?></span>
        </p>
        <p>
            <label for="paginas">Páginas</label>
            <input id="paginas" name="paginas" type="text"
                   value="<?= e($valores['paginas']) ?>">
            <span><?= e($errores['paginas'] ?? '') ?></span>
        </p>
        <p>
            <label for="genero">Género</label>
            <select id="genero" name="genero">
                <option value="ciencia" <?= $valores['genero'] === 'ciencia' ? 'selected' : '' ?>>Ciencia</option>
                <option value="fantasia" <?= $valores['genero'] === 'fantasia' ? 'selected' : '' ?>>Fantasía</option>
                <option value="terror" <?= $valores['genero'] === 'terror' ? 'selected' : '' ?>>Terror</option>
            </select>
            <span><?= e($errores['genero'] ?? '') ?></span>
        </p>
        <p>
            <label for="fechaAlta">Fecha de alta (opcional)</label>
            <input id="fechaAlta" name="fechaAlta" type="date"
                   value="<?= e($valores['fechaAlta']) ?>">
            <span><?= e($errores['fechaAlta'] ?? '') ?></span>
        </p>
        <p>
            <label>
                <input type="checkbox" name="destacado" value="1"
                    <?= $destacado ? 'checked' : '' ?>> Libro destacado
            </label>
            <span><?= e($errores['destacado'] ?? '') ?></span>
        </p>
        <button type="submit">Comprobar</button>
    </form>
</body>
</html>
