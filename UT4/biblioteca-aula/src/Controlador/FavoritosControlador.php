<?php

declare(strict_types=1);

namespace App\Controlador;

use function App\Soporte\redirigir;
use function App\Soporte\soloMetodos;

final class FavoritosControlador extends Controlador
{
    public function cambiar(): void
    {
        soloMetodos($_POST);

        $entrada = $_POST['id'] ?? null;
        // Validar la entrada
        $id = is_string($entrada);
        if ($id === false) {
            http_response_code(400);
            exit;
        }

        if ($this->app['catalogo']->buscarPorId($id) === null) {
            http_response_code(404);
            exit;
        }

        $favoritos = $_SESSION['favoritos'] ?? [];
        if (!in_array($id, $favoritos, true)) {
            $favoritos[] = $id;
        }

        $_SESSION['favoritos'] = $favoritos;
        redirigir('index.php');
    }
}
