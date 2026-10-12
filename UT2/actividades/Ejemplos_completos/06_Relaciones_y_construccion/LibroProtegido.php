<?php
declare(strict_types=1);

// La breve variante private de las diapositivas 26-28.
// Solo consultas: no desarrollamos encapsulación ni setters de la presentación 3.
final class LibroProtegido
{
    public function __construct(private string $titulo, private int $paginas)
    {
        $sinTitulo = trim($titulo) === '';

        if ($sinTitulo || $paginas <= 0) {
            throw new InvalidArgumentException('Título o páginas no válidos');
        }

        $this->titulo = trim($titulo);
    }

    public function ficha(): string
    {
        return "$this->titulo - $this->paginas páginas";
    }
}
