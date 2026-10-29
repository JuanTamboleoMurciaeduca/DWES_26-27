<?php

declare(strict_types=1);

namespace Juan\Biblioteca\Modelo;

use Override;

class LibroDigital extends Libro {
    
    #[Override]
    public function getTipo(): string
    {
        return 'digital';
    }

    #[Override]
    public function costeReposicionesCentimos(): int
    {
        return $this->unidades() * 400;
    }
}