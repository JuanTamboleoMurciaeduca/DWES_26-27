<?php
declare(strict_types=1);
require __DIR__ . '/Autor.php';
require __DIR__ . '/Categoria.php';
require __DIR__ . '/Libro.php';
require __DIR__ . '/LibroProtegido.php';

$autor = new Autor('Ana Gil');
$categoria = new Categoria('Entorno local');
$atlas = new Libro('  Atlas del barrio  ', 120, $categoria, $autor);
$rutas = new Libro('Rutas del barrio', 80, $categoria, $autor);
$anonimo = new Libro('Cuaderno anónimo', 24, $categoria); // Autor omitido: null.
$libros = [$atlas, $rutas, $anonimo];
foreach ($libros as $libro) {
    echo $libro->ficha() . PHP_EOL;
}
echo 'Acceso encadenado: ' . $atlas->autor->nombre . PHP_EOL;
echo 'Relación opcional: ' . ($anonimo->autor?->nombre ?? 'Sin autor') . PHP_EOL;
echo 'Autor compartido: ' . var_export($atlas->autor === $rutas->autor, true) . PHP_EOL;
$autor->nombre = 'Ana Gil Pérez';
echo 'Atlas tras cambio compartido: ' . $atlas->ficha() . PHP_EOL;
echo 'Rutas tras cambio compartido: ' . $rutas->ficha() . PHP_EOL;
echo 'Anónimo tras cambio compartido: ' . $anonimo->ficha() . PHP_EOL;

// Una clase como tipo de propiedad distingue Autor de Categoria.
// ?-> permite consultar una relación opcional; no valida sus datos.
echo "\nValidar al construir no protege todas las escrituras públicas\n";
$atlas->paginas = 0; // Public permite romper la regla después del constructor.
echo 'Estado público alterado: ' . $atlas->ficha() . PHP_EOL;
$atlas->paginas = 120; // Restauramos el estado para seguir trabajando.
$protegido = new LibroProtegido('Atlas del barrio', 120);
echo 'Variante con private: ' . $protegido->ficha() . PHP_EOL;
// No existe una escritura pública de paginas en LibroProtegido.
