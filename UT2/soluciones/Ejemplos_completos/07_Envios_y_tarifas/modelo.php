<?php
declare(strict_types=1);

interface Cotizable { public function coste(): int; }

trait Referencia {
    public function referencia(string $prefijo = 'ENV-'): string
    {
        /* INICIO referencia */
        return $prefijo . $this->id;
        /* FIN */
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
        /* INICIO constructor */
        if ($id <= 0 || trim($destino) === '' || $gramos < 1 || $gramos > 5000) {
            throw new InvalidArgumentException('Id, destino o peso no válido');
        }
        $this->id = $id;
        $this->destino = trim($destino);
        $this->gramos = $gramos;
        /* FIN */
    }
    public function destino(): string { return $this->destino; }
    public function gramos(): int { return $this->gramos; }
    abstract public function coste(): int;
}

final class Ordinario extends Envio
{
    public function coste(): int
    {
        /* INICIO coste ordinario */
        // intdiv evita importes decimales: cada bloque iniciado cuenta completo.
        return 200 + 80 * intdiv($this->gramos + 499, 500);
        /* FIN */
    }
}

final class Urgente extends Envio
{
    public function coste(): int
    {
        /* INICIO coste urgente */
        return 450 + ($this->gramos > 1000 ? 150 : 0);
        /* FIN */
    }
}

function totalCotizado(Cotizable ...$elementos): int
{
    /* INICIO total polimorfico */
    return array_reduce($elementos, fn (int $s, Cotizable $e): int => $s + $e->coste(), 0);
    /* FIN */
}
