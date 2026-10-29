<?php

declare(strict_types=1);

namespace Juan\Biblioteca\Modelo;

use Override;

class LibroPapel extends Libro {
    
    #[Override]
    public function getTipo(): string
    {
        return 'papel';
    }

    #[Override]
    public function costeReposicionesCentimos(): int
    {
        return $this->unidades() * $this->paginas() * 5;
    }
}