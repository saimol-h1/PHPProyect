<?php

/**
 * Verificador de BOM y espacios en blanco en archivos PHP
 */

echo "<h1>🔍 Verificador de Headers y BOM</h1>";

$files_to_check = [
    'index.php',
    'config/config.php',
    'config/auth.php',
    'config/database.php',
    'controllers/controller.php',
    'models/model.php',
    'views/template.php'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Archivo</th><th>Estado</th><th>Primeros 10 bytes</th></tr>";

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $first_bytes = bin2hex(substr($content, 0, 10));

        $status = "✅ OK";

        // Verificar BOM UTF-8
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $status = "❌ BOM detectado";
        }

        // Verificar espacios antes de <?php
        if (!preg_match('/^<\?php/', $content) && trim($content) !== '') {
            $status = "❌ Espacios antes de <?php";
        }

        echo "<tr>";
        echo "<td>$file</td>";
        echo "<td>$status</td>";
        echo "<td>$first_bytes</td>";
        echo "</tr>";
    } else {
        echo "<tr><td>$file</td><td>❌ No existe</td><td>-</td></tr>";
    }
}

echo "</table>";

echo "<h2>📊 Estado del Sistema:</h2>";
echo "<p><strong>PHP Output Buffering:</strong> " . (ob_get_level() ? 'Activo' : 'Inactivo') . "</p>";
echo "<p><strong>Headers enviados:</strong> " . (headers_sent() ? 'SÍ' : 'NO') . "</p>";

if (headers_sent($file, $line)) {
    echo "<p><strong>Headers enviados desde:</strong> $file línea $line</p>";
}
