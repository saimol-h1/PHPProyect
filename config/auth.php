<?php

/**
 * Manejo de Sesiones y Autenticación
 */

// Configurar directorio de sesiones personalizado
$tempDir = __DIR__ . '/../tmp';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}
ini_set('session.save_path', $tempDir);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verificar si el usuario está logueado
 */
function isLoggedIn()
{
    return isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_tipo']);
}

/**
 * Verificar si el usuario es administrador
 */
function isAdmin()
{
    return isLoggedIn() && $_SESSION['usuario_tipo'] === 'administrador';
}

/**
 * Verificar si el usuario es secretaria
 */
function isSecretaria()
{
    return isLoggedIn() && $_SESSION['usuario_tipo'] === 'secretaria';
}

/**
 * Obtener información del usuario logueado
 */
function getUsuarioInfo()
{
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'usuario' => $_SESSION['usuario_nombre'],
            'tipo' => $_SESSION['usuario_tipo'],
            'nombre_completo' => $_SESSION['nombre_completo']
        ];
    }
    return null;
}

/**
 * Redirigir a login si no está autenticado
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit();
    }
}

/**
 * Redirigir si no es administrador
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php?action=unauthorized');
        exit();
    }
}

/**
 * Cerrar sesión
 */
function logout()
{
    session_start();
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

/**
 * Mostrar información del usuario logueado (para incluir en templates)
 */
function mostrarUsuarioLogueado()
{
    if (isLoggedIn()) {
        $info = getUsuarioInfo();
        $tipoCapitalizado = ucfirst($info['tipo']);
        echo "<div class='usuario-logueado alert alert-info mb-0'>";
        echo "<i class='fas fa-user'></i> Bienvenido {$tipoCapitalizado}: <strong>{$info['nombre_completo']}</strong>";
        echo " <a href='logout.php' class='btn btn-sm btn-outline-danger ms-2'>Cerrar Sesión</a>";
        echo "</div>";
    }
}
