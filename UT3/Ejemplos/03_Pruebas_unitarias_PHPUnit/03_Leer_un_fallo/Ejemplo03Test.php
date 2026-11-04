<?php

declare(strict_types=1);

namespace Ejemplos\E03;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo03Test extends TestCase
{
    public function testMuestraUnFallo(): void
    {
        $libro = new Libro('Dune');

        $this->assertSame('Solaris', $libro->titulo());
    }
}
