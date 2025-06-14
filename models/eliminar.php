<?php
// Incluir configuración y conexión usando rutas relativas
require_once '../config/config.php';
requireFile('model', 'conexion.php');

$cedula = $_GET['cedula'];
$sql = "DELETE FROM estudiantes WHERE cedula='$cedula'";
if (mysqli_query($conn, $sql)) {
    echo "Registro eliminado exitosamente";
} else {
    echo "Error al eliminar el registro: " . mysqli_error($conn);
}
mysqli_close($conn);
