<?php

/**
 * Configuración de Base de Datos para Railway
 * Utiliza variables de entorno para la conexión
 */

// Configuración para Railway (usa variables de entorno)
if (isset($_ENV['MYSQL_HOST']) || isset($_SERVER['MYSQL_HOST'])) {
    // Railway proporciona variables individuales
    $host = $_ENV['MYSQL_HOST'] ?? $_SERVER['MYSQL_HOST'];
    $port = $_ENV['MYSQL_PORT'] ?? $_SERVER['MYSQL_PORT'] ?? 3306;
    $database = $_ENV['MYSQL_DATABASE'] ?? $_SERVER['MYSQL_DATABASE'];
    $username = $_ENV['MYSQL_USER'] ?? $_SERVER['MYSQL_USER'];
    $password = $_ENV['MYSQL_PASSWORD'] ?? $_SERVER['MYSQL_PASSWORD'];
} elseif (isset($_ENV['MYSQL_URL']) || isset($_SERVER['MYSQL_URL'])) {
    // Fallback: Railway proporciona MYSQL_URL
    $mysql_url = $_ENV['MYSQL_URL'] ?? $_SERVER['MYSQL_URL'];
    $url_parts = parse_url($mysql_url);

    $host = $url_parts['host'];
    $port = $url_parts['port'] ?? 3306;
    $database = ltrim($url_parts['path'], '/');
    $username = $url_parts['user'];
    $password = $url_parts['pass'];
} else {
    // Configuración local (desarrollo)
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "practicamvc";
    $port = 3306;
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

    // En producción, mostrar mensaje genérico
    if (isset($_ENV['RAILWAY_ENVIRONMENT'])) {
        die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
    } else {
        die("Error de conexión: " . $e->getMessage());
    }
}
