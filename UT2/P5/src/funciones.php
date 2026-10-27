<?php

declare(strict_types=1);

function entrada(array $origen, string $clave, string $defecto = ''): string
{
    // TODO F1. Lee solo cadenas y aplica el valor por defecto.
    return isset($origen[$clave]) && is_string($origen[$clave])
        ? $origen[$clave] : $defecto;
}

function enteroPositivo(string $valor): int
{
    // TODO F2. Valida antes de convertir; rechaza con InvalidArgumentException.
    $numero = filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($numero === false) {
        throw new InvalidArgumentException('Indica un entero positivo.');
    }
    return $numero;
}

function limpiarEspacios(string $texto): string
{
    return preg_replace('/\s+/u', ' ', trim($texto)) ?? '';
}

function normalizar(string $texto): string
{
    return mb_strtolower(limpiarEspacios($texto), 'UTF-8');
}

function buscarPorId(array $actividades, int $id): ?Actividad
{
    // TODO F3. Usa array_find con un callback tipado.
    return array_find(
        $actividades,
        fn(Actividad $a): bool => $a->id() === $id
    );
}

function filtrarActividades(
    array $actividades,
    string $texto,
    string $tipo,
    ?EstadoActividad $estado,
    bool $soloDisponibles
): array {
    // TODO F4. Combina las cuatro condiciones mediante array_filter.
    $busqueda = normalizar($texto);
    return array_values(array_filter(
        $actividades,
        fn(Actividad $a): bool =>
        str_contains(normalizar($a->nombre()), $busqueda)
            && ($tipo === '' || $a->tipo() === $tipo)
            && ($estado === null || $a->estado() === $estado)
            && (!$soloDisponibles || ($a->estado() === EstadoActividad::Abierta
                && $a->plazasLibres() > 0))
    ));
}

function ordenarActividades(array $actividades, string $orden): array
{
    // TODO F5. Ordena una copia con usort y desempata por id.
    usort($actividades, function (Actividad $a, Actividad $b) use ($orden): int {
        $comparacion = $orden === 'libres'
            ? $a->plazasLibres() <=> $b->plazasLibres()
            : normalizar($a->nombre()) <=> normalizar($b->nombre());
        return $comparacion !== 0 ? $comparacion : $a->id() <=> $b->id();
    });
    return $actividades;
}

function transformarActividades(array $actividades, callable $transformacion): array
{
    return array_map($transformacion, $actividades);
}

function generarEtiquetas(
    array $actividades,
    string $prefijo = 'Actividad: '
): array {
    // TODO F6. Usa transformarActividades y una closure con use.
    return transformarActividades(
        $actividades,
        function (Actividad $a) use ($prefijo): string {
            return $a->etiqueta($prefijo);
        }
    );
}

function resumirActividades(array $actividades): array
{
    // TODO F7. Calcula las cinco magnitudes con array_reduce.
    return array_reduce($actividades, function (array $r, Actividad $a): array {
        $r['cantidad']++;
        $r['capacidad'] += $a->capacidad();
        $r['ocupadas'] += $a->ocupadas();
        $r['libres'] += $a->plazasLibres();
        $r['importe'] += $a->importe($a->ocupadas());
        return $r;
    }, [
        'cantidad' => 0,
        'capacidad' => 0,
        'ocupadas' => 0,
        'libres' => 0,
        'importe' => 0
    ]);
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function euros(int $centimos): string
{
    return number_format($centimos / 100, 2, ',', '.') . ' €';
}
