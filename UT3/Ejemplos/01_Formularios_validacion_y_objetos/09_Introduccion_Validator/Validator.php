<?php

declare(strict_types=1);

// Una regla reutilizable: null indica que NO hay error.
final class Validator
{
    public function requerido(string $valor, string $mensaje): ?string
    {
        return trim($valor) === '' ? $mensaje : null;
    }
}
