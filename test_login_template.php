<!DOCTYPE html>
<html>

<head>
    <title>Test Login desde Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .form-test {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <h1>🧪 Test Login desde Template Principal</h1>

    <div class="form-test">
        <h3>Simular envío desde login.php en template</h3>
        <form action="models/login.php" method="POST">
            <p>
                <label>Usuario:</label>
                <input type="text" name="usuario" value="admin" style="padding: 8px; margin-left: 10px;">
            </p>
            <p>
                <label>Contraseña:</label>
                <input type="password" name="password" value="admin123" style="padding: 8px; margin-left: 10px;">
            </p>
            <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px;">
                🚀 Test Login (admin/admin123)
            </button>
        </form>
    </div>

    <div class="form-test">
        <h3>Links para verificar:</h3>
        <ul>
            <li><a href="index.php?action=login">Login usando template</a></li>
            <li><a href="index.php?action=servicios">Servicios (requiere login)</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
            <li><a href="test_login_complete.php">Test completo</a></li>
        </ul>
    </div>

    <?php
    require_once 'config/auth.php';

    if (isset($_GET['test']) && $_GET['test'] === 'session') {
        echo "<div class='form-test'>";
        echo "<h3>🔍 Estado de la Sesión:</h3>";

        if (isLoggedIn()) {
            $user = getUsuarioInfo();
            echo "<div class='success'>";
            echo "<strong>✅ Usuario logueado:</strong><br>";
            echo "Usuario: " . htmlspecialchars($user['usuario']) . "<br>";
            echo "Tipo: " . htmlspecialchars($user['tipo']) . "<br>";
            echo "Nombre: " . htmlspecialchars($user['nombre_completo']);
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<strong>❌ No hay usuario logueado</strong>";
            echo "</div>";
        }
        echo "</div>";
    }
    ?>

    <div class="form-test">
        <a href="?test=session" style="padding: 8px 16px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;">
            🔍 Verificar Estado de Sesión
        </a>
    </div>
</body>

</html>