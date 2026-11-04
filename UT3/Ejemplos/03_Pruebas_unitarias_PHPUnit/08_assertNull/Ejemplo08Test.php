<?php

declare(strict_types=1);

namespace Ejemplos\E08;

use PHPUnit\Framework\TestCase;

final class Ejemplo08Test extends TestCase
{
    public function testNoHayLibroConEseId(): void
    {
        $titulos = [1 => 'Dune'];

        $encontrado = $titulos[999] ?? null;

        $this->assertNull($encontrado);
    }
}
