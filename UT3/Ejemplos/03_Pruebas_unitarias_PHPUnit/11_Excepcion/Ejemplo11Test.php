<?php

declare(strict_types=1);

namespace Ejemplos\E11;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo11Test extends TestCase
{
    public function testNoPermitePrestarDosVeces(): void
    {
        $libro = new Libro();
        $libro->prestar();

        $this->expectException(\DomainException::class);

        $libro->prestar();
    }
}
