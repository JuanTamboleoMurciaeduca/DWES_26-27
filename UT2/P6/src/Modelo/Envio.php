<?php

declare(strict_types=1);

namespace App\Modelo;

use App\Excepcion\EnvioException;
use InvalidArgumentException;

abstract class Envio
{
    public function __construct(
        private readonly int $id,
        private string $cliente,
        private int $peso,
        private EstadoEnvio $estado
    ) {
        $cliente = trim($cliente);
        if ($id <= 0 || $cliente === '' || $peso < 1 || $peso > 20000) {
            throw new InvalidArgumentException('Id positivo, cliente no vacío y peso entre 1 y 20000 g.');
        }
        $this->cliente = $cliente;
    }

    public function id(): int
    {
        return $this->id;
    }
    public function cliente(): string
    {
        return $this->cliente;
    }
    public function peso(): int
    {
        return $this->peso;
    }
    public function estado(): EstadoEnvio
    {
        return $this->estado;
    }
    abstract public function modalidad(): string;
    abstract protected function cuotaFija(): int;
    abstract protected function precioBloque(): int;

    public function tarifa(): int
    {
        return $this->cuotaFija() + intdiv($this->peso + 999, 1000) * $this->precioBloque();
    }

    final public function enviar(): void
    {
        $this->cambiarEstado(EstadoEnvio::Pendiente, EstadoEnvio::Enviado);
    }

    final public function entregar(): void
    {
        $this->cambiarEstado(EstadoEnvio::Enviado, EstadoEnvio::Entregado);
    }

    final public function cancelar(): void
    {
        $this->cambiarEstado(EstadoEnvio::Pendiente, EstadoEnvio::Cancelado);
    }

    private function cambiarEstado(EstadoEnvio $origen, EstadoEnvio $destino): void
    {
        if ($this->estado !== $origen) {
            throw new EnvioException('No se puede pasar de ' . $this->estado->value . ' a ' . $destino->value . '.');
        }
        $this->estado = $destino;
    }
}
