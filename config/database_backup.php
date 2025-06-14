<?php

/**
 * Configuración de Base de Datos para Producción
 * Crear este archivo cuando despliegues a producción
 */

// Configuración para desarrollo (local) - Mejorada
$isLocal = (
    $_SERVER['HTTP_HOST'] == 'localhost' ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
    strpos($_SERVER['HTTP_HOST'], '::1') !== false ||
    $_SERVER['SERVER_NAME'] == 'localhost' ||
    strpos($_SERVER['HTTP_HOST'], '.local') !== false ||
    strpos($_SERVER['HTTP_HOST'], 'xampp') !== false
);

if ($isLocal) {
    // Configuración local
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'cuarto');
    define('ENVIRONMENT', 'development');
} else {
    // Configuración de producción - ACTUALIZAR CON TUS DATOS REALES
    define('DB_HOST', 'sql123.infinityfree.com'); // Cambiar por tu host
    define('DB_USER', 'if0_12345678'); // Cambiar por tu usuario
    define('DB_PASS', 'tu_password_seguro'); // Cambiar por tu password
    define('DB_NAME', 'if0_12345678_cuarto'); // Cambiar por tu base de datos
    define('ENVIRONMENT', 'production');
}

// Configuración de errores según el entorno
if (ENVIRONMENT == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Conexión a la base de datos con mejor manejo de errores
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    if (ENVIRONMENT == 'development') {
        // En desarrollo mostramos información detallada
        echo "<div style='background: #ffebee; padding: 20px; border: 1px solid #f44336; margin: 20px;'>";
        echo "<h3>❌ Error de Conexión a Base de Datos</h3>";
        echo "<strong>Host:</strong> " . DB_HOST . "<br>";
        echo "<strong>Usuario:</strong> " . DB_USER . "<br>";
        echo "<strong>Base de datos:</strong> " . DB_NAME . "<br>";
        echo "<strong>Error MySQL:</strong> " . mysqli_connect_error() . "<br>";
        echo "<strong>Entorno:</strong> " . ENVIRONMENT . "<br>";
        echo "<strong>URL actual:</strong> " . $_SERVER['HTTP_HOST'] . "<br>";
        echo "<hr>";
        echo "<h4>💡 Posibles soluciones:</h4>";
        echo "<ul>";
        echo "<li>Verifica que XAMPP esté ejecutándose</li>";
        echo "<li>Verifica que el servicio MySQL esté activo</li>";
        echo "<li>Verifica que la base de datos 'cuarto' exista</li>";
        echo "<li>Ejecuta el script SQL en phpMyAdmin</li>";
        echo "</ul>";
        echo "<a href='debug.php' style='padding: 10px; background: #2196f3; color: white; text-decoration: none;'>🔍 Ver Debug Completo</a>";
        echo "</div>";
        die();
    } else {
        die("Error de conexión a la base de datos. Contacte al administrador.");
    }
}

// Establecer charset UTF-8
mysqli_set_charset($conn, "utf8");
