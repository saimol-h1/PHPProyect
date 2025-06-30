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

// Configurar tiempo de expiración de sesión (10 minutos = 600 segundos)
define('SESSION_TIMEOUT', 600); // 10 minutos en segundos

/**
 * Verificar y manejar la expiración de sesión
 */
function checkSessionTimeout()
{
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];

        if ($inactive_time > SESSION_TIMEOUT) {
            // Sesión expirada
            session_unset();
            session_destroy();

            // Redirigir al login con mensaje de sesión expirada
            if (!headers_sent()) {
                header('Location: index.php?action=login&expired=1');
                exit();
            }
            return false;
        }
    }

    // Actualizar tiempo de última actividad
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Obtener tiempo restante de sesión en segundos
 */
function getSessionTimeRemaining()
{
    if (isset($_SESSION['last_activity'])) {
        $elapsed = time() - $_SESSION['last_activity'];
        $remaining = SESSION_TIMEOUT - $elapsed;
        return max(0, $remaining);
    }
    return 0;
}

/**
 * Verificar si el usuario está logueado
 */
function isLoggedIn()
{
    // Verificar expiración de sesión primero
    if (!checkSessionTimeout()) {
        return false;
    }

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
        $tipoCapitalizado = ucfirst($info['tipo_usuario'] ?? 'usuario');
        echo "<div class='usuario-logueado alert alert-info mb-0'>";
        echo "<i class='fas fa-user'></i> Bienvenido {$tipoCapitalizado}: <strong>{$info['nombre_completo']}</strong>";
        echo " <a href='logout.php' class='btn btn-sm btn-outline-danger ms-2'>Cerrar Sesión</a>";
        echo "</div>";
    }
}

/**
 * Mostrar información del usuario logueado con tiempo de sesión
 */
function mostrarUsuarioLogueadoConTiempo()
{
    if (isLoggedIn()) {
        $info = getUsuarioInfo();
        $tipoCapitalizado = ucfirst($info['tipo_usuario'] ?? 'usuario');
        $timeRemaining = getSessionTimeRemaining();
        $minutesRemaining = floor($timeRemaining / 60);
        $secondsRemaining = $timeRemaining % 60;

        echo "<div class='usuario-logueado alert alert-info mb-0 d-flex justify-content-between align-items-center'>";
        echo "<div>";
        echo "<i class='fas fa-user'></i> Bienvenido {$tipoCapitalizado}: <strong>{$info['nombre_completo']}</strong>";
        echo "</div>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='badge bg-warning text-dark me-2' id='session-timer'>";
        echo "<i class='fas fa-clock'></i> {$minutesRemaining}:" . sprintf('%02d', $secondsRemaining);
        echo "</span>";
        echo "<a href='logout.php' class='btn btn-sm btn-outline-danger'>Cerrar Sesión</a>";
        echo "</div>";
        echo "</div>";

        // JavaScript para actualizar el contador en tiempo real
        echo "<script>
        let sessionTimeRemaining = {$timeRemaining};
        const sessionTimer = document.getElementById('session-timer');
        
        function updateSessionTimer() {
            if (sessionTimeRemaining <= 0) {
                alert('Su sesión ha expirado. Será redirigido al login.');
                window.location.href = 'index.php?action=login&expired=1';
                return;
            }
            
            const minutes = Math.floor(sessionTimeRemaining / 60);
            const seconds = sessionTimeRemaining % 60;
            sessionTimer.innerHTML = '<i class=\"fas fa-clock\"></i> ' + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            
            // Cambiar color cuando quedan menos de 2 minutos
            if (sessionTimeRemaining <= 120) {
                sessionTimer.className = 'badge bg-danger text-white me-2';
            } else if (sessionTimeRemaining <= 300) {
                sessionTimer.className = 'badge bg-warning text-dark me-2';
            }
            
            sessionTimeRemaining--;
        }
        
        // Actualizar cada segundo
        setInterval(updateSessionTimer, 1000);
        </script>";
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
            $_SESSION['last_activity'] = time(); // Inicializar tiempo de última actividad

            return true;
        }
    }

    return false;
}

/**
 * Extender sesión (llamar desde AJAX para mantener sesión activa)
 */
function extendSession()
{
    if (isLoggedIn()) {
        $_SESSION['last_activity'] = time();
        return true;
    }
    return false;
}
