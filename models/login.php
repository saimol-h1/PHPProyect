<?php
// Iniciar sesión

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
    $sql = "SELECT id, usuario, password, tipo_usuario, nombre_completo, estado, intentos_fallidos FROM usuarios WHERE usuario = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verificar si la cuenta está bloqueada
        if ($row['estado'] == 'inactivo') {
            header('Location: ../index.php?action=login&error=account_blocked');
            exit();
        } else {
            // Verificar contraseña (usando MD5 como está en la BD)
            if (md5($password) === $row['password']) {
                // Login exitoso - resetear intentos fallidos
                $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = 0, ultimo_intento_fallido = NULL WHERE id = ?");
                $stmt->bind_param("i", $row['id']);
                $stmt->execute();

                // Crear sesión
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nombre'] = $row['usuario'];
                $_SESSION['usuario_tipo'] = $row['tipo_usuario'];
                $_SESSION['nombre_completo'] = $row['nombre_completo'];
                $_SESSION['login_time'] = time();

                // Redirigir según el tipo de usuario
                if ($row['tipo_usuario'] === 'administrador') {
                    header('Location: ../index.php?action=servicios&login=success');
                } else {
                    header('Location: ../index.php?action=servicios&login=success');
                }
                exit();
            } else {
                // Contraseña incorrecta - incrementar intentos fallidos
                $nuevos_intentos = $row['intentos_fallidos'] + 1;

                if ($nuevos_intentos >= 3) {
                    // Bloquear cuenta
                    $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = ?, ultimo_intento_fallido = NOW(), estado = 'inactivo' WHERE id = ?");
                    $stmt->bind_param("ii", $nuevos_intentos, $row['id']);
                    $stmt->execute();
                    header('Location: ../index.php?action=login&error=account_locked&user=' . urlencode($usuario));
                    exit();
                } else {
                    // Actualizar intentos fallidos
                    $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = ?, ultimo_intento_fallido = NOW() WHERE id = ?");
                    $stmt->bind_param("ii", $nuevos_intentos, $row['id']);
                    $stmt->execute();
                    $intentos_restantes = 3 - $nuevos_intentos;
                    header('Location: ../index.php?action=login&error=wrong_password&attempts=' . $intentos_restantes);
                    exit();
                }
            }
        }
    } else {
        header('Location: ../index.php?action=login&error=user_not_found');
        exit();
    }
} else {
    // Método no permitido
    header('Location: ../index.php');
    exit();
}

mysqli_close($conn);
?>
