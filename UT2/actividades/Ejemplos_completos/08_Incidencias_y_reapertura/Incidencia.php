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
        // TODO asignar
        throw new \LogicException("Pendiente: asignar");
    }
    public function resolver(): void
    {
        // TODO resolver
        throw new \LogicException("Pendiente: resolver");
    }
    public function reabrir(): void
    {
        // TODO reabrir
        throw new \LogicException("Pendiente: reabrir");
    }
}
