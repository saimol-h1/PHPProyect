<?php

/**
 * Configuración de rutas relativas para el proyecto MVC
 */

// Obtener la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__));

// Detectar si estamos en Railway o entorno de producción
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
    // En Railway, usar ruta raíz
    define('BASE_URL', '');
    define('ROOT_URL', '/');
} else {
    // Desarrollo local
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    define('BASE_URL', $scriptDir);
    define('ROOT_URL', $scriptDir . '/');
}

// Definir las rutas principales del proyecto
define('CONTROLLERS_PATH', BASE_PATH . '/controllers/');
define('MODELS_PATH', BASE_PATH . '/models/');
define('VIEWS_PATH', BASE_PATH . '/views/');
define('CSS_PATH', BASE_PATH . '/css/');
define('JS_PATH', BASE_PATH . '/jquery/');
define('IMG_PATH', BASE_PATH . '/img/');

// URLs para el frontend
define('CSS_URL', ROOT_URL . 'css/');
define('JS_URL', ROOT_URL . 'jquery/');
define('IMG_URL', ROOT_URL . 'img/');

/**
 * Función para generar rutas relativas seguras
 */
function getPath($type, $file = '')
{
    switch ($type) {
        case 'controller':
            return CONTROLLERS_PATH . $file;
        case 'model':
            return MODELS_PATH . $file;
        case 'view':
            return VIEWS_PATH . $file;
        case 'css':
            return CSS_PATH . $file;
        case 'js':
            return JS_PATH . $file;
        case 'img':
            return IMG_PATH . $file;
        default:
            return BASE_PATH . '/' . $file;
    }
}

/**
 * Función para generar URLs para el frontend
 */
function getUrl($type, $file = '')
{
    switch ($type) {
        case 'css':
            return CSS_URL . $file;
        case 'js':
            return JS_URL . $file;
        case 'img':
            return IMG_URL . $file;
        case 'model':
            return ROOT_URL . 'models/' . $file;
        case 'root':
            // Si el archivo ya contiene index.php, usar tal como está
            if (strpos($file, 'index.php') !== false) {
                return ROOT_URL . $file;
            }
            return ROOT_URL . $file;
        default:
            return ROOT_URL . $file;
    }
}

/**
 * Función para incluir archivos de forma segura
 */
function includeFile($type, $file)
{
    $path = getPath($type, $file);
    if (file_exists($path)) {
        include $path;
    } else {
        echo "Error: No se encontró el archivo $path";
    }
}

/**
 * Función para requerir archivos de forma segura
 */
function requireFile($type, $file)
{
    $path = getPath($type, $file);
    if (file_exists($path)) {
        require_once $path;
    } else {
        die("Error: No se encontró el archivo requerido $path");
    }
}

/**
 * Función específica para generar URLs de acciones del MVC
 */
function getActionUrl($action = '')
{
    if (empty($action)) {
        return ROOT_URL . 'index.php';
    }
    return ROOT_URL . 'index.php?action=' . $action;
}
