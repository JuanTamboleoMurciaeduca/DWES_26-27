<?php

declare(strict_types=1);

require_once __DIR__ . '/subidas.php';

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function inicio(string $titulo, string $diapositivas, array $errores = []): void
{
    header('Content-Type: text/html; charset=UTF-8');
    if ($errores !== []) {
        http_response_code(errorPeticion() !== null ? 413 : 422);
    }
    if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'POST'], true)) {
        header('Allow: GET, POST');
        http_response_code(405);
        exit('Método no permitido.');
    }
    echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>' . e($titulo)
        . '</title><body><p><a href="index.php">Índice de ejemplos</a></p><h1>'
        . e($titulo) . '</h1><p>Presentación 02 · Diapositivas ' . e($diapositivas) . '</p>';
}

function resultado(array $errores, array $traza): void
{
    if ($errores !== []) {
        echo '<h2>Errores</h2><ul>';
        foreach ($errores as $campo => $error) {
            echo '<li>' . e((string) $campo) . ': ' . e($error) . '</li>';
        }
        echo '</ul><p>Selecciona otra vez el archivo al corregir el formulario.</p>';
    }
    if ($traza !== []) {
        echo '<h2>Recorrido de esta petición</h2><ol>';
        foreach ($traza as $paso) {
            echo '<li>' . e($paso) . '</li>';
        }
        echo '</ol>';
    }
}

function imagen(string $nombre): void
{
    echo '<p>Nombre guardado: <code>' . e($nombre) . '</code></p>';
    echo '<img src="portada.php?nombre=' . e($nombre) . '" alt="Portada guardada" width="180">';
}
