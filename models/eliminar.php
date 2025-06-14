<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
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
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para eliminar estudiantes']);
    exit;
}

try {
    $id = null;

    // Aceptar tanto GET como POST/DELETE
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];
    } elseif (isset($_POST['id'])) {
        $id = $_POST['id'];
    }

    if ($id) {
        $id = mysqli_real_escape_string($conn, $id);

        // Verificar que el estudiante existe antes de eliminar
        $check_sql = "SELECT id, nombres, apellidos FROM estudiantes WHERE id='$id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $estudiante = mysqli_fetch_assoc($check_result);

            $sql = "DELETE FROM estudiantes WHERE id='$id'";

            if (mysqli_query($conn, $sql)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante eliminado exitosamente',
                    'eliminado' => $estudiante['nombres'] . ' ' . $estudiante['apellidos']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el registro: ' . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el estudiante a eliminar']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de estudiante no proporcionado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Cerrar conexión
if (isset($conn)) {
    mysqli_close($conn);
}
