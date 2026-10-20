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
        /* INICIO registrar */
        if ($valor < 0 || $valor > 100) { throw new InvalidArgumentException('Lectura fuera de 0 a 100'); }
        $this->lectura = $valor;
        /* FIN */
    }
    public function nivel(): ?Nivel
    {
        /* INICIO nivel derivado */
        if ($this->lectura === null) { return null; }
        return $this->lectura >= $this->umbral ? Nivel::Alerta : Nivel::Normal;
        /* FIN */
    }
}
