<?php

declare(strict_types=1);

namespace Ejemplos\E04;

use PHPUnit\Framework\TestCase;

final class Ejemplo04Test extends TestCase
{
    public function testComparaValorYTipo(): void
    {
        $paginas = 412;

        $this->assertSame(412, $paginas);
    }
}
