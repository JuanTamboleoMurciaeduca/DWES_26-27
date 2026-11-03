<?php
declare(strict_types=1);

// Funciones compartidas por los cinco ejemplos. No leen $_POST ni $_FILES.
const MAX_BYTES = 2 * 1024 * 1024;
const TIPOS = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
const DIRECTORIO = __DIR__ . '/../storage/portadas';

function nombreValido(mixed $nombre): bool
{
    return is_string($nombre)
        && preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $nombre) === 1;
}

// Diap. 9-10: recepción fallida y archivo de formato no permitido son errores distintos.
function mensajeRecepcion(int $codigo): ?string
{
    return match ($codigo) {
        UPLOAD_ERR_OK => null,
        UPLOAD_ERR_INI_SIZE => 'El archivo supera upload_max_filesize de PHP.',
        UPLOAD_ERR_FORM_SIZE => 'El archivo supera MAX_FILE_SIZE del formulario.',
        UPLOAD_ERR_PARTIAL => 'Solo llegó una parte del archivo. Vuelve a enviarlo.',
        UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo.',
        UPLOAD_ERR_NO_TMP_DIR => 'El servidor no dispone de directorio temporal.',
        UPLOAD_ERR_CANT_WRITE => 'El servidor no pudo escribir el temporal.',
        UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la recepción.',
        default => 'Código de recepción desconocido.',
    };
}

// Diap. 6: portada[] también es un array, pero sus propiedades NO son escalares.
function estructuraValida(mixed $archivo): bool
{
    return is_array($archivo)
        && isset($archivo['error'], $archivo['size'], $archivo['tmp_name'])
        && is_int($archivo['error']) && is_int($archivo['size'])
        && is_string($archivo['tmp_name']);
}

// Diap. 6-17: validar NO mueve el archivo. El controlador decide cuándo guardarlo.
// Resultado uniforme: error, archivo validado o null, MIME y extensión calculados.
function validarArchivo(mixed $archivo, bool $obligatorio = false): array
{
    $resultado = ['error' => null, 'archivo' => null, 'mime' => null, 'extension' => null];
    if ($archivo === null) {
        $resultado['error'] = $obligatorio ? 'Selecciona una portada.' : null;
        return $resultado;
    }
    if (!estructuraValida($archivo)) {
        $resultado['error'] = 'Entrada no válida: se esperaba un único archivo.';
        return $resultado;
    }
    if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
        $resultado['error'] = $obligatorio ? 'Selecciona una portada.' : null;
        return $resultado;
    }
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        $resultado['error'] = mensajeRecepcion($archivo['error']);
        return $resultado;
    }
    if ($archivo['size'] <= 0 || $archivo['size'] > MAX_BYTES) {
        $resultado['error'] = 'La portada debe ocupar entre 1 byte y 2 MiB.';
        return $resultado;
    }
    // Defensa adicional: el origen debe ser una subida HTTP real de esta petición.
    if (!is_uploaded_file($archivo['tmp_name'])) {
        $resultado['error'] = 'El origen no es una subida HTTP válida.';
        return $resultado;
    }
    if (!class_exists(finfo::class)) {
        $resultado['error'] = 'El servidor necesita la extensión fileinfo.';
        return $resultado;
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
    if ($mime === false || !isset(TIPOS[$mime])) {
        $resultado['error'] = 'Formato no permitido: utiliza JPEG, PNG o WebP.';
        return $resultado;
    }
    $resultado['archivo'] = $archivo;
    $resultado['mime'] = $mime;
    $resultado['extension'] = TIPOS[$mime]; // Nunca la extensión de name.
    return $resultado;
}

