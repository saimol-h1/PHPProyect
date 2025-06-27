<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración híbrida
require_once '../config/database.php';
require_once '../config/auth.php';

// Verificar que el usuario esté logueado y sea admin
// Solo iniciar sesión si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para agregar usuarios']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar que todos los campos requeridos estén presentes
        $campos_requeridos = ['usuario', 'clave', 'nombre', 'email'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                echo json_encode(['success' => false, 'message' => "El campo '$campo' es requerido"]);
                exit;
            }
        }

        $usuario = mysqli_real_escape_string($conn, trim($_POST['usuario']));
        $clave = trim($_POST['clave']); // No escapar antes de encriptar
        $nombres = mysqli_real_escape_string($conn, trim($_POST['nombre']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $tipo_usuario = 'secretaria'; // Asignar tipo de usuario fijo
        $estado = 'activo'; // Asignar estado activo por defecto

        $errors = []; // Array para almacenar mensajes de error

        // --- VALIDACIONES ADICIONALES DE ENTRADA ---

        // 1. Validar longitud mínima de contraseña
        if (strlen($clave) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres.";
        }

        // 2. Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del email no es válido.";
        }

        // 3. Validar Nombre Completo (solo letras y espacios)
        // Aplicamos la validación a la variable $nombres que contiene el $_POST['nombre']
        if (!preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $nombres)) {
            $errors[] = "El nombre completo solo debe contener letras y espacios.";
        }

        // 4. Verificar que el usuario no existe (con mysqli_query y escape, como tu lógica original)
        $check_sql = "SELECT id FROM usuarios WHERE usuario='$usuario'";
        $check_result = mysqli_query($conn, $check_sql);
        if (!$check_result) {
            throw new Exception("Error al verificar el usuario: " . mysqli_error($conn));
        }
        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = "Ya existe una secretaria con ese nombre de usuario.";
        }

        // 5. Verificar que el email no existe (con mysqli_query y escape, como tu lógica original)
        $check_email_sql = "SELECT id FROM usuarios WHERE email='$email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);
        if (!$check_email_result) {
            throw new Exception("Error al verificar el email: " . mysqli_error($conn));
        }
        if (mysqli_num_rows($check_email_result) > 0) {
            $errors[] = "Ya existe una secretaria con ese email.";
        }

        // Si hay errores, devolverlos y salir
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(" ", $errors)]);
            exit;
        }

        // Encriptar la contraseña usando MD5 (manteniendo tu método de hash existente)
        $clave_encriptada = md5($clave);

        // Tu consulta INSERT que usa prepared statements
        // Pasamos $nombres (que es tu $_POST['nombre']) al prepared statement para la columna 'nombre_completo'
        // Según la estructura de la tabla: id, usuario, password, tipo_usuario, nombre_completo, email, estado, fecha_creacion, ultima_conexion
        $sql = "INSERT INTO usuarios (usuario, password, tipo_usuario, nombre_completo, email, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));
        }

        // Bind de parámetros - solo 6 parámetros para las 6 columnas
        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $usuario,
            $clave_encriptada,
            $tipo_usuario,
            $nombres,
            $email,
            $estado
        );

        if (mysqli_stmt_execute($stmt)) {
            $nuevo_id = mysqli_insert_id($conn);
            echo json_encode([
                'success' => true,
                'message' => 'Nueva secretaria creada exitosamente',
                'id' => $nuevo_id,
                'secretaria' => $nombres // Usar $nombres para el mensaje, que es el contenido de $_POST['nombre']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_stmt_error($stmt)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    }
} catch (Exception $e) {
    error_log("Error en guardarSecretaria.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
} finally {
    // Cerrar conexión
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
