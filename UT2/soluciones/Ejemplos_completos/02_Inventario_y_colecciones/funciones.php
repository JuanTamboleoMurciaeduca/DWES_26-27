<?php
declare(strict_types=1);

function nombreProducto(array $producto): string
{
    return $producto['nombre'];
}

function transformar(array $productos, callable $callback): array
{
    return array_map($callback, $productos);
}

function seleccionar(array $productos, string $busqueda): array
{
    $busqueda = mb_strtolower(trim($busqueda), 'UTF-8');
    // La arrow captura $busqueda automáticamente por valor.
    return array_filter($productos, fn (array $p): bool =>
        $p['stock'] > 0 && str_contains(mb_strtolower($p['nombre'], 'UTF-8'), $busqueda));
}

function etiquetas(array $productos): array
{
    return array_map(fn (array $p): string => $p['nombre'] . ' - ' . $p['precio'] . ' EUR', $productos);
}
