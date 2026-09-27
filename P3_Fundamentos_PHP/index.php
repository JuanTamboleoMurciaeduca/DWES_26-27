<?php
    // 1.Lee los tres parámetros utilizando $_GET y ??.
    // 2.Convierte dias a int.
    $tipo = $_GET['tipo'] ?? 'externo';
    if ($tipo !== 'alumno' && $tipo !== 'profesor') {
        echo "El usuario no es ni alumno ni profesor, es una persona externa.";
        $tipo = 'externo';
    }

    $dias = (int) ($_GET['dias'] ?? 0);
    if (!is_int($dias) || $dias < 0) {
        echo "<br>Los días tienen que ser un valor entero mayor o igual que 0.";
        $dias = 0;
    }

    $renovacion = $_GET['renovacion'] ?? false;
    if ($renovacion !== 'si' && $renovacion !== 'no') {
        echo "<br>Los valores de la renovacion solo pueden ser si o no.";
        $renovacion = false;
    }

    // 3.Usa match para asignar el máximo de días de préstamo: alumno 15, profesor 30, externo 7.
    $maxDias = match ($tipo) {
        'alumno' => 15,
        'profesor' => 30,
        'externo' => 7
    };

    // 4.Si renovacion es si, añade 7 días al límite excepto para usuarios externos.
    if ($renovacion === 'si' && $tipo !== 'externo') {
        $maxDias += 7;
    }

    // 5. Clasifica la situación como «correcta», «último día», «retraso leve» o «retraso grave». 
    //    Define tú los límites de retraso leve y grave y déjalos visibles como constantes.
    const RETRASO_LEVE = -7;
    // const RETRASO_GRAVE = -15;

    if (($maxDias - $dias) > 1) {
        echo "<br>Correcto";
    }

    if (($maxDias - $dias) === 1) {
        echo "<br>Último día";
    }

    if (($maxDias - $dias) <= 0 && ($maxDias - $dias) >= RETRASO_LEVE) {
        echo "<br>Retraso leve";
    }

    if (($maxDias - $dias) < RETRASO_LEVE) {
        echo "<br>Retraso grave";
    }
?>