<?php

use function App\Soporte\e; ?>
<h2>Catálogo</h2>

<form method="post" action="preferencias.php">
    <label for="tema">Tema</label>
    <select id="tema" name="tema">
        <option value="claro">Claro</option>
        <option value="oscuro" <?= $tema === 'oscuro' ? 'selected' : '' ?>>Oscuro</option>
    </select>
    <button>Guardar tema</button>
</form>

<form method="post" action="preferencias.php">
    <label for="densidad">Espaciado del catálogo</label>
    <select id="densidad" name="densidad">
        <option value="normal">Normal</option>
        <option value="compacta"
            <?= $densidad === 'compacta' ? 'selected' : '' ?>>Compacto</option>
    </select>
    <button>Guardar espaciado</button>
</form>

<form method="get" action="index.php">
    <label for="q">Buscar por título</label>
    <input id="q" name="q" value="<?= e($q) ?>">
    <button>Buscar</button>
    <a href="index.php">Ver todos</a>
</form>
<?php if ($libros === []): ?>
    <p>Sin resultados.</p>
<?php else: ?>
    <ul class="libros">
        <?php foreach ($libros as $libro): ?>
            <li><?= e($libro->titulo) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>