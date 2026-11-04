<?php

declare(strict_types=1);

namespace Ejemplos\E17;

interface Catalogo
{
    public function buscarTitulo(int $id): ?string;
}
