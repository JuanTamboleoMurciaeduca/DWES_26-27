<?php
declare(strict_types=1);

namespace Aula\Sensores\Repositorio;
use Aula\Sensores\Modelo\Sensor;
use InvalidArgumentException;

final class SensorRepositoryMemoria implements SensorRepository
{
    /** @param list<Sensor> $sensores */
    public function __construct(private array $sensores)
    {
        $ids = array_map(fn (Sensor $s): int => $s->id, $sensores);
        if (count($ids) !== count(array_unique($ids))) {
            throw new InvalidArgumentException('Id de sensor duplicado');
        }
    }
    public function todos(): array { return $this->sensores; }
    public function buscar(int $id): ?Sensor
    {
        // TODO buscar
        throw new \LogicException("Pendiente: buscar");
    }
}
