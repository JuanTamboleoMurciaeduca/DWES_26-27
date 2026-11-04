<?php

declare(strict_types=1);

namespace Ejemplos\E09;

use PHPUnit\Framework\TestCase;

final class Ejemplo09Test extends TestCase
{
    public function testHayDosTitulos(): void
    {
        $titulos = ['Dune', 'Solaris'];

        $this->assertCount(2, $titulos);
    }
}
