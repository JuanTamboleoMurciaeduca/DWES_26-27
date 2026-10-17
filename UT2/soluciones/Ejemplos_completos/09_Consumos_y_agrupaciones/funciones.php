<?php
declare(strict_types=1);

function seleccionar(array $registros, string $aula, int $minimo): array
{
    /* INICIO seleccionar */
    if ($minimo < 0) { throw new InvalidArgumentException('Mínimo negativo'); }
    $aula = mb_strtolower(trim($aula), 'UTF-8');
    return array_values(array_filter($registros, function (array $r) use ($aula, $minimo): bool {
        return ($aula === '' || mb_strtolower(trim($r['aula']), 'UTF-8') === $aula)
            && $r['kwh'] >= $minimo;
    }));
    /* FIN */
}
function filasConCoste(array $registros): array
{
    /* INICIO transformar */
    return array_map(fn (array $r): array => [
        'aula' => mb_strtolower(trim($r['aula']), 'UTF-8'), 'equipo' => $r['equipo'],
        'kwh' => $r['kwh'], 'coste' => $r['kwh'] * $r['centimosKwh'],
    ], $registros);
    /* FIN */
}
function agrupar(array $filas): array
{
    /* INICIO agrupar */
    return array_reduce($filas, function (array $grupos, array $r): array {
        $aula = $r['aula'];
        $grupos[$aula] ??= ['equipos' => 0, 'kwh' => 0, 'coste' => 0];
        $grupos[$aula]['equipos']++;
        $grupos[$aula]['kwh'] += $r['kwh'];
        $grupos[$aula]['coste'] += $r['coste'];
        return $grupos;
    }, []);
    /* FIN */
}
function ordenar(array $filas): array
{
    /* INICIO ordenar */
    usort($filas, fn (array $a, array $b): int => ($b['coste'] <=> $a['coste'])
        ?: strcmp($a['equipo'], $b['equipo']));
    return $filas;
    /* FIN */
}
