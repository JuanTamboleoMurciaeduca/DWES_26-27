<?php

declare(strict_types=1);

// TODO M7. Implementa la especialización final de pago y su constructor.
final class ActividadPago extends Actividad
{
    public function __construct(
        int $id,
        string $nombre,
        int $capacidad,
        int $ocupadas,
        EstadoActividad $estado,
        ?Responsable $responsable,
        private readonly int $precioCentimos
    ) {
        if ($precioCentimos <= 0) {
            throw new InvalidArgumentException('El precio debe ser positivo.');
        }
        parent::__construct($id, $nombre, $capacidad, $ocupadas, $estado, $responsable);
    }

    public function tipo(): string
    {
        return 'Pago';
    }

    public function importe(int $cantidad): int
    {
        if ($cantidad < 0)
            throw new InvalidArgumentException();
        return $cantidad * $this->precioCentimos;
    }
}
