<?php
declare(strict_types=1);
require __DIR__ . '/clases.php';
// Ejecutar por separado. Se espera Error, no una salida correcta.
$borrador = new TallerBorrador();
echo $borrador->nombre;
