<?php
// Incluir configuración y conexión usando rutas relativas
require_once '../config/config.php';
requireFile('model', 'conexion.php');

$sql = "select * from estudiantes";
$respuesta = $conn->query($sql);
$resultado = array();
if ($respuesta->num_rows > 0) {
    while ($fila = $respuesta->fetch_assoc()) {
        array_push($resultado, $fila);
    }
} else {
    $resultado = "No hay estudiantes";
}
echo json_encode($resultado);
