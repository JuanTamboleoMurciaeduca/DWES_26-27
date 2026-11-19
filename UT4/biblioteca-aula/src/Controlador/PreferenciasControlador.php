<?php

declare(strict_types=1);

namespace App\Controlador;

use function App\Soporte\redirigir;
use function App\Soporte\soloMetodos;

final class PreferenciasControlador extends Controlador
{
    public function guardar(): void
    {
        soloMetodos(['POST']);
        $tema = $_POST['tema'] ?? null;
        $densidad = $_POST['densidad'] ?? null;
        if (in_array($tema, ['claro', 'oscuro'], true)) {
            $this->guardarCookie('tema', $tema);
            redirigir('index.php');
        }
        if (in_array($densidad, ['normal', 'compacta'], true)) {
            $this->guardarCookie('densidad', $densidad);
            redirigir('index.php');
        }
        http_response_code(400);
        exit('Preferencia no válida');
    }


    private function guardarCookie(
        string $nombre,
        string $valor,
        ?int $caduca = null
    ): void {
        setcookie($nombre, $valor, [
            'expires' => $caduca ?? time() + 30 * 86400,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
