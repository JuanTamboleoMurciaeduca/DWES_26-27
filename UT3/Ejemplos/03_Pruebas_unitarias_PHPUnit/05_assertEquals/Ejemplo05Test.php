<?php

declare(strict_types=1);

namespace Ejemplos\E05;

use PHPUnit\Framework\TestCase;

final class Ejemplo05Test extends TestCase
{
    public function testComparaUnNumeroYUnaCadenaNumerica(): void
    {
        $paginas = '412';

        $this->assertEquals(412, $paginas);
    }
}
