<?php

declare(strict_types=1);

namespace Clase\Alquileres\Modelo;

use Clase\Alquileres\Excepcion\AlquilerException;
use InvalidArgumentException;

abstract class Alquiler
{
    public function __construct(
        private readonly int $id,
        private string $cliente,
        private int $dias,
        private EstadoAlquiler $estado
    ) {
        $this->cliente = trim($cliente);

        if ($this->cliente === '') {
            throw new InvalidArgumentException('Cliente vacío');
        }
        if ($this->id <= 0)
            throw new InvalidArgumentException('Id no positivo');
        if ($this->dias < 1 || $this->dias > 7)
            throw new InvalidArgumentException('Días no valido');
    }

    public function id(): int
    {
        return $this->id;
    }
    public function cliente(): string
    {
        return $this->cliente;
    }
    public function dias(): int
    {
        return $this->dias;
    }
    public function estado(): EstadoAlquiler
    {
        return $this->estado;
    }


    public abstract function modalidad(): string;
    protected abstract function tarifaDiaria(): int;

    public function importe(): int
    {
        return $this->dias * $this->tarifaDiaria();
    }

    public function iniciar(): void
    {
        if ($this->estado() !== EstadoAlquiler::Reservado)
            throw new AlquilerException('Estado incorrecto');
        $this->estado = EstadoAlquiler::Activo;
    }

    public function finalizar(): void
    {
        if ($this->estado() !== EstadoAlquiler::Activo)
            throw new AlquilerException('Estado incorrecto');
        $this->estado = EstadoAlquiler::Finalizado;
    }
}
