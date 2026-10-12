<?php
declare(strict_types=1);
final class Libro
{
    public function __construct(
        public string $titulo,
        public int $paginas,
        public Categoria $categoria,
        public ?Autor $autor = null,
    ) {
        $sinTitulo = trim($titulo) === '';
        if ($sinTitulo || $paginas <= 0) {
            throw new InvalidArgumentException('Título o páginas no válidos');
        }
        $this->titulo = trim($titulo);
    }

    public function ficha(): string
    {
        $autor = $this->autor?->nombre ?? 'Sin autor';
        return "$this->titulo / $autor / {$this->categoria->nombre} / $this->paginas páginas";
    }
}
