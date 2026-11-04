<?php

declare(strict_types=1);

namespace Ejemplos\E12;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/Validator.php';

final class Ejemplo12Test extends TestCase
{
    #[DataProvider('titulos')]
    public function testRequerido(string $entrada, ?string $esperado): void
    {
        $validator = new Validator();

        $error = $validator->requerido($entrada);

        $this->assertSame($esperado, $error);
    }

    public static function titulos(): array
    {
        return [
            'vacío' => ['', 'Escribe el título.'],
            'espacios' => ['   ', 'Escribe el título.'],
            'válido' => ['Dune', null],
        ];
    }
}
