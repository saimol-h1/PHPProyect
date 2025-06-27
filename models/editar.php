<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
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
        $id = mysqli_real_escape_string($conn, trim($data['id'] ?? ''));
        $cedula_from_data = mysqli_real_escape_string($conn, trim($data['cedula'] ?? ''));
        $nombres = mysqli_real_escape_string($conn, trim($data['nombres'] ?? ''));
        $apellidos = mysqli_real_escape_string($conn, trim($data['apellidos'] ?? ''));
        $email = mysqli_real_escape_string($conn, trim($data['email'] ?? ''));
        $telefono = mysqli_real_escape_string($conn, trim($data['telefono'] ?? ''));
        $carrera = mysqli_real_escape_string($conn, trim($data['carrera'] ?? ''));
        $semestre = mysqli_real_escape_string($conn, trim($data['semestre'] ?? ''));
        $fecha_nacimiento = mysqli_real_escape_string($conn, trim($data['fecha_nacimiento'] ?? ''));
        $direccion = mysqli_real_escape_string($conn, trim($data['direccion'] ?? ''));
        $estado = mysqli_real_escape_string($conn, trim($data['estado'] ?? 'activo'));

        $errors = []; // Array para almacenar mensajes de error

        // Validar que el ID es un número y está presente
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $errors[] = "ID de estudiante inválido o no proporcionado.";
        } else {
            // Verificar que el estudiante existe antes de intentar actualizar
            $check_id_sql = "SELECT id FROM estudiantes WHERE id='$id'";
            $check_id_result = mysqli_query($conn, $check_id_sql);
            if (!$check_id_result || mysqli_num_rows($check_id_result) === 0) {
                $errors[] = "Estudiante no encontrado para actualizar.";
            }
        }


        if (empty($nombres) || !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $nombres)) {
            $errors[] = "Los nombres solo deben contener letras y espacios y no pueden estar vacíos.";
        }

        if (empty($apellidos) || !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $apellidos)) {
            $errors[] = "Los apellidos solo deben contener letras y espacios y no pueden estar vacíos.";
        }

        if (empty($carrera) || !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $carrera)) {
            $errors[] = "La carrera solo debe contener letras y espacios y no puede estar vacía.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del email es inválido o el campo está vacío.";
        } else {
            $check_email_sql = "SELECT id FROM estudiantes WHERE email='$email' AND id != '$id'";
            $check_email_result = mysqli_query($conn, $check_email_sql);
            if (!$check_email_result) {
                throw new Exception("Error al verificar el email: " . mysqli_error($conn));
            }
            if (mysqli_num_rows($check_email_result) > 0) {
                $errors[] = "Ya existe otro estudiante con ese email.";
            }
        }

        if (!empty($telefono) && !preg_match('/^[0-9]{10}$/', $telefono)) {
            $errors[] = "El teléfono debe ser de 10 dígitos numéricos o estar vacío.";
        }

        $semestre_int = filter_var($semestre, FILTER_VALIDATE_INT);
        if ($semestre_int === false || $semestre_int < 1 || $semestre_int > 10) {
            if (!empty($semestre) || (string)$semestre === '0') {
                $errors[] = "El semestre debe ser un número entero entre 1 y 10.";
            }
        } else {
            $semestre = $semestre_int;
        }

        if (empty($fecha_nacimiento)) {
            $errors[] = "La fecha de nacimiento es obligatoria.";
        } else {
            $fecha_nacimiento_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
            if (!$fecha_nacimiento_obj || $fecha_nacimiento_obj->format('Y-m-d') !== $fecha_nacimiento) {
                $errors[] = "Formato de fecha de nacimiento inválido (YYYY-MM-DD).";
            } else {
                $hoy = new DateTime();
                $edad = $hoy->diff($fecha_nacimiento_obj)->y;
                if ($edad < 16) {
                    $errors[] = "El estudiante debe tener al menos 16 años.";
                }
            }
        }


        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(" ", $errors)]);
            exit;
        }

        // Si todas las validaciones pasan y el estudiante existe, procede con la actualización
        $sql = "UPDATE estudiantes SET 
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
            echo json_encode(['success' => true, 'message' => 'Estudiante actualizado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el registro: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido para esta acción.']);
    }
} catch (Exception $e) {
    error_log("Error en editar.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ocurrió un error inesperado al procesar la solicitud.']);
} finally {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
