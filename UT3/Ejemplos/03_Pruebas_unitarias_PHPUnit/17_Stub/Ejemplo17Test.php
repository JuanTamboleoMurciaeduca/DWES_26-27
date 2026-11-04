<?php

declare(strict_types=1);

namespace Ejemplos\E17;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Catalogo.php';
require_once __DIR__ . '/Biblioteca.php';

final class Ejemplo17Test extends TestCase
{
    public function testInformaDeUnTituloAusente(): void
    {
        $catalogo = $this->createStub(Catalogo::class);
        $catalogo->method('buscarTitulo')->willReturn(null);
        $biblioteca = new Biblioteca($catalogo);

        $titulo = $biblioteca->titulo(999);

        $this->assertSame('No encontrado', $titulo);
    }
}
