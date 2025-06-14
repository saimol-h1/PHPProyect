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
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para agregar estudiantes']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
        $nombres = mysqli_real_escape_string($conn, $_POST['nombres']);
        $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono'] ?? '');
        $carrera = mysqli_real_escape_string($conn, $_POST['carrera']);
        $semestre = mysqli_real_escape_string($conn, $_POST['semestre'] ?? '');
        $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento'] ?? '');
        $direccion = mysqli_real_escape_string($conn, $_POST['direccion'] ?? '');
        $estado = 'activo'; // Estado por defecto

        // Verificar que la cédula no existe
        $check_sql = "SELECT id FROM estudiantes WHERE cedula='$cedula'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un estudiante con esa cédula']);
            exit;
        }

        // Verificar que el email no existe
        $check_email_sql = "SELECT id FROM estudiantes WHERE email='$email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);

        if (mysqli_num_rows($check_email_result) > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un estudiante con ese email']);
            exit;
        }

        $sql = "INSERT INTO estudiantes (cedula, nombres, apellidos, email, telefono, carrera, semestre, fecha_nacimiento, direccion, estado, fecha_registro, fecha_actualizacion) 
                VALUES ('$cedula', '$nombres', '$apellidos', '$email', '$telefono', '$carrera', '$semestre', '$fecha_nacimiento', '$direccion', '$estado', NOW(), NOW())";

        if (mysqli_query($conn, $sql)) {
            $nuevo_id = mysqli_insert_id($conn);
            echo json_encode([
                'success' => true,
                'message' => 'Nuevo estudiante creado exitosamente',
                'id' => $nuevo_id,
                'estudiante' => $nombres . ' ' . $apellidos
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
