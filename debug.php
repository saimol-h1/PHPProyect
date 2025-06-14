<?php
// Archivo temporal para debug - eliminar después
echo "<h3>Debug de Configuración</h3>";
echo "<strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "<br>";
echo "<strong>SERVER_NAME:</strong> " . $_SERVER['SERVER_NAME'] . "<br>";
echo "<strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";

// Verificar qué condición se cumple
if ($_SERVER['HTTP_HOST'] == 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    echo "<strong>Entorno detectado:</strong> DESARROLLO (LOCAL)<br>";
} else {
    echo "<strong>Entorno detectado:</strong> PRODUCCIÓN<br>";
}

// Verificar si XAMPP está corriendo
$connection = @mysqli_connect('localhost', 'root', '', 'cuarto');
if ($connection) {
    echo "<strong>Conexión MySQL:</strong> ✅ EXITOSA<br>";
    mysqli_close($connection);
} else {
    echo "<strong>Conexión MySQL:</strong> ❌ FALLÓ - " . mysqli_connect_error() . "<br>";
}

// Verificar si la base de datos existe
$connection = @mysqli_connect('localhost', 'root', '');
if ($connection) {
    $result = mysqli_query($connection, "SHOW DATABASES LIKE 'cuarto'");
    if (mysqli_num_rows($result) > 0) {
        echo "<strong>Base de datos 'cuarto':</strong> ✅ EXISTE<br>";
    } else {
        echo "<strong>Base de datos 'cuarto':</strong> ❌ NO EXISTE<br>";
    }
    mysqli_close($connection);
}
