<?php

declare(strict_types=1);

namespace App\Modelo;

use DateTimeImmutable;
use InvalidArgumentException;

final class Libro
{
    // El formulario, el servicio y el modelo consultan los mismos límites.
    public const MAX_TITULO = 120;
    public const MIN_PAGINAS = 1;
    public const MAX_PAGINAS = 3000;
    public const GENEROS = ['ciencia', 'fantasia', 'terror'];

    public function __construct(
        private string $titulo,
        private string $autor,
        private int $paginas,
        private string $genero = 'ciencia',
        private ?DateTimeImmutable $fechaAlta = null,
    ) {
        $this->titulo = trim($titulo);
        $this->autor = trim($autor);

        // Estas reglas también se aplican si otro script crea un Libro sin formulario.
        // Aquí ya recibimos un int: no necesitamos interpretar $_POST ni "12abc".
        if ($this->titulo === '' || mb_strlen($this->titulo, 'UTF-8') > self::MAX_TITULO) {
            throw new InvalidArgumentException('El título debe tener entre 1 y 120 caracteres.');
        }

        if ($this->autor === '') {
            throw new InvalidArgumentException('El autor no puede estar vacío.');
        }

        if ($this->paginas < self::MIN_PAGINAS || $this->paginas > self::MAX_PAGINAS) {
            throw new InvalidArgumentException('El libro debe tener entre 1 y 3000 páginas.');
        }

        if (!in_array($this->genero, self::GENEROS, true)) {
            throw new InvalidArgumentException('El género del libro no está permitido.');
        }

        // La fecha ya llega como DateTimeImmutable o null, nunca como texto de POST.
    }

    public function titulo(): string
    {
        return $this->titulo;
    }

    public function autor(): string
    {
        return $this->autor;
    }

    public function paginas(): int
    {
        return $this->paginas;
    }

    public function genero(): string
    {
        return $this->genero;
    }

    public function fechaAlta(): ?DateTimeImmutable
    {
        return $this->fechaAlta;
    }
}
