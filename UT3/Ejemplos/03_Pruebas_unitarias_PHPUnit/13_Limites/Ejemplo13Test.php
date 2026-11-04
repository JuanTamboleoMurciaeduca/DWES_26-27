<?php

declare(strict_types=1);

namespace Ejemplos\E13;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/paginas.php';

final class Ejemplo13Test extends TestCase
{
    #[DataProvider('limites')]
    public function testLimitesDePaginas(string $entrada, bool $esperado): void
    {
        $resultado = paginasValidas($entrada);

        $this->assertSame($esperado, $resultado);
    }

    public static function limites(): array
    {
        return [
            'mínimo' => ['1', true],
            'máximo' => ['3000', true],
            'por debajo' => ['0', false],
            'por encima' => ['3001', false],
        ];
    }
}
