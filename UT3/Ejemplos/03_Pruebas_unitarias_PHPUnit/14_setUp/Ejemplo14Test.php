<?php

declare(strict_types=1);

namespace Ejemplos\E14;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo14Test extends TestCase
{
    private Libro $libro;

    protected function setUp(): void
    {
        $this->libro = new Libro();
    }

    public function testEmpiezaDisponible(): void
    {
        $this->assertTrue($this->libro->estaDisponible());
    }

    public function testPrestarLoDejaNoDisponible(): void
    {
        $this->libro->prestar();

        $this->assertFalse($this->libro->estaDisponible());
    }
}
