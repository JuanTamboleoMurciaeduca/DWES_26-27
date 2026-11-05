<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Model\Libro;

final class LibroTest extends TestCase
{
    public function testConservaElTitulo(): void
    {
        $libro = new Libro('Dune', 'Frank Herbert', 412);

        $this->assertSame('Dune', $libro->titulo());
    }
}
