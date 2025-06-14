<?php
// Incluir configuración y conexión usando rutas relativas
require_once '../config/config.php';
requireFile('model', 'conexion.php');

$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$sql = "insert into estudiantes VALUES ('$cedula', '$nombre', '$apellido', '$direccion', '$telefono')";

if ($conn->query($sql) === TRUE) {
    echo json_encode("Nuevo registro creado exitosamente");
} else {
    echo json_encode("Error: " . $sql . "<br>" . $conn->error);
}
