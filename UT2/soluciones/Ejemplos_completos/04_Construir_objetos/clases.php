<?php
declare(strict_types=1);

// Versión explícita: parámetro local y propiedad son elementos diferentes.
final class TallerExplicito
{
    public string $nombre;
    public int $plazas;
    public bool $abierto;

    public function __construct(string $nombre, int $plazas, bool $abierto = true)
    {
        $this->nombre = $nombre;
        $this->plazas = $plazas;
        $this->abierto = $abierto;
    }

    public function ficha(): string
    {
        return "$this->nombre - $this->plazas plazas";
    }

    public function mostrarFicha(): void
    {
        echo $this->ficha() . PHP_EOL;
    }
}

// Versión promovida: public en el parámetro declara e inicializa la propiedad.
final class TallerPromovido
{
    public function __construct(
        public string $nombre,
        public int $plazas,
        public bool $abierto = true,
    ) {}

    public function ficha(): string
    {
        return "$this->nombre - $this->plazas plazas";
    }
}

// Declarar string no inicializa la propiedad; null sí es un valor inicial.
final class TallerBorrador
{
    public string $nombre;
    public int $plazas = 0;
    public ?string $observaciones = null;
}
