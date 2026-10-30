<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de la biblioteca</title>
</head>

<body>
    <h1>Catálogo de la biblioteca</h1>
    <form action="index.php" method="get">
        <!-- Añade aquí los controles y el botón Buscar. -->
        <!-- Texto de búsqueda-->
        <p>
            <label for="q">Título o autor</label>
            <input type="text" id="q" name="q">
        </p>

        <!-- Género -->
        <p>
            <label for="genero">Género</label>

            <select id="genero" name="genero">
                <option value="">Todos</option>
                <option value="ciencia">Ciencia</option>
                <option value="fantasia">Fatasía</option>
                <option value="terror">Terror</option>
            </select>
        </p>

        <!-- Tipo de libro -->
        <p>
            <label for="tipo">Tipo de libro</label>
            <select id="tipo" name="tipo">
                <option value="">Todos</option>
                <option value="papel">Papel</option>
                <option value="digital">Digital</option>
            </select>
        </p>

        <!-- Solo destacados -->
        <p>
            <label for="destacados">Solo destacados</label>
            <input type="checkbox" value="1" id="destacados" name="destacados">
        </p>
        <button type="submit">Buscar</button>
    </form>


    <?php if ($filas === []): ?>
        <p>No hay libros</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Tipo</th>
                    <th>Páginas</th>
                    <th>Unidades</th>
                    <th>Coste de reposición</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($filas as $fila) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila['titulo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')  . "</td>";
                    echo "<td>" . htmlspecialchars($fila['autor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')  . "</td>";
                    echo "<td>" . htmlspecialchars($fila['genero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')  . "</td>";
                    echo "<td>" . htmlspecialchars($fila['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')  . "</td>";
                    echo "<td>" . $fila['paginas']  . "</td>";
                    echo "<td>" . $fila['unidades']  . "</td>";
                    echo "<td>" . $fila['costeCentimos'] / 100  . "€ </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
    <h2>Resumen</h2>
    <h3>Número de títulos</h3>
    <p><?= $resumen['titulos'] ?></p>
    <h3>Unidades</h3>
    <p><?= $resumen['unidades'] ?></p>
    <h3>Páginas</h3>
    <p><?= $resumen['paginas'] ?></p>
    <h3>Coste total</h3>
    <p><?= $resumen['costeCentimos'] / 100 ?>€</p>
</body>

</html>