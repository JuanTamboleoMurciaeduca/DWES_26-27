<?php

declare(strict_types=1);

// TODO M6. Implementa la clase abstracta Actividad, sus reglas y las operaciones final.
abstract class Actividad implements Reservable
{
    use GeneraEtiqueta;

    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
        private readonly int $capacidad,
        private int $ocupadas,
        private readonly EstadoActividad $estado,
        private readonly ?Responsable $responsable
    ) {
        if (
            $id <= 0 || trim($nombre) === '' || $capacidad <= 0
            || $ocupadas < 0 || $ocupadas > $capacidad
        ) {
            throw new InvalidArgumentException('Datos de actividad inválidos.');
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function capacidad(): int
    {
        return $this->capacidad;
    }

    public function ocupadas(): int
    {
        return $this->ocupadas;
    }

    public function estado(): EstadoActividad
    {
        return $this->estado;
    }

    public function responsable(): ?Responsable
    {
        return $this->responsable;
    }

    public function plazasLibres(): int
    {
        return $this->capacidad - $this->ocupadas;
    }

    public abstract function tipo(): string;
    public abstract function importe(int $cantidad): int;

    public final function reservar(int $cantidad): void
    {
        if ($cantidad <= 0)
            throw new InvalidArgumentException();
        if ($cantidad > $this->ocupadas)
            throw new ReservaException();
        $this->ocupadas -= $cantidad;
    }

    final public function cancelar(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad debe ser positiva.');
        }
        if ($cantidad > $this->ocupadas) {
            throw new ReservaException('No puedes cancelar más plazas de las ocupadas.');
        }
        $this->ocupadas -= $cantidad;
    }
}
