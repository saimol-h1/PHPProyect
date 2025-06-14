<?php

/**
 * Configuración de Base de Datos Híbrida
 * Funciona en desarrollo local, Railway y hosting gratuito
 */

// Detectar entorno de Railway
if (isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_ENV['MYSQL_HOST']) || isset($_SERVER['MYSQL_HOST'])) {
    // Configuración para Railway usando variables individuales
    $host = $_ENV['MYSQL_HOST'] ?? $_SERVER['MYSQL_HOST'];
    $username = $_ENV['MYSQL_USER'] ?? $_SERVER['MYSQL_USER'];
    $password = $_ENV['MYSQL_PASSWORD'] ?? $_SERVER['MYSQL_PASSWORD'];
    $database = $_ENV['MYSQL_DATABASE'] ?? $_SERVER['MYSQL_DATABASE'];
    $port = $_ENV['MYSQL_PORT'] ?? $_SERVER['MYSQL_PORT'] ?? 3306;
    $environment = 'railway';
} elseif (isset($_ENV['MYSQL_URL']) || isset($_SERVER['MYSQL_URL'])) {
    // Fallback: Railway con MYSQL_URL
    $mysql_url = $_ENV['MYSQL_URL'] ?? $_SERVER['MYSQL_URL'];
    $url_parts = parse_url($mysql_url);

    $host = $url_parts['host'];
    $port = $url_parts['port'] ?? 3306;
    $database = ltrim($url_parts['path'], '/');
    $username = $url_parts['user'];
    $password = $url_parts['pass'];
    $environment = 'railway';
} else {
    // Detectar si es desarrollo local
    $isLocal = (
        $_SERVER['HTTP_HOST'] == 'localhost' ||
        strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
        strpos($_SERVER['HTTP_HOST'], '::1') !== false ||
        $_SERVER['SERVER_NAME'] == 'localhost' ||
        strpos($_SERVER['HTTP_HOST'], '.local') !== false ||
        strpos($_SERVER['HTTP_HOST'], 'xampp') !== false
    );

    if ($isLocal) {
        // Configuración local (XAMPP/WAMP/MAMP)
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "practicamvc";
        $port = 3306;
        $environment = 'development';
    } else {
        // Configuración para hosting gratuito (InfinityFree, etc.)
        $host = "sql123.infinityfree.com";
        $username = "if0_12345678";
        $password = "tu_password_hosting";
        $database = "if0_12345678_cuarto";
        $port = 3306;
        $environment = 'production';
    }
}

try {
    // Crear conexión
    $conn = new mysqli($host, $username, $password, $database, $port);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Configurar charset
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Log del error
    error_log("Error de base de datos: " . $e->getMessage());

    // Mostrar mensaje según el entorno
    if ($environment == 'development') {
        echo "<div style='background: #ffebee; padding: 20px; border: 1px solid #f44336; margin: 20px;'>";
        echo "<h3>❌ Error de Conexión a Base de Datos</h3>";
        echo "<strong>Host:</strong> " . $host . "<br>";
        echo "<strong>Usuario:</strong> " . $username . "<br>";
        echo "<strong>Base de datos:</strong> " . $database . "<br>";
        echo "<strong>Error:</strong> " . $e->getMessage() . "<br>";
        echo "<strong>Entorno:</strong> " . $environment . "<br>";
        echo "</div>";
        die();
    } elseif ($environment == 'railway') {
        die("Error de conexión a la base de datos Railway. Contacte al administrador.");
    } else {
        die("Error de conexión al hosting. Verificar configuración.");
    }
}

// Definir constantes para compatibilidad
define('DB_HOST', $host);
define('DB_USER', $username);
define('DB_PASS', $password);
define('DB_NAME', $database);
define('DB_PORT', $port);
define('ENVIRONMENT', $environment);
