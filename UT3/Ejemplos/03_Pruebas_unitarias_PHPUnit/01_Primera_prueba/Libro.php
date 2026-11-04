<?php

declare(strict_types=1);

namespace Ejemplos\E01;

final class Libro
{
    public function __construct(private string $titulo)
    {
    }

    public function titulo(): string
    {
        return $this->titulo;
    }
}
