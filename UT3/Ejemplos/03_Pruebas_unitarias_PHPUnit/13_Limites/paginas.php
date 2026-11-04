<?php

declare(strict_types=1);

namespace Ejemplos\E13;

function paginasValidas(string $entrada): bool
{
    $numero = filter_var($entrada, FILTER_VALIDATE_INT);

    return $numero !== false && $numero >= 1 && $numero <= 3000;
}
