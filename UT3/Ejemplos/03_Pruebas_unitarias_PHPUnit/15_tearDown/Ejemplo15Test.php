<?php

declare(strict_types=1);

namespace Ejemplos\E15;

use PHPUnit\Framework\TestCase;

final class Ejemplo15Test extends TestCase
{
    private ?string $temporal = null;

    protected function tearDown(): void
    {
        if ($this->temporal !== null && is_file($this->temporal)) {
            $this->assertTrue(unlink($this->temporal));
        }
    }

    public function testEscribeYLeeUnTemporal(): void
    {
        $ruta = tempnam(sys_get_temp_dir(), 'ut3_e15_');
        $this->assertNotFalse($ruta);
        $this->temporal = $ruta;

        file_put_contents($ruta, 'Dune');

        $this->assertSame('Dune', file_get_contents($ruta));
    }
}
