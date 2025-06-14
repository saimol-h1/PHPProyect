<?php
// Incluir configuración si no está incluida
if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

// Incluir configuración de base de datos
require_once dirname(__DIR__) . '/config/database.php';

// La conexión $conn ya está disponible desde database.php
