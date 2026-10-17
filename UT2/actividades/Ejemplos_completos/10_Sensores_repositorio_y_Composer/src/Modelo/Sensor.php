<?php
declare(strict_types=1);

namespace Aula\Sensores\Modelo;
use InvalidArgumentException;

final class Sensor
{
    private string $sala;
    public function __construct(public readonly int $id, string $sala,
        private int $umbral, private ?int $lectura = null)
    {
        if ($id <= 0 || trim($sala) === '' || $umbral < 0 || $umbral > 100
            || ($lectura !== null && ($lectura < 0 || $lectura > 100))) {
            throw new InvalidArgumentException('Datos de sensor no válidos');
        }
        $this->sala = trim($sala);
    }
    public function sala(): string { return $this->sala; }
    public function lectura(): ?int { return $this->lectura; }
    public function registrar(int $valor): void
    {
        // TODO registrar
        throw new \LogicException("Pendiente: registrar");
    }
    public function nivel(): ?Nivel
    {
        // TODO nivel derivado
        throw new \LogicException("Pendiente: nivel derivado");
    }
}
