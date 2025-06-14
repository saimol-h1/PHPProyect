<?php
// Test login simple para verificar que funciona
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'config/auth.php';

echo "<h2>Test de Login</h2>";

// Test de conexión a base de datos
if (isset($conn) && $conn) {
    echo "<p style='color: green;'>✅ Conexión a BD: OK</p>";
} else {
    echo "<p style='color: red;'>❌ Conexión a BD: FAIL</p>";
}

// Test de sesiones
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p style='color: green;'>✅ Sesiones: OK</p>";
} else {
    echo "<p style='color: red;'>❌ Sesiones: FAIL</p>";
}

// Test de usuarios en BD
$sql = "SELECT usuario, tipo_usuario FROM usuarios WHERE estado = 'activo'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<p style='color: green;'>✅ Usuarios en BD: " . mysqli_num_rows($result) . "</p>";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['usuario'] . " (" . $row['tipo_usuario'] . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ No hay usuarios en BD</p>";
}

// Test de función isLoggedIn
if (function_exists('isLoggedIn')) {
    $loggedStatus = isLoggedIn() ? "SÍ" : "NO";
    echo "<p>🔍 Usuario logueado: " . $loggedStatus . "</p>";
} else {
    echo "<p style='color: red;'>❌ Función isLoggedIn no existe</p>";
}

// Formulario de test
echo "<hr>";
echo "<h3>Test de Login Directo</h3>";
echo "<form method='POST' action='test_login.php'>";
echo "<p>Usuario: <input type='text' name='usuario' value='admin'></p>";
echo "<p>Contraseña: <input type='password' name='password' value='admin123'></p>";
echo "<p><button type='submit'>Probar Login</button></p>";
echo "</form>";

// Procesar login si viene POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    echo "<hr><h3>Resultado del Test:</h3>";

    $sql = "SELECT id, usuario, password, tipo_usuario, nombre_completo, estado FROM usuarios WHERE usuario = ? AND estado = 'activo'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo "<p>✅ Usuario encontrado: " . $row['usuario'] . "</p>";
        echo "<p>🔐 Hash en BD: " . $row['password'] . "</p>";
        echo "<p>🔐 Hash calculado: " . md5($password) . "</p>";

        if (md5($password) === $row['password']) {
            echo "<p style='color: green;'>✅ Contraseña correcta</p>";

            // Intentar crear sesión
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_nombre'] = $row['usuario'];
            $_SESSION['usuario_tipo'] = $row['tipo_usuario'];
            $_SESSION['nombre_completo'] = $row['nombre_completo'];
            $_SESSION['login_time'] = time();

            echo "<p style='color: green;'>✅ Sesión creada</p>";
            echo "<p>🎯 Redirigiendo a: <a href='index.php?action=servicios'>Servicios</a></p>";
        } else {
            echo "<p style='color: red;'>❌ Contraseña incorrecta</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Usuario no encontrado</p>";
    }
}
