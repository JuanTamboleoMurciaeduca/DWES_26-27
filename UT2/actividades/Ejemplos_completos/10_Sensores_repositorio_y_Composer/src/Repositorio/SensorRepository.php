<?php
declare(strict_types=1);

namespace Aula\Sensores\Repositorio;
use Aula\Sensores\Modelo\Sensor;
interface SensorRepository
{
    /** @return list<Sensor> */
    public function todos(): array;
    public function buscar(int $id): ?Sensor;
}
