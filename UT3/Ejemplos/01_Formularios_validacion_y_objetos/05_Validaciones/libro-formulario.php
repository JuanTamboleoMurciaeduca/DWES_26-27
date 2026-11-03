<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Validación de datos</title></head>
<body>
    <h1>Validación de libros con POST</h1>
    <form method="post" action="libro-crear.php">
        <label for="titulo">Título</label>
        <input id="titulo" name="titulo" type="text"><br>

        <label for="paginas">Páginas</label>
        <input id="paginas" name="paginas" type="text"><br>

        <label for="genero">Género</label>
        <select id="genero" name="genero">
            <option value="ciencia">Ciencia</option>
            <option value="fantasia">Fantasía</option>
            <option value="terror">Terror</option>
        </select><br>

        <label>
            <input name="destacado" type="checkbox" value="1">
            Libro destacado
        </label><br>
        <button type="submit">Validar</button>
    </form>
</body>
</html>
