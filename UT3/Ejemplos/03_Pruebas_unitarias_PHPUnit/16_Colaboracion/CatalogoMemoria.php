<?php

declare(strict_types=1);

namespace Ejemplos\E16;

final class CatalogoMemoria implements Catalogo
{
    private array $titulos = [1 => 'Dune'];

    public function buscarTitulo(int $id): ?string
    {
        return $this->titulos[$id] ?? null;
    }
}
