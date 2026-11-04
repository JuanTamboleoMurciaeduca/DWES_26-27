<?php

declare(strict_types=1);

namespace Ejemplos\E10;

use PHPUnit\Framework\TestCase;

final class Ejemplo10Test extends TestCase
{
    public function testExisteElErrorDeTitulo(): void
    {
        $errores = ['titulo' => 'Escribe el título.'];

        $this->assertArrayHasKey('titulo', $errores);
    }
}
