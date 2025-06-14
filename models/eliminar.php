<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración de base de datos directamente
require_once '../config/database.php';

try {
    if (isset($_GET['cedula'])) {
        $cedula = mysqli_real_escape_string($conn, $_GET['cedula']);

        $sql = "DELETE FROM estudiantes WHERE cedula='$cedula'";

        if (mysqli_query($conn, $sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                echo json_encode(['success' => true, 'message' => 'Registro eliminado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró el registro a eliminar']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el registro: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Cédula no proporcionada']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Cerrar conexión
if (isset($conn)) {
    mysqli_close($conn);
}
