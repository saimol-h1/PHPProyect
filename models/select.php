<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración de base de datos directamente
require_once '../config/database.php';

try {
    $sql = "SELECT * FROM estudiantes ORDER BY nombre ASC";
    $respuesta = $conn->query($sql);
    $resultado = array();

    if ($respuesta && $respuesta->num_rows > 0) {
        while ($fila = $respuesta->fetch_assoc()) {
            array_push($resultado, $fila);
        }
    } else {
        $resultado = array(); // Array vacío en lugar de string
    }

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // En caso de error, devolver un JSON con el error
    $error = array('error' => 'Error al consultar datos: ' . $e->getMessage());
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}

// Cerrar conexión
if (isset($conn)) {
    mysqli_close($conn);
}
