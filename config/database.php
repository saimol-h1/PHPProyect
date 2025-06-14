<?php

/**
 * Configuración de Base de Datos para Producción
 * Crear este archivo cuando despliegues a producción
 */

// Configuración para desarrollo (local)
if ($_SERVER['HTTP_HOST'] == 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
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

// Conexión a la base de datos
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    if (ENVIRONMENT == 'development') {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        die("Error de conexión a la base de datos. Contacte al administrador.");
    }
}

// Establecer charset UTF-8
mysqli_set_charset($conn, "utf8");
