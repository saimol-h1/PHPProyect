<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración híbrida
require_once '../config/database_hybrid.php';
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
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'El formato del email no es válido']);
            exit;
        }
        
        // Validar longitud mínima de contraseña
        if (strlen($clave) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            exit;
        }
        
        // Encriptar la contraseña usando password_hash (recomendado)
        $clave_encriptada = md5($clave);

        // Verificar que el usuario no existe
        $check_sql = "SELECT id FROM usuarios WHERE usuario='$usuario'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya existe una secretaria con ese usuario']);
            exit;
        }

        // Verificar que el email no existe
        $check_email_sql = "SELECT id FROM usuarios WHERE email='$email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);

        if (mysqli_num_rows($check_email_result) > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya existe una secretaria con ese email']);
            exit;
        }

        $sql = "INSERT INTO usuarios VALUES (NULL, '$usuario', '$clave_encriptada', '$tipo_usuario', '$nombres', '$email', '$estado', NOW(), NULL)";

        if (mysqli_query($conn, $sql)) {
            $nuevo_id = mysqli_insert_id($conn);
            echo json_encode([
                'success' => true,
                'message' => 'Nueva secretaria creada exitosamente',
                'id' => $nuevo_id,
                'secretaria' => $nombres
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Cerrar conexión
if (isset($conn)) {
    mysqli_close($conn);
}
