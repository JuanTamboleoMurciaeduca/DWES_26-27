<?php

declare(strict_types=1);

namespace Ejemplos\E20;

function tamanoValido(int $bytes): bool
{
    return $bytes >= 1 && $bytes <= 2 * 1024 * 1024;
}
