<?php

declare(strict_types=1);

namespace Ejemplos\E06;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo06Test extends TestCase
{
    public function testEmpiezaDisponible(): void
    {
        $libro = new Libro();

        $this->assertTrue($libro->estaDisponible());
    }
}
