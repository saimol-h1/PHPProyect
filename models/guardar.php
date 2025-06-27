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
        $cedula = mysqli_real_escape_string($conn, trim($_POST['cedula'] ?? ''));
        $nombres = mysqli_real_escape_string($conn, trim($_POST['nombres'] ?? ''));
        $apellidos = mysqli_real_escape_string($conn, trim($_POST['apellidos'] ?? ''));
        $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
        $telefono = mysqli_real_escape_string($conn, trim($_POST['telefono'] ?? ''));
        $carrera = mysqli_real_escape_string($conn, trim($_POST['carrera'] ?? ''));
        $semestre = mysqli_real_escape_string($conn, trim($_POST['semestre'] ?? ''));
        $fecha_nacimiento = mysqli_real_escape_string($conn, trim($_POST['fecha_nacimiento'] ?? ''));
        $direccion = mysqli_real_escape_string($conn, trim($_POST['direccion'] ?? ''));
        $estado = 'activo'; // Estado por defecto

        $errors = []; 

        if (empty($nombres) || !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $nombres)) {
            $errors[] = "Los nombres solo deben contener letras y espacios y no pueden estar vacíos.";
        }
        if (empty($apellidos) || !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $apellidos)) {
            $errors[] = "Los apellidos solo deben contener letras y espacios y no pueden estar vacíos.";
        }

        if (empty($cedula) || !preg_match('/^[0-9]{10}$/', $cedula)) {
            $errors[] = "La cédula debe ser de 10 dígitos numéricos.";
        }
        // Verificar que la cédula no existe
        $check_sql = "SELECT id FROM estudiantes WHERE cedula='$cedula'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un estudiante con esa cédula']);
            exit;
        }

        if (empty($cedula) || !preg_match('/^[0-9]{10}$/', $cedula)) {
            $errors[] = "La cédula debe ser de 10 dígitos numéricos.";
        } else {
            // Verificar unicidad de cédula
            $check_sql = "SELECT id FROM estudiantes WHERE cedula='$cedula'";
            $check_result = mysqli_query($conn, $check_sql);
            if (!$check_result) {
                 throw new Exception("Error al verificar la cédula: " . mysqli_error($conn));
            }
            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "Ya existe un estudiante con esa cédula.";
            }
        }

        if (empty($carrera) || !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $carrera)) {
            $errors[] = "La carrera solo debe contener letras y espacios y no puede estar vacía.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del email es inválido o el campo está vacío.";
        } else {
            // Verificar unicidad de email
            $check_email_sql = "SELECT id FROM estudiantes WHERE email='$email'";
            $check_email_result = mysqli_query($conn, $check_email_sql);
            if (!$check_email_result) {
                throw new Exception("Error al verificar el email: " . mysqli_error($conn));
            }
            if (mysqli_num_rows($check_email_result) > 0) {
                $errors[] = "Ya existe un estudiante con ese email.";
            }
        }       

        if (!empty($telefono) && !preg_match('/^[0-9]{10}$/', $telefono)) {
            $errors[] = "El teléfono debe ser de 10 dígitos numéricos o estar vacío.";
        }

        $semestre_int = filter_var($semestre, FILTER_VALIDATE_INT);
        if ($semestre_int === false || $semestre_int < 1 || $semestre_int > 10) {
             if (!empty($semestre)) { // Solo añadir error si se intenta enviar un valor inválido o está fuera de rango
                $errors[] = "El semestre debe ser un número entero entre 1 y 10.";
             }
        } else {
            $semestre = $semestre_int; // Usar el valor validado
        }

        // 8. Fecha de Nacimiento (Mayor de 16 años y formato válido)
        if (empty($fecha_nacimiento)) {
            $errors[] = "La fecha de nacimiento es obligatoria.";
        } else {
            $fecha_nacimiento_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
            if (!$fecha_nacimiento_obj || $fecha_nacimiento_obj->format('Y-m-d') !== $fecha_nacimiento) { // Valida formato estricto
                $errors[] = "Formato de fecha de nacimiento inválido (YYYY-MM-DD).";
            } else {
                $hoy = new DateTime();
                $edad = $hoy->diff($fecha_nacimiento_obj)->y;
                if ($edad < 16) {
                    $errors[] = "El estudiante debe tener al menos 16 años.";
                }
            }
        }

        // Si hay errores, devolverlos y salir
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(" ", $errors)]);
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
