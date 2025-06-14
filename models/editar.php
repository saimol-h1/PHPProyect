<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
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
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para editar estudiantes']);
    exit;
}

try {
    // Manejar tanto POST como PUT
    $data = [];
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input;
    } else {
        $data = $_POST;
    }

    // Obtener datos de un estudiante específico para edición (GET)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT * FROM estudiantes WHERE id='$id'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $estudiante = mysqli_fetch_assoc($result);
            echo json_encode(['success' => true, 'data' => $estudiante]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
        }
        exit;
    }

    // Actualizar estudiante (POST/PUT)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $id = mysqli_real_escape_string($conn, $data['id']);
        $cedula = mysqli_real_escape_string($conn, $data['cedula']);
        $nombres = mysqli_real_escape_string($conn, $data['nombres']);
        $apellidos = mysqli_real_escape_string($conn, $data['apellidos']);
        $email = mysqli_real_escape_string($conn, $data['email']);
        $telefono = mysqli_real_escape_string($conn, $data['telefono'] ?? '');
        $carrera = mysqli_real_escape_string($conn, $data['carrera']);
        $semestre = mysqli_real_escape_string($conn, $data['semestre'] ?? '');
        $fecha_nacimiento = mysqli_real_escape_string($conn, $data['fecha_nacimiento'] ?? '');
        $direccion = mysqli_real_escape_string($conn, $data['direccion'] ?? '');
        $estado = mysqli_real_escape_string($conn, $data['estado'] ?? 'activo');

        // Verificar que el estudiante existe
        $check_sql = "SELECT id FROM estudiantes WHERE id='$id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $sql = "UPDATE estudiantes SET 
                    cedula='$cedula', 
                    nombres='$nombres', 
                    apellidos='$apellidos', 
                    email='$email',
                    telefono='$telefono',
                    carrera='$carrera',
                    semestre='$semestre',
                    fecha_nacimiento='$fecha_nacimiento',
                    direccion='$direccion',
                    estado='$estado',
                    fecha_actualizacion=NOW()
                    WHERE id='$id'";

            if (mysqli_query($conn, $sql)) {
                echo json_encode(['success' => true, 'message' => 'Estudiante actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el registro: ' . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
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
