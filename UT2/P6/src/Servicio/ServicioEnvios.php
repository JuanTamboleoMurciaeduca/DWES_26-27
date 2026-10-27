<?php

declare(strict_types=1);

namespace App\Servicio;

use App\Contrato\RepositorioEnvios;
use App\Excepcion\EnvioException;
use App\Modelo\Envio;
use App\Modelo\EstadoEnvio;
use InvalidArgumentException;

class ServicioEnvios
{
    public function __construct(private RepositorioEnvios $repositorio) {}

    public function enviar(int $id): void
    {
        $this->obtener($id)->enviar();
    }

    public function entregar(int $id): void
    {
        $this->obtener($id)->entregar();
    }

    public function cancelar(int $id): void
    {
        $this->obtener($id)->cancelar();
    }

    private function obtener(int $id): Envio
    {
        return $this->repositorio->buscar($id)
            ?? throw new EnvioException('No existe el envío ' . $id . '.');
    }

    private static function normalizar(string $texto): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim($texto)), 'UTF-8');
    }

    public function consultar(
        string $texto = '',
        ?string $modalidad = null,
        ?EstadoEnvio $estado = null,
        string $orden = 'precio',
    ): array {
        // La validación precede al recorrido: funciona con un repositorio vacío.
        if ($modalidad !== null && !in_array($modalidad, ['normal', 'urgente'], true)) {
            throw new InvalidArgumentException('Modalidad desconocida.');
        }
        if (!in_array($orden, ['precio', 'peso'], true)) {
            throw new InvalidArgumentException('Orden desconocido.');
        }
        $busqueda = self::normalizar($texto);
        $visibles = array_filter(
            $this->repositorio->todos(),
            fn(Envio $e): bool =>
            str_contains(self::normalizar($e->cliente()), $busqueda)
                && ($modalidad === null || $e->modalidad() === $modalidad)
                && ($estado === null || $e->estado() === $estado)
        );
        usort($visibles, function (Envio $a, Envio $b) use ($orden): int {
            $comparacion = $orden === 'peso'
                ? $a->peso() <=> $b->peso()
                : $a->tarifa() <=> $b->tarifa();
            return $comparacion ?: ($a->id() <=> $b->id());
        });
        return array_values($visibles);
    }

    public function etiquetas(array $envios): array
    {
        return array_map(fn(Envio $e): string => $e->id() . ' | ' . $e->cliente()
            . ' | ' . $e->modalidad() . ' | ' . $e->estado()->value
            . ' | ' . $e->tarifa() . ' céntimos', $envios);
    }

    public function resumir(array $envios): array
    {
        return array_reduce($envios, function (array $s, Envio $e): array {
            $s['cantidad']++;
            $s['peso'] += $e->peso();
            $s['importeTotal'] += $e->tarifa();
            if ($e->estado() !== EstadoEnvio::Cancelado) {
                $s['importeActivo'] += $e->tarifa();
            }
            $s[$e->estado()->value]++;
            return $s;
        }, [
            'cantidad' => 0,
            'peso' => 0,
            'importeTotal' => 0,
            'importeActivo' => 0,
            'pendiente' => 0,
            'enviado' => 0,
            'entregado' => 0,
            'cancelado' => 0
        ]);
    }
}
