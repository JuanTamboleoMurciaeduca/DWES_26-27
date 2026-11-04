<?php

declare(strict_types=1);

namespace Ejemplos\E02;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Libro.php';

final class Ejemplo02Test extends TestCase
{
    public function testConservaElTitulo(): void
    {
        // Arrange. Preparar la entrada.
        $titulo = 'Dune';

        // Act. Ejecutar el comportamiento.
        $libro = new Libro($titulo);

        // Assert. Comprobar el resultado.
        $this->assertSame('Dune', $libro->titulo());
    }
}
