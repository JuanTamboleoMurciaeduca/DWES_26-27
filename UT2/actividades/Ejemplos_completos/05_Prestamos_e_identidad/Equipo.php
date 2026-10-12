<?php
declare(strict_types=1);

final class Equipo
{
    public function __construct(
        public int $id,
        public string $nombre,
        public bool $disponible = true,
    ) {
    }

    public function prestar(): void
    {
        if (!$this->disponible) {
            echo "$this->nombre: préstamo rechazado; ya estaba prestado\n";
            return; // Sin valor: sigue siendo un método void.
        }

        $this->disponible = false;

        echo "$this->nombre: préstamo realizado\n";
    }

    public function devolver(): void
    {
        if ($this->disponible) {
            echo "$this->nombre: devolución rechazada; ya estaba disponible\n";
            return;
        }

        $this->disponible = true;

        echo "$this->nombre: devolución realizada\n";
    }

    public function ficha(): string
    {
        return "$this->id - $this->nombre: " . ($this->disponible ? 'disponible' : 'prestado');
    }

    // Un getter consulta un dato; un setter lo cambia. Las acciones del dominio
    // prestar/devolver expresan más intención que cambiar disponible desde fuera.
    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
}
