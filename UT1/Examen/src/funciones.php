<?php

declare(strict_types=1);

function normalizarTexto(string $texto): string
{
    return strtolower(trim($texto));
}

function buscarPorId(array $videojuegos, int $id): ?array
{
    foreach ($videojuegos as $videojuego) {
        if ($videojuego['id'] === $id) {
            return $videojuego;
        }
    }

    return null;
}

function filtrarPorGenero(array $videojuegos, string $genero): array
{

    $resultado = [];

    foreach ($videojuegos as $videojuego) {
        if (normalizarTexto($genero) === normalizarTexto($videojuego['genero']))
            $resultado[] = $videojuego;
    }

    return $resultado;
}

function filtrarPorPlataforma(array $videojuegos, string $plataforma): array
{
    $resultado = [];

    foreach ($videojuegos as $videojuego) {
        if (normalizarTexto($plataforma) === normalizarTexto($videojuego['plataforma']))
            $resultado[] = $videojuego;
    }

    return $resultado;
}

function buscarPorTexto(array $videojuegos, string $texto): array
{
    $resultado = [];
    $texto = normalizarTexto($texto);

    foreach ($videojuegos as $videojuego) {
        $titulo = normalizarTexto($videojuego['titulo']);
        $estudio = normalizarTexto($videojuego['estudio']);

        if (str_contains($titulo, $texto) || str_contains($estudio, $texto)) {
            $resultado[] = $videojuego;
        }
    }

    return $resultado;
}

function ordenarVideojuegos(array $videojuegos, string $criterio): array
{
    $criterio = normalizarTexto($criterio);
    $cantidad = count($videojuegos);

    for ($i = 0; $i < $cantidad; $i++) {
        for ($j = 0; $j < $cantidad - 1; $j++) {
            $actual = $videojuegos[$j];
            $siguiente = $videojuegos[$j + 1];

            $intercambiar = match ($criterio) {
                'precio' => $actual['precio'] > $siguiente['precio'],
                'puntuacion' => $actual['puntuacion'] < $siguiente['puntuacion'],
                default => $actual['titulo'] > $siguiente['titulo']
            };

            if ($intercambiar) {
                $temporal = $videojuegos[$j];
                $videojuegos[$j] = $videojuegos[$j + 1];
                $videojuegos[$j + 1] = $temporal;
            }
        }
    }

    return $videojuegos;
}
