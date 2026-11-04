<?php

declare(strict_types=1);

namespace Ejemplos\E16;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Catalogo.php';
require_once __DIR__ . '/Biblioteca.php';
require_once __DIR__ . '/CatalogoMemoria.php';

final class Ejemplo16Test extends TestCase
{
    public function testObtieneElTituloDelCatalogo(): void
    {
        $catalogo = new CatalogoMemoria();
        $biblioteca = new Biblioteca($catalogo);

        $titulo = $biblioteca->titulo(1);

        $this->assertSame('Dune', $titulo);
    }
}
