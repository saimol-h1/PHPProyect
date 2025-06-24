<?php
// Incluir configuración de rutas
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    if (empty($usuario) || empty($password)) {
        header('Location: ../index.php?action=login&error=empty');
        exit();
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
            $_SESSION['login_time'] = time();            // Redirigir según el tipo de usuario
            if ($row['tipo_usuario'] === 'administrador') {
                header('Location: ../index.php?action=servicios&login=success');
            } else {
                header('Location: ../index.php?action=servicios&login=success');
            }
            exit();
        } else {
            // Contraseña incorrecta
            header('Location: ../index.php?action=login&error=invalid');
            exit();
        }
    } else {
        // Usuario no encontrado
        header('Location: ../index.php?action=login&error=invalid');
        exit();
    }
} else {
    // Método no permitido
    header('Location: ../index.php');
    exit();
}

mysqli_close($conn);
