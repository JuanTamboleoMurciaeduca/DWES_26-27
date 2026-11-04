<?php

declare(strict_types=1);

namespace Ejemplos\E12;

final class Validator
{
    public function requerido(string $valor): ?string
    {
        return trim($valor) === '' ? 'Escribe el título.' : null;
    }
}
