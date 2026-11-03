<?php

declare(strict_types=1);

namespace Juan\Biblioteca\Modelo;

use Override;

class LibroDigital extends Libro {
    public function getTipo(): string
    {
        return 'digital';
    }

    public function costeReposicionesCentimos(): int
    {
        return $this->unidades() * 400;
    }
}