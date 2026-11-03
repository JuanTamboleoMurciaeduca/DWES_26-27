<?php

declare(strict_types=1);

namespace App\Servicio;

use App\Modelo\Libro;
use App\Validacion\Validator;
use DateTimeImmutable;
use ValueError;

final class LibroFormService
{
    public function __construct(private Validator $v) {}

    // Este método pertenece al servicio, igual que procesar().
    public function validarPaginas(mixed $entrada): ?string
    {
        $n = is_string($entrada)
            ? filter_var($entrada, FILTER_VALIDATE_INT)
            : false;

        if ($n === false || $n < Libro::MIN_PAGINAS || $n > Libro::MAX_PAGINAS) {
            return 'Entre 1 y 3000 páginas.';
        }

        // "412" cumple la regla: devolvemos null porque NO hay error.
        // $n contiene 412, pero el contrato de este método solo devuelve el error.
        return null;
    }

    /**
     * Recibe los campos enviados, no un Libro.
     *
     * @return array{
     *   valores: array<string, string>,
     *   errores: array<string, string>,
     *   datos: array{titulo: string, autor: string, paginas: int,
     *                genero: string, fechaAlta: DateTimeImmutable|null}|null
     * }
     */
    public function procesar(array $entrada): array
    {
        // 1. Preparar cadenas para el formulario y conservar errores de tipo.
        $valores = [];
        $errores = [];
        $campos = ['titulo', 'autor', 'paginas', 'genero', 'fechaAlta'];

        foreach ($campos as $campo) {
            $valor = $entrada[$campo] ?? '';
            if (!is_string($valor)) {
                $errores[$campo] = 'Debe ser texto.';
                $valor = '';
            }
            $valores[$campo] = trim($valor);
        }

        // 2. Reutilizar Validator para los dos textos obligatorios.
        $mensajes = ['titulo' => 'Escribe el título.', 'autor' => 'Escribe el autor.'];
        foreach ($mensajes as $campo => $mensaje) {
            if (isset($errores[$campo])) {
                continue; // No sustituir "Debe ser texto" por "Escribe el título".
            }
            $error = $this->v->requerido($valores[$campo], $mensaje);
            if ($error !== null) {
                $errores[$campo] = $error;
            }
        }

        if (!isset($errores['titulo'])
            && mb_strlen($valores['titulo'], 'UTF-8') > Libro::MAX_TITULO) {
            $errores['titulo'] = 'Máximo 120 caracteres.';
        }

        // 3. Páginas sigue siendo texto. "12abc" debe fallar antes de convertir.
        if (!isset($errores['paginas'])) {
            $error = $this->validarPaginas($valores['paginas']);
            if ($error !== null) {
                $errores['paginas'] = $error;
            }
        }
        if (!isset($errores['genero'])
            && !in_array($valores['genero'], Libro::GENEROS, true)) {
            $errores['genero'] = 'Elige un género válido.';
        }

        // 4. La fecha vacía es opcional; una fecha escrita debe ser posible y exacta.
        $fecha = null;
        $textoFecha = $valores['fechaAlta'];
        if (!isset($errores['fechaAlta']) && $textoFecha !== '') {
            try {
                $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $textoFecha);
            } catch (ValueError) {
                $fecha = false;
            }
            if ($fecha === false || $fecha->format('Y-m-d') !== $textoFecha) {
                $errores['fechaAlta'] = 'Fecha no válida.';
            }
        }

        // 5. Solo hay datos para el modelo cuando TODOS los campos son válidos.
        $datos = null;
        if ($errores === []) {
            $datos = [
                'titulo' => $valores['titulo'],
                'autor' => $valores['autor'],
                'paginas' => (int) $valores['paginas'],
                'genero' => $valores['genero'],
                'fechaAlta' => $fecha,
            ];
        }

        // valores: cadenas para volver a mostrar, incluido el texto incorrecto.
        // errores: mensajes por campo; [] si todo es correcto.
        // datos: valores tipados para Libro, o null si hay cualquier error.
        return ['valores' => $valores, 'errores' => $errores, 'datos' => $datos];
    }
}
