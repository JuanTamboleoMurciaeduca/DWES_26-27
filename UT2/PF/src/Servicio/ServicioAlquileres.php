<?php

declare(strict_types=1);

namespace Clase\Alquileres\Servicio;

use Clase\Alquileres\Modelo\Alquiler;
use Clase\Alquileres\Modelo\EstadoAlquiler;

class ServicioAlquileres
{
    public function __construct(private array $alquileres) {}

    public function todos(): array
    {
        return $this->alquileres;
    }

    private function normalizar(string $texto): string
    {
        return mb_strtolower(trim($texto), 'UTF-8');
    }

    public function consultar(
        string $texto = '',
        ?EstadoAlquiler $estado = null
    ): array {
        $texto = $this->normalizar($texto);

        $filtro = array_filter(
            $this->todos(),
            fn(Alquiler $a): bool =>
            str_contains(
                $this->normalizar($a->cliente()),
                $texto
            )
                && ($estado === null || $a->estado() === $estado)
        );

        usort(
            $filtro,
            fn(Alquiler $a, Alquiler $b): int => ($a->importe() <=> $b->importe()) === 0
                ? $a->id() <=> $b->id()
                : $a->importe() <=> $b->importe()
        );

        return $filtro;
    }

    public function resumir(array $alquileres): array
    {
        return array_reduce(
            $alquileres,
            fn(array $carry, Alquiler $item): array => [
                'cantidad' => $carry['cantidad'] + 1,
                'importe' => $carry['importe'] + $item->importe()
            ],
            ['cantidad' => 0, 'importe' => 0]
        );
    }
}
