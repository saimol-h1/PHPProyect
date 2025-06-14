<!DOCTYPE html>
<html>

<head>
    <title>Test Login Completo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .test {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .info {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>

<body>
    <h1>Test de Sistema de Login</h1>

    <!-- Test de usuario admin -->
    <div class="test">
        <h3>🧪 Test: Login de Administrador</h3>
        <form method="post" action="models/login.php">
            <input type="hidden" name="usuario" value="admin">
            <input type="hidden" name="password" value="admin123">
            <button type="submit">Login como Admin (admin/admin123)</button>
        </form>
    </div>

    <!-- Test de usuario secretaria -->
    <div class="test">
        <h3>🧪 Test: Login de Secretaria</h3>
        <form method="post" action="models/login.php">
            <input type="hidden" name="usuario" value="secretaria1">
            <input type="hidden" name="password" value="secret123">
            <button type="submit">Login como Secretaria (secretaria1/secret123)</button>
        </form>
    </div>

    <!-- Test de login manual -->
    <div class="test">
        <h3>🧪 Test: Login Manual</h3>
        <form method="post" action="models/login.php">
            <p>Usuario: <input type="text" name="usuario" placeholder="admin o secretaria1"></p>
            <p>Contraseña: <input type="password" name="password" placeholder="admin123 o secret123"></p>
            <button type="submit">Login Manual</button>
        </form>
    </div>

    <div class="test info">
        <h3>📋 Enlaces de Navegación:</h3>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="index.php?action=login">Página de Login</a></li>
            <li><a href="index.php?action=servicios">Servicios (requiere login)</a></li>
            <li><a href="test_login.php">Test de Diagnóstico</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>

    <?php
    require_once 'config/auth.php';
    if (isLoggedIn()) {
        $user = getUsuarioInfo();
        echo "<div class='test success'>";
        echo "<h3>✅ Usuario Logueado:</h3>";
        echo "<p><strong>Usuario:</strong> " . $user['usuario'] . "</p>";
        echo "<p><strong>Tipo:</strong> " . $user['tipo'] . "</p>";
        echo "<p><strong>Nombre:</strong> " . $user['nombre_completo'] . "</p>";
        echo "</div>";
    } else {
        echo "<div class='test error'>";
        echo "<h3>❌ No hay usuario logueado</h3>";
        echo "</div>";
    }
    ?>
</body>

</html>