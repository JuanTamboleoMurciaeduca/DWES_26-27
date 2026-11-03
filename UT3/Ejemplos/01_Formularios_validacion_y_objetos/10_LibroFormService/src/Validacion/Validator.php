<?php

declare(strict_types=1);

namespace App\Validacion;

final class Validator
{
    // Retorno: mensaje si falla; null si cumple. No devuelve el dato validado.
    public function requerido(string $valor, string $mensaje): ?string
    {
        return trim($valor) === '' ? $mensaje : null;
    }
}
