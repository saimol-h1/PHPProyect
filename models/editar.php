<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración de base de datos directamente
require_once '../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
        $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);

        // Si existe cedula_original, usar esa para el WHERE (por si se cambió la cédula)
        $cedula_where = isset($_POST['cedula_original']) ? mysqli_real_escape_string($conn, $_POST['cedula_original']) : $cedula;

        $sql = "UPDATE estudiantes SET 
                cedula='$cedula', 
                nombre='$nombre', 
                apellido='$apellido', 
                direccion='$direccion', 
                telefono='$telefono' 
                WHERE cedula='$cedula_where'";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Registro editado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al editar el registro: ' . mysqli_error($conn)]);
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
