<?php

declare(strict_types=1);

namespace Alquiler\Equipo\Servicio;

use Alquiler\Equipo\Contrato\EquipoRepository;
use Alquiler\Equipo\Excepcion\EquipoException;
use Alquiler\Equipo\Modelo\{Equipo, EstadoEquipo};

final class InventarioService
{
    public function __construct(private EquipoRepository $repository) {}

    public function filtrar(
        string $texto = '',
        ?EstadoEquipo $estado = null,
        int $minDisponibles = 0
    ): array {
        $texto = mb_strtolower(trim($texto), 'UTF-8');

        if ($minDisponibles < 0) {
            throw new \InvalidArgumentException('Mínimo negativo');
        }

        // TODO F1: filtra con array_filter y devuelve claves consecutivas.
        return array_values(array_filter(
            $this->repository->todos(),
            fn(Equipo $e): bool =>
            str_contains($e->nombreNormalizado(), $texto) &&
                ($estado === null || $e->estado() === $estado) &&
                ($minDisponibles === 0 || ($e->disponibles() >= $minDisponibles && $e->estado() === EstadoEquipo::Disponible))
        ));
    }

    public function ordenar(array $equipos): array
    {
        // TODO F2: ordena una copia mediante usort y un callback.
        usort(
            $equipos,
            fn(Equipo $a, Equipo $b): int => ($b->disponibles() <=> $a->disponibles())
                ?: ($a->tarifaDia() <=> $b->tarifaDia())
                ?: ($a->id() <=> $b->id())
        );

        return $equipos;
    }

    public function fichas(array $equipos, string $prefijo = 'Equipo: '): array
    {
        // TODO F3: utiliza array_map y una arrow function.
        return array_map(
            fn(Equipo $e): string =>
            $e->ficha($prefijo),
            $equipos
        );
    }

    public function resumen(array $equipos): array
    {
        // TODO F4: acumula las tres cifras mediante array_reduce.
        return array_reduce(
            $equipos,
            fn(array $carry, Equipo $e): array => [
                "unidades" => $carry["unidades"] + $e->unidades(),
                "reservadas" => $carry["reservadas"] + $e->reservadas(),
                "valorDisponibleCentimos" => $carry["valorDisponibleCentimos"] + $e->disponibles() * $e->tarifaDia()
            ],
            ["unidades" => 0, "reservadas" => 0, "valorDisponibleCentimos" => 0]
        );
    }

    public function presupuestarLotes(
        int $cantidad,
        int $dias,
        int $presupuestoCentimos
    ): array {
        // TODO D1: repara las etapas conservando las funciones de arrays.
        if ($cantidad <= 0 || $dias <= 0 || $presupuestoCentimos < 0) {
            throw new \InvalidArgumentException('Cantidad, días o presupuesto no válidos');
        }

        $candidatos = array_filter(
            $this->repository->todos(),
            fn(Equipo $equipo): bool =>
            $equipo->estado() === EstadoEquipo::Disponible
                && $equipo->disponibles() >= $cantidad
        );

        $propuestas = array_map(
            fn(Equipo $equipo): array => [
                'id' => $equipo->id(),
                'importeCentimos' => $equipo->tarifaDia() * $dias * $cantidad,
                'disponiblesDespues' => $equipo->disponibles() - $cantidad
            ],
            $candidatos
        );

        $opciones = array_reduce(
            $propuestas,
            function (array $resultado, array $propuesta) use ($presupuestoCentimos): array {
                if ($propuesta['importeCentimos'] <= $presupuestoCentimos) {
                    $resultado[] = $propuesta;
                }

                return $resultado;
            },
            []
        );

        usort(
            $opciones,
            fn(array $a, array $b): int => ($a['importeCentimos'] <=> $b['importeCentimos']) ?: ($a['id'] <=> $b['id'])
        );

        return $opciones;
    }

    private function requerido(int $id): Equipo
    {
        return $this->repository->buscarPorId($id)
            ?? throw new EquipoException('El equipo no existe');
    }

    public function reservar(int $id, int $cantidad): void
    {
        $this->requerido($id)->reservar($cantidad);
    }

    public function devolver(int $id, int $cantidad): void
    {
        $this->requerido($id)->devolver($cantidad);
    }
}
