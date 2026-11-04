<?php

declare(strict_types=1);

namespace Ejemplos\E21;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/mime.php';

final class Ejemplo21Test extends TestCase
{
    #[DataProvider('tipos')]
    public function testFormatosPermitidos(string $mime, bool $esperado): void
    {
        $this->assertSame($esperado, mimePermitido($mime));
    }

    public static function tipos(): array
    {
        return [
            'JPEG' => ['image/jpeg', true],
            'PNG' => ['image/png', true],
            'WebP' => ['image/webp', true],
            'texto' => ['text/plain', false],
        ];
    }
}
