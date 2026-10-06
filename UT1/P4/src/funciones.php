<?php

declare(strict_types=1);

function buscarPorId(array $libros, int $id): ?array
{
    foreach ($libros as $libro) {
        if ($libro['id'] === $id) {
            return $libro;
        }
    }
    return null;
}

function filtrarPorGenero(array $libros, string $genero): array
{
    $resultado = [];
    foreach ($libros as $libro) {
        if ($libro['genero'] === $genero) {
            $resultado[] = $libro;
        }
    }
    return $resultado;
}

function filtrarDisponibles(array $libros): array
{
    $resultado = [];
    foreach ($libros as $libro) {
        if ($libro['disponible'] === true) {
            $resultado[] = $libro;
        }
    }
    return $resultado;
}

function calcularMediaPaginas(array $libros): float
{
    if (count($libros) === 0) {
        return 0.0;
    }

    $totalPaginas = 0;
    foreach ($libros as $libro) {
        $totalPaginas += $libro['paginas'];
    }
    return $totalPaginas / count($libros);
}

function obtenerLibroMasLargo(array $libros): ?array
{
    if ($libros === []) {
        return null;
    }
    $masLargo = $libros[0];
    foreach ($libros as $libro) {
        if ($libro['paginas'] > $masLargo['paginas']) {
            $masLargo = $libro;
        }
    }
    return $masLargo;
}
