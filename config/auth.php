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
            'tipo_usuario' => $_SESSION['usuario_tipo'], // Corregido para consistencia
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
    // Iniciar sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();

    // Limpiar buffer si existe antes de redirigir
    if (ob_get_level()) {
        ob_clean();
    }

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

/**
 * Función de login
 */
function login($usuario, $password)
{
    // Incluir conexión a base de datos
    require_once 'config/database.php';

    if (empty($usuario) || empty($password)) {
        return false;
    }

    // Buscar usuario en la base de datos
    $sql = "SELECT id, usuario, password, tipo_usuario, nombre_completo, estado FROM usuarios WHERE usuario = ? AND estado = 'activo'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verificar contraseña (usando MD5 como está en la BD)
        if (md5($password) === $row['password']) {
            // Login exitoso - crear sesión
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_nombre'] = $row['usuario'];
            $_SESSION['usuario_tipo'] = $row['tipo_usuario'];
            $_SESSION['nombre_completo'] = $row['nombre_completo'];
            $_SESSION['login_time'] = time();

            return true;
        }
    }

    return false;
}
