<?php
/**
 * Configuración de Base de Datos Híbrida
 * Funciona tanto en desarrollo local como en Railway
 */

// Detectar si estamos en Railway
if (isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_ENV['MYSQL_URL']) || isset($_SERVER['MYSQL_URL'])) {
    // Configuración para Railway
    $mysql_url = $_ENV['MYSQL_URL'] ?? $_SERVER['MYSQL_URL'];
    $url_parts = parse_url($mysql_url);
    
    define('DB_HOST', $url_parts['host']);
    define('DB_USER', $url_parts['user']);
    define('DB_PASS', $url_parts['pass']);
    define('DB_NAME', ltrim($url_parts['path'], '/'));
    define('DB_PORT', $url_parts['port'] ?? 3306);
    define('ENVIRONMENT', 'production');
    
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
        // Configuración local
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'practicamvc');  // Cambiar a 'cuarto' si prefieres
        define('DB_PORT', 3306);
        define('ENVIRONMENT', 'development');
    } else {
        // Configuración de producción para otros hostings
        define('DB_HOST', 'sql123.infinityfree.com');
        define('DB_USER', 'if0_12345678');
        define('DB_PASS', 'tu_password_seguro');
        define('DB_NAME', 'if0_12345678_cuarto');
        define('DB_PORT', 3306);
        define('ENVIRONMENT', 'production');
    }
}

try {
    // Crear conexión
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Configurar charset
    $conn->set_charset("utf8mb4");
    
    // Log de conexión exitosa (solo en desarrollo)
    if (ENVIRONMENT === 'development') {
        error_log("✅ Conexión a BD exitosa: " . DB_NAME . " en " . DB_HOST);
    }
    
} catch (Exception $e) {
    // Log del error
    error_log("❌ Error de base de datos: " . $e->getMessage());
    
    // En producción, mostrar mensaje genérico
    if (ENVIRONMENT === 'production') {
        die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
    } else {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
