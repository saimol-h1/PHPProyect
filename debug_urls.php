<?php
require_once 'config/config.php';

echo "<h2>🔍 Debug de URLs</h2>";

echo "<h3>Testing getUrl() function:</h3>";
echo "<p><strong>getUrl('model', 'login.php'):</strong> " . getUrl('model', 'login.php') . "</p>";
echo "<p><strong>getUrl('root', 'models/login.php'):</strong> " . getUrl('root', 'models/login.php') . "</p>";
echo "<p><strong>Directo 'models/login.php':</strong> models/login.php</p>";

echo "<h3>Constantes definidas:</h3>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>ROOT_URL:</strong> " . ROOT_URL . "</p>";

echo "<h3>Formularios de test:</h3>";
?>

<div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
    <h4>Formulario 1: Usando getUrl('model', 'login.php')</h4>
    <form action="<?php echo getUrl('model', 'login.php'); ?>" method="POST">
        <input type="hidden" name="usuario" value="admin">
        <input type="hidden" name="password" value="admin123">
        <button type="submit">Test con getUrl()</button>
    </form>
    <p><em>Action: <?php echo getUrl('model', 'login.php'); ?></em></p>
</div>

<div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
    <h4>Formulario 2: Usando ruta directa</h4>
    <form action="models/login.php" method="POST">
        <input type="hidden" name="usuario" value="admin">
        <input type="hidden" name="password" value="admin123">
        <button type="submit">Test con ruta directa</button>
    </form>
    <p><em>Action: models/login.php</em></p>
</div>

<div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
    <h4>Enlaces de verificación:</h4>
    <ul>
        <li><a href="<?php echo getUrl('model', 'login.php'); ?>">Verificar URL generada por getUrl()</a></li>
        <li><a href="models/login.php">Verificar ruta directa</a></li>
        <li><a href="index.php?action=login">Login en template</a></li>
    </ul>
</div>