<?php
declare(strict_types=1);

enum Estado: string { case Abierta = 'abierta'; case Asignada = 'asignada'; case Resuelta = 'resuelta'; }
final class IncidenciaException extends RuntimeException {}

final class Incidencia
{
    private Estado $estado = Estado::Abierta;
    private ?string $tecnico = null;
    private string $descripcion;

    public function __construct(public readonly int $id, string $descripcion)
    {
        if ($id <= 0 || trim($descripcion) === '') {
            throw new InvalidArgumentException('Id o descripción no válida');
        }
        $this->descripcion = trim($descripcion);
    }
    public function estado(): Estado { return $this->estado; }
    public function tecnico(): ?string { return $this->tecnico; }
    public function descripcion(): string { return $this->descripcion; }

    public function asignar(string $tecnico): void
    {
        /* INICIO asignar */
        $tecnico = trim($tecnico);
        if ($tecnico === '') { throw new InvalidArgumentException('Falta el técnico'); }
        if ($this->estado === Estado::Resuelta) { throw new IncidenciaException('Reabre antes de asignar'); }
        if ($this->tecnico === $tecnico) { throw new IncidenciaException('Ese técnico ya está asignado'); }
        $this->tecnico = $tecnico;
        $this->estado = Estado::Asignada;
        /* FIN */
    }
    public function resolver(): void
    {
        /* INICIO resolver */
        if ($this->estado !== Estado::Asignada) { throw new IncidenciaException('Solo se resuelve una asignada'); }
        $this->estado = Estado::Resuelta;
        /* FIN */
    }
    public function reabrir(): void
    {
        /* INICIO reabrir */
        if ($this->estado !== Estado::Resuelta) { throw new IncidenciaException('Solo se reabre una resuelta'); }
        $this->estado = Estado::Abierta;
        $this->tecnico = null;
        /* FIN */
    }
}
