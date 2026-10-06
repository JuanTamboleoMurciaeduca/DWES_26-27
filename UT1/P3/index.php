<?php

declare(strict_types=1);

const LIMITE_LEVE = 5;
const PENALIZACION_RETRASO = 0.5;

$tipo = strtolower(trim((string) ($_GET['tipo'] ?? 'externo')));
$tipo = in_array($tipo, ['alumno', 'profesor', 'externo'], true) ? $tipo : 'externo';

$dias = max(0, (int) ($_GET['dias'] ?? 0));

$renovacion = strtolower(trim((string) ($_GET['renovacion'] ?? 'no')));
$renovacion = in_array($renovacion, ['si', 'no'], true) ? $renovacion : 'no';

$maximoDias = match ($tipo) {
    'alumno' => 15,
    'profesor' => 30,
    default => 7,
};

if ($renovacion === 'si' && $tipo !== 'externo')
    $maximoDias += 7;

$retraso = $dias - $maximoDias;

if ($retraso < 0) {
    $situacion = 'correcta';
} elseif ($retraso === 0) {
    $situacion = 'último día';
} elseif ($retraso <= LIMITE_LEVE) {
    $situacion = 'retraso leve';
} else {
    $situacion = 'retraso grave';
}

$penalizacion = $retraso > 0 ? $retraso * PENALIZACION_RETRASO : 0;

$salida = 'Usuario de tipo ' . $tipo . ' tarda ' . $dias . ' días ' . 'por lo tanto es ' . $situacion . ' y debe pagar ' . $penalizacion . '€';
$salida = htmlspecialchars($salida, ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P3</title>
</head>

<body>
    <h1>Salida del programa:</h1>
    <p><?= $salida ?></p>
    <?php
    for ($i = 1; $i <= $retraso && $i <= 10; $i++) {
        echo "Retraso día $i<br>";
    }

    if ($retraso > 10) {
        echo '...';
    }
    ?>
</body>

</html>