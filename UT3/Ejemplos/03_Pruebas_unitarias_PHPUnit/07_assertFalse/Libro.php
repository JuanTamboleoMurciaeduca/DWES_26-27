<?php

declare(strict_types=1);

namespace Ejemplos\E07;

final class Libro
{
    private bool $disponible = true;

    public function estaDisponible(): bool
    {
        return $this->disponible;
    }

    public function prestar(): void
    {
        $this->disponible = false;
    }
}
