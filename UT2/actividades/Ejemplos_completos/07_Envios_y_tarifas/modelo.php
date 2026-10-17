<?php
declare(strict_types=1);

interface Cotizable { public function coste(): int; }

trait Referencia {
    public function referencia(string $prefijo = 'ENV-'): string
    {
        // TODO referencia
        throw new \LogicException("Pendiente: referencia");
    }
}

abstract class Envio implements Cotizable
{
    use Referencia;
    public readonly int $id;
    private string $destino;
    protected int $gramos;

    public function __construct(int $id, string $destino, int $gramos)
    {
        // TODO constructor
        throw new \LogicException("Pendiente: constructor");
    }
    public function destino(): string { return $this->destino; }
    public function gramos(): int { return $this->gramos; }
    abstract public function coste(): int;
}

final class Ordinario extends Envio
{
    public function coste(): int
    {
        // TODO coste ordinario
        throw new \LogicException("Pendiente: coste ordinario");
    }
}

final class Urgente extends Envio
{
    public function coste(): int
    {
        // TODO coste urgente
        throw new \LogicException("Pendiente: coste urgente");
    }
}

function totalCotizado(Cotizable ...$elementos): int
{
    // TODO total polimorfico
        throw new \LogicException("Pendiente: total polimorfico");
}
