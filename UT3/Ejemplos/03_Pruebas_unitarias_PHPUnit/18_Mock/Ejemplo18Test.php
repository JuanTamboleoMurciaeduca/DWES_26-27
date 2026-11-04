<?php

declare(strict_types=1);

namespace Ejemplos\E18;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Catalogo.php';
require_once __DIR__ . '/Biblioteca.php';

final class Ejemplo18Test extends TestCase
{
    public function testConsultaUnaVezElIdSolicitado(): void
    {
        $catalogo = $this->createMock(Catalogo::class);
        $catalogo->expects($this->once())
            ->method('buscarTitulo')
            ->with(1)
            ->willReturn('Dune');

        $biblioteca = new Biblioteca($catalogo);

        $this->assertSame('Dune', $biblioteca->titulo(1));
    }
}
