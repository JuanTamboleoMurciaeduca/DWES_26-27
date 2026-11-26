<?php

declare(strict_types=1);

namespace App\Controlador;

use App\Model\Usuario;

abstract class Controlador
{
    public function __construct(protected array $app) {}

    protected function vista(string $nombre, array $datos = []): void
    {
        extract($datos, EXTR_SKIP);
        $usuarioActual = $this->app['usuarioActual'];
        $aviso = $_SERVER['REQUEST_METHOD'] === 'GET' ? \App\Soporte\flash() : null;
        $tema = ($_COOKIE['tema'] ?? null) === 'oscuro' ? 'oscuro' : 'claro';
        $densidad = ($_COOKIE['densidad'] ?? null) === 'compacta' ? 'compacta' : 'normal';
        require dirname(__DIR__, 2) . '/vistas/cabecera.php';
        require dirname(__DIR__, 2) . '/vistas/' . $nombre . '.php';
        require dirname(__DIR__, 2) . '/vistas/pie.php';
    }

    protected function exigirUsuario(): Usuario
    {
        $usuario = $this->app['usuarioActual'];
        if ($usuario === null) {
            header('Location: login.php', true, 302);
        }
        return $usuario;
    }
}
