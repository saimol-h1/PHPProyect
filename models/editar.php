<?php
// Incluir configuración y conexión usando rutas relativas
require_once '../config/config.php';
requireFile('model', 'conexion.php');

$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];

$sql = "UPDATE estudiantes SET nombre='$nombre', apellido='$apellido', direccion='$direccion', telefono='$telefono' WHERE cedula='$cedula'";
if (mysqli_query($conn, $sql)) {
    echo "Registro editado exitosamente";
} else {
    echo "Error al editar el registro: " . mysqli_error($conn);
}
mysqli_close($conn);
