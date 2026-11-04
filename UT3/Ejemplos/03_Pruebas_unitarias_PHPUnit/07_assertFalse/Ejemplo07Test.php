<?php

declare(strict_types=1);

namespace Ejemplos\E07;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo07Test extends TestCase
{
    public function testPrestarCambiaElEstado(): void
    {
        $libro = new Libro();

        $libro->prestar();

        $this->assertFalse($libro->estaDisponible());
    }
}
