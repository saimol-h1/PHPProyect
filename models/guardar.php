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

        $sql = "INSERT INTO estudiantes (cedula, nombre, apellido, direccion, telefono) 
                VALUES ('$cedula', '$nombre', '$apellido', '$direccion', '$telefono')";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Nuevo registro creado exitosamente']);
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
