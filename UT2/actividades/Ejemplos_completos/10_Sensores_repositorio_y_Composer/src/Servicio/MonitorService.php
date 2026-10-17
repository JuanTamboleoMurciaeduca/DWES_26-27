<?php
declare(strict_types=1);

namespace Aula\Sensores\Servicio;
use Aula\Sensores\Repositorio\SensorRepository;
use Aula\Sensores\Modelo\Sensor;
use Aula\Sensores\Modelo\Nivel;
use OutOfBoundsException;

final class MonitorService
{
    public function __construct(private SensorRepository $repositorio) {}
    public function registrar(int $id, int $valor): void
    {
        // TODO registrar desde servicio
        throw new \LogicException("Pendiente: registrar desde servicio");
    }
    public function alertas(string $sala = ''): array
    {
        // TODO alertas
        throw new \LogicException("Pendiente: alertas");
    }
    public function resumen(): array
    {
        // TODO resumen
        throw new \LogicException("Pendiente: resumen");
    }
}
