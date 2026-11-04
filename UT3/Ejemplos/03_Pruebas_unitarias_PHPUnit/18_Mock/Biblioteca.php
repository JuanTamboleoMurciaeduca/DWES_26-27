<?php

declare(strict_types=1);

namespace Ejemplos\E18;

final class Biblioteca
{
    public function __construct(private Catalogo $catalogo)
    {
    }

    public function titulo(int $id): string
    {
        return $this->catalogo->buscarTitulo($id) ?? 'No encontrado';
    }
}
