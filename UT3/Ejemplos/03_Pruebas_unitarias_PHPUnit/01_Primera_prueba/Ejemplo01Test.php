<?php

declare(strict_types=1);

namespace Ejemplos\E01;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo01Test extends TestCase
{
    public function testConservaElTitulo(): void
    {
        $libro = new Libro('Dune');

        $this->assertSame('Dune', $libro->titulo());
    }
}
