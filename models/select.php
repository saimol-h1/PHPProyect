<?php
// Configurar headers para AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración de base de datos directamente
require_once '../config/database.php';

try {
    // Usar las columnas reales de la tabla
    $sql = "SELECT * FROM estudiantes ORDER BY nombres ASC";
    $respuesta = $conn->query($sql);
    $resultado = array();

    if ($respuesta && $respuesta->num_rows > 0) {
        while ($fila = $respuesta->fetch_assoc()) {
            array_push($resultado, $fila);
        }
    }

    // Devolver respuesta en formato esperado por JavaScript
    $response = array(
        'success' => true,
        'data' => $resultado,
        'count' => count($resultado)
    );

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // En caso de error, devolver respuesta con error
    $response = array(
        'success' => false,
        'error' => 'Error al consultar datos: ' . $e->getMessage(),
        'data' => array()
    );
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Cerrar conexión
if (isset($conn)) {
    mysqli_close($conn);
}
