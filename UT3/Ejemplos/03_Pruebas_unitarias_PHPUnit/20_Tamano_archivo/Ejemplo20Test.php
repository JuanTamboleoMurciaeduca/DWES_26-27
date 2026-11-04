<?php

declare(strict_types=1);

namespace Ejemplos\E20;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/tamano.php';

final class Ejemplo20Test extends TestCase
{
    public function testElMaximoEsInclusivo(): void
    {
        $this->assertTrue(tamanoValido(2097152));
        $this->assertFalse(tamanoValido(2097153));
    }
}