// Diap. 18-22. Recibe únicamente un resultado correcto de validarArchivo().
function guardarArchivo(array $validado): ?string
{
    if ($validado['error'] !== null) {
        throw new LogicException('No se puede guardar un resultado con errores.');
    }
    if ($validado['archivo'] === null) {
        return null;
    }
    if (!is_dir(DIRECTORIO) || !is_writable(DIRECTORIO)) {
        throw new RuntimeException('Almacenamiento no disponible.');
    }
    // La comprobación file_exists de la diap. 20 no reserva un nombre.
    // Este bloqueo coordina los guardados de estos ejemplos mientras comprueban y mueven.
    // Todos los escritores deben respetarlo; no protege frente a procesos externos.
    $bloqueo = @fopen(__DIR__ . '/../storage/guardado.lock', 'c');
    if ($bloqueo === false) {
        throw new RuntimeException('No se pudo preparar el almacenamiento.');
    }
    try {
        if (!flock($bloqueo, LOCK_EX)) {
            throw new RuntimeException('No se pudo bloquear el almacenamiento.');
        }
        do {
            $nombre = bin2hex(random_bytes(16)) . '.' . $validado['extension'];
            $destino = DIRECTORIO . '/' . $nombre;
        } while (file_exists($destino));
        if (!@move_uploaded_file($validado['archivo']['tmp_name'], $destino)) {
            // El aviso nativo puede contener rutas internas; el usuario recibe un mensaje fijo.
            error_log('Falló move_uploaded_file al guardar una portada.');
            throw new RuntimeException('No se pudo guardar la portada.');
        }
        return $nombre;
    } finally {
        flock($bloqueo, LOCK_UN);
        fclose($bloqueo);
    }
}

// Diap. 30-32. Solo nombres generados dentro de un directorio fijo y sin enlaces.
function retirarArchivo(?string $nombre): bool
{
    if ($nombre === null) {
        return true;
    }
    if (!nombreValido($nombre)) {
        throw new LogicException('Nombre de limpieza no válido.');
    }
    $ruta = DIRECTORIO . '/' . $nombre;
    if (is_link($ruta)) {
        error_log('Se rechazó un enlace en el almacenamiento de portadas.');
        return false;
    }
    if (!file_exists($ruta)) {
        return true;
    }
    if (!@unlink($ruta)) {
        error_log('No se pudo retirar una portada.');
        return false;
    }
    return true;
}

// Diap. 33: propiedades paralelas con los mismos índices, sin arrays anidados.
// El máximo de 3 es una decisión visible de este ejemplo, no un límite de PHP.
function normalizarMultiples(mixed $grupo): array
{
    if ($grupo === null) {
        return [];
    }
    if (!is_array($grupo)) {
        throw new InvalidArgumentException('Entrada múltiple no válida.');
    }
    $claves = ['name', 'type', 'tmp_name', 'error', 'size'];
    foreach ($claves as $clave) {
        if (!isset($grupo[$clave]) || !is_array($grupo[$clave])) {
            throw new InvalidArgumentException('Se esperaba portadas[].');
        }
    }
    $indices = array_keys($grupo['error']);
    if (count($indices) > 3) {
        throw new InvalidArgumentException('Selecciona como máximo 3 portadas.');
    }
    foreach ($claves as $clave) {
        if (array_keys($grupo[$clave]) !== $indices) {
            throw new InvalidArgumentException('Los índices de los archivos no coinciden.');
        }
    }
    $archivos = [];
    foreach ($indices as $i) {
        if (!is_int($i) || $i < 0) {
            throw new InvalidArgumentException('Índice de archivo no válido.');
        }
        $archivo = [];
        foreach ($claves as $clave) {
            $archivo[$clave] = $grupo[$clave][$i];
        }
        if (!estructuraValida($archivo)
            || !is_string($archivo['name']) || !is_string($archivo['type'])) {
            throw new InvalidArgumentException('Archivo múltiple mal formado.');
        }
        if ($archivo['error'] !== UPLOAD_ERR_NO_FILE) {
            $archivos[$i] = $archivo;
        }
    }
    return $archivos;
}

// Diap. 12-13: se ejecuta antes de interpretar un POST vacío como datos opcionales.
function errorPeticion(): ?string
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return null;
    }
    $config = trim((string) ini_get('post_max_size'));
    $limite = (int) $config;
    $unidad = strtolower(substr($config, -1));
    $limite *= match ($unidad) { 'g' => 1024 ** 3, 'm' => 1024 ** 2, 'k' => 1024, default => 1 };
    $longitud = $_SERVER['CONTENT_LENGTH'] ?? null;
    if ($limite > 0 && is_string($longitud) && ctype_digit($longitud)
        && (float) $longitud > $limite) {
        return 'El cuerpo completo supera post_max_size. PHP puede vaciar $_POST y $_FILES.';
    }
    return null;
}
