<?php

function procesarPedidos(array $stock, array $pedidos): array
{
    $demandas = array_map(
        function (array $pedido): array {
            $demanda = array_reduce(
                $pedido['lineas'],
                function (array $acumulado, array $linea): array {
                    [$producto, $unidades] = $linea;
                    $acumulado[$producto] = ($acumulado[$producto] ?? 0) + $unidades;
                    return $acumulado;
                },
                []
            );
            return ['id' => $pedido['id'], 'demanda' => $demanda];
        },
        $pedidos
    );

    return array_reduce(
        $demandas,
        function (array $resultado, array $pedido): array {
            $actual = $resultado['stock'];
            $puede = array_all(
                $pedido['demanda'],
                fn(int $unidades, string $producto): bool =>
                $unidades <= ($actual[$producto] ?? 0)
            );

            if (!$puede) {
                $resultado['rechazados'][] = $pedido['id'];
                return $resultado;
            }

            $productos = array_keys($actual);
            $resultado['stock'] = array_combine($productos, array_map(
                fn(string $producto, int $disponibles): int =>
                $disponibles - ($pedido['demanda'][$producto] ?? 0),
                $productos,
                array_values($actual)
            ));
            $resultado['aceptados'][] = $pedido['id'];
            return $resultado;
        },
        ['aceptados' => [], 'rechazados' => [], 'stock' => $stock]
    );
}

$stock = ['A' => 5, 'B' => 3, 'C' => 0];
$pedidos = [
    ['id' => 101, 'lineas' => [['A', 2], ['B', 1]]],
    ['id' => 102, 'lineas' => [['A', 2], ['B', 1], ['A', 2]]],
    ['id' => 103, 'lineas' => [['A', 1], ['B', 2]]],
    ['id' => 104, 'lineas' => [['C', 1]]],
    ['id' => 105, 'lineas' => [['A', 2]]],
    ['id' => 106, 'lineas' => [['D', 1]]],
];

print_r(procesarPedidos($stock, $pedidos));
