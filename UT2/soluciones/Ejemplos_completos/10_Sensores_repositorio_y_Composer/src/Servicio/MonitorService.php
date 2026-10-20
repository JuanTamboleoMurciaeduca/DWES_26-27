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
        /* INICIO registrar desde servicio */
        $sensor = $this->repositorio->buscar($id);
        if ($sensor === null) { throw new OutOfBoundsException('Sensor no encontrado'); }
        $sensor->registrar($valor);
        /* FIN */
    }
    public function alertas(string $sala = ''): array
    {
        /* INICIO alertas */
        $sala = mb_strtolower(trim($sala), 'UTF-8');
        return array_values(array_filter($this->repositorio->todos(),
            function (Sensor $s) use ($sala): bool {
                return $s->nivel() === Nivel::Alerta
                    && ($sala === '' || mb_strtolower(trim($s->sala()), 'UTF-8') === $sala);
            }));
        /* FIN */
    }
    public function resumen(): array
    {
        /* INICIO resumen */
        return array_reduce($this->repositorio->todos(), function (array $r, Sensor $s): array {
            if ($s->lectura() === null) { $r['sinLectura']++; }
            else { $r['medidos']++; }
            if ($s->nivel() === Nivel::Alerta) { $r['alertas']++; }
            return $r;
        }, ['medidos' => 0, 'sinLectura' => 0, 'alertas' => 0]);
        /* FIN */
    }
}
