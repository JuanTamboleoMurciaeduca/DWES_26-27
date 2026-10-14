<?php
declare(strict_types=1);

require_once __DIR__ . '/Autor.php';
require_once __DIR__ . '/Categoria.php';
require_once __DIR__ . '/Libro.php';

// Fase 2.1: crea aquí un Autor y una Categoria y muestra sus nombres.
$autor = new Autor('Frank Herbert');
$categoria = new Categoria('Ciencia ficción');
echo $autor->nombre . ' ' . $categoria->nombre;

// Fase 2.2: cuando completes Libro.php, añade su require_once arriba.
// Después crea los dos libros y muestra su disponibilidad.
// $l1 = new Libro(10, 'Dune', 412, $autor, $categoria);
// $l2 = new Libro(10, 'Dune', 412, $autor, $categoria, false);
// print_r($l1);
// print_r($l2);

// Fase 3.2: sustituye las pruebas anteriores por un Libro construido
// con las variables $titulo y $paginas. Muestra ambas propiedades.
$titulo = '    ';
$paginas =  0;
$l1 = new Libro(10, $titulo, $paginas, $autor, $categoria);
print_r($l1);