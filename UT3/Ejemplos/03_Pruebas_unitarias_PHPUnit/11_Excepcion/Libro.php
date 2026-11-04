<?php

declare(strict_types=1);

namespace Ejemplos\E11;

final class Libro
{
    private bool $disponible = true;

    public function prestar(): void
    {
        if (!$this->disponible) {
            throw new \DomainException('Ya está prestado.');
        }

        $this->disponible = false;
    }
}
