<?php

declare(strict_types=1);

// TODO M4. Implementa la base abstracta Juego y las operaciones del préstamo.


abstract class Juego implements Prestable
{
    use GeneraEtiqueta;

    private bool $prestado = false;
    private int $dias = 0;

    public function __construct(private int $id, private string $nombre)
    {
        if ($id <= 0 || trim($nombre) === '') {
            throw new InvalidArgumentException('Datos del juego inválidos.');
        }
        $this->id = $id;
        $this->nombre = trim($nombre);
    }

    public function id(): int
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function prestado(): bool
    {
        return $this->prestado;
    }

    public function dias(): int
    {
        return $this->dias;
    }

    public abstract function plazoMaximo(): int;

    public function ficha(): string
    {
        $estado = $this->prestado ? 'Prestado' : 'Disponible';
        return $this->etiqueta() . ' · ' . $estado . ' · Días: ' . $this->dias;
    }

    public function prestar(int $dias): void
    {
        if ($dias <= 0)
            throw new InvalidArgumentException('Los días deben ser positivos.');
        if ($this->prestado())
            throw new PrestamoException('El juego ya está prestado.');
        if ($dias > $this->plazoMaximo())
            throw new PrestamoException('Se supera el plazo máximo del juego.');
        $this->prestado = true;
        $this->dias = $dias;
    }

    public function renovar(int $diasExtra): void
    {
        if ($diasExtra <= 0)
            throw new InvalidArgumentException('Los días deben ser positivos');
        if (!$this->prestado())
            throw new PrestamoException('El juego no está prestado');
        if ($this->dias() + $diasExtra >  $this->plazoMaximo())
            throw new PrestamoException('Se supera el plazo máximo');
        $this->dias += $diasExtra;
    }

    public function devolver(): void
    {
        if (!$this->prestado)
            throw new PrestamoException('El juego no está prestado');
        $this->prestado = false;
        $this->dias = 0;
    }
}
