<?php

declare(strict_types=1);

namespace Ejemplos\E19;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/paginas.php';

final class Ejemplo19Test extends TestCase
{
    public function testRechazaUnNumeroMezcladoConTexto(): void
    {
        $resultado = paginasValidas('12abc');

        $this->assertFalse($resultado);
    }
}
