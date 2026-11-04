<?php

declare(strict_types=1);

use App\Modelo\Libro;
use App\Servicio\LibroFormService;
use App\Validacion\Validator;

// Cargas explícitas para poder abrir el ejemplo sin ejecutar Composer.
// Los namespaces y las carpetas mantienen la organización estudiada en UT2.
require_once __DIR__ . '/src/Validacion/Validator.php';
require_once __DIR__ . '/src/Modelo/Libro.php';
require_once __DIR__ . '/src/Servicio/LibroFormService.php';

header('Content-Type: text/html; charset=UTF-8');
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($metodo !== 'GET' && $metodo !== 'POST') {
    header('Allow: GET, POST');
    http_response_code(405);
    exit('Método no permitido.');
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// GET: mostrar valores iniciales sin validar ni construir un Libro.
$valores = ['titulo' => '', 'autor' => '', 'paginas' => '',
    'genero' => 'ciencia', 'fechaAlta' => ''];
$errores = [];
$resultado = null;
$libro = null;
$errorGeneral = null;

if ($metodo === 'POST') {
    // El controlador conoce HTTP; el servicio recibe un array y no lee $_POST.
    $formulario = new LibroFormService(new Validator());
    $resultado = $formulario->procesar($_POST);
    $valores = $resultado['valores'];
    $errores = $resultado['errores'];

    if ($errores !== []) {
        http_response_code(422);
    } else {
        // El servicio NO crea ni guarda libros. El controlador toma esa decisión.
        $datos = $resultado['datos'];

        try {
            $libro = new Libro(
                $datos['titulo'], $datos['autor'], $datos['paginas'],
                $datos['genero'], $datos['fechaAlta']
            );
        } catch (InvalidArgumentException $excepcion) {
            // Protección si las reglas del servicio y del modelo dejan de coincidir.
            $errorGeneral = $excepcion->getMessage();
            http_response_code(422);
        }
    }
}

// La vista recibe las variables ya preparadas. Aquí termina el procesamiento.
require __DIR__ . '/vistas/formulario.php';
