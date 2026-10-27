<?php

declare(strict_types=1);

namespace App\Repositorio;

use App\Contrato\RepositorioEnvios;
use App\Excepcion\EnvioException;
use App\Modelo\Envio;
use InvalidArgumentException;

class RepositorioEnviosMemoria implements RepositorioEnvios
{
    private array $envios = [];

    public function registrar(Envio $envio): void
    {
        if (isset($this->envios[$envio->id()])) {
            throw new EnvioException('Ya existe el envío ' . $envio->id() . '.');
        }
        $this->envios[$envio->id()] = $envio;
    }

    function todos(): array
    {
        return array_values($this->envios);
    }

    function buscar(int $id): ?Envio
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El id debe ser positivo.');
        }
        return $this->envios[$id] ?? null;
    }
}
