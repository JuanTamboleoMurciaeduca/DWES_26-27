<?php

declare(strict_types=1);

// TODO M7. Implementa la especialización final gratuita.
final class ActividadGratuita extends Actividad
{
    public function __construct(int $id, string $nombre, int $capacidad, int $ocupadas, EstadoActividad $estado, ?Responsable $responsable)
    {
        return parent::__construct($id, $nombre, $capacidad, $ocupadas, $estado, $responsable);
    }

    public function tipo(): string
    {
        return "Gratuita";
    }

    public function importe(int $cantidad): int
    {
        if ($cantidad <= 0)
            throw new InvalidArgumentException();
        return 0;
    }
}
