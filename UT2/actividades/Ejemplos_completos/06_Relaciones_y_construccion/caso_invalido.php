<?php
declare(strict_types=1);

require __DIR__ . '/Autor.php';
require __DIR__ . '/Categoria.php';
require __DIR__ . '/Libro.php';

// Cada caso se ejecuta en otro proceso: una excepción sin capturar detiene el script.
// php caso_invalido.php titulo | cero | negativo
$caso = $argv[1] ?? 'titulo';
$titulo = $caso === 'titulo' ? '   ' : 'Atlas';
$paginas = $caso === 'cero' ? 0 : ($caso === 'negativo' ? -5 : 120);

echo "Caso: $caso\n";

$libro = new Libro($titulo, $paginas, new Categoria('Entorno local'));

echo "No debe aparecer: new ha devuelto un objeto\n";
