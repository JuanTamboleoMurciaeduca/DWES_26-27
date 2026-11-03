<?php
declare(strict_types=1);

final class Libro
{
    public function __construct(
        private string $titulo,
        private int $paginas,
        private ?string $portada = null,
    ) {
        if (trim($titulo) === '' || mb_strlen($titulo, 'UTF-8') > 120) {
            throw new InvalidArgumentException('Título no válido.');
        }
        if ($paginas < 1 || $paginas > 3000) {
            throw new InvalidArgumentException('Número de páginas no válido.');
        }
        $this->cambiarPortada($portada);
    }

    public function titulo(): string { return $this->titulo; }
    public function paginas(): int { return $this->paginas; }
    public function portada(): ?string { return $this->portada; }

    public function cambiarPortada(?string $nombre): void
    {
        if ($nombre !== null && !nombreValido($nombre)) {
            throw new InvalidArgumentException('Nombre de portada no válido.');
        }
        $this->portada = $nombre; // Guarda una referencia, nunca bytes ni ruta absoluta.
    }
}

// Diap. 29 y 31. Se reconstruye en CADA petición; no persiste libros entre envíos.
final class LibroRepositoryMemoria
{
    private array $libros = [];

    public function guardar(Libro $libro, bool $simularFallo = false): int
    {
        if ($simularFallo) {
            throw new RuntimeException('Fallo de repositorio simulado.');
        }
        $id = count($this->libros) + 1;
        $this->libros[$id] = clone $libro;
        return $id;
    }

    public function actualizar(int $id, Libro $libro, bool $simularFallo = false): void
    {
        if ($simularFallo || !isset($this->libros[$id])) {
            throw new RuntimeException('Fallo de actualización simulado.');
        }
        $this->libros[$id] = clone $libro;
    }

    public function obtener(int $id): Libro
    {
        return clone $this->libros[$id];
    }
}
