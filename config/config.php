<?php

/**
 * Configuración de rutas relativas para el proyecto MVC
 */

// Obtener la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));

// Definir las rutas principales del proyecto
define('CONTROLLERS_PATH', BASE_PATH . '/controllers/');
define('MODELS_PATH', BASE_PATH . '/models/');
define('VIEWS_PATH', BASE_PATH . '/views/');
define('CSS_PATH', BASE_PATH . '/css/');
define('JS_PATH', BASE_PATH . '/jquery/');
define('IMG_PATH', BASE_PATH . '/img/');

// URLs para el frontend
define('CSS_URL', BASE_URL . '/css/');
define('JS_URL', BASE_URL . '/jquery/');
define('IMG_URL', BASE_URL . '/img/');
define('ROOT_URL', BASE_URL . '/');

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
