<?php

/**
 * Script para verificar y crear la base de datos automáticamente
 * Ejecutar este archivo una vez para configurar la BD local
 */

echo "<h2>🔧 Configurador de Base de Datos Local</h2>";

// Intentar conectar sin especificar base de datos
$conn = @mysqli_connect('localhost', 'root', '');

if (!$conn) {
    echo "<div style='background: #ffebee; padding: 15px; border: 1px solid #f44336;'>";
    echo "<h3>❌ No se puede conectar a MySQL</h3>";
    echo "<p><strong>Error:</strong> " . mysqli_connect_error() . "</p>";
    echo "<h4>💡 Soluciones:</h4>";
    echo "<ul>";
    echo "<li>Verifica que XAMPP esté ejecutándose</li>";
    echo "<li>Inicia el servicio MySQL desde el panel de XAMPP</li>";
    echo "<li>Verifica que el puerto 3306 no esté bloqueado</li>";
    echo "</ul>";
    echo "</div>";
    exit();
}

echo "<div style='background: #e8f5e8; padding: 15px; border: 1px solid #4caf50;'>";
echo "✅ Conexión a MySQL exitosa<br>";

// Verificar si existe la base de datos
$result = mysqli_query($conn, "SHOW DATABASES LIKE 'cuarto'");
if (mysqli_num_rows($result) > 0) {
    echo "✅ La base de datos 'cuarto' ya existe<br>";
} else {
    echo "⚠️ La base de datos 'cuarto' no existe. Creándola...<br>";

    // Crear la base de datos
    if (mysqli_query($conn, "CREATE DATABASE cuarto CHARACTER SET utf8 COLLATE utf8_general_ci")) {
        echo "✅ Base de datos 'cuarto' creada exitosamente<br>";
    } else {
        echo "❌ Error al crear la base de datos: " . mysqli_error($conn) . "<br>";
        exit();
    }
}

// Seleccionar la base de datos
mysqli_select_db($conn, 'cuarto');

// Verificar si existe la tabla estudiantes
$result = mysqli_query($conn, "SHOW TABLES LIKE 'estudiantes'");
if (mysqli_num_rows($result) > 0) {
    echo "✅ La tabla 'estudiantes' ya existe<br>";
} else {
    echo "⚠️ La tabla 'estudiantes' no existe. Creándola...<br>";

    // Crear la tabla con la nueva estructura
    $sql = "CREATE TABLE estudiantes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cedula VARCHAR(10) UNIQUE NOT NULL,
        nombres VARCHAR(100) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        telefono VARCHAR(15),
        carrera VARCHAR(100),
        semestre INT,
        fecha_nacimiento DATE,
        direccion TEXT,
        estado ENUM('activo', 'inactivo') DEFAULT 'activo',
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (mysqli_query($conn, $sql)) {
        echo "✅ Tabla 'estudiantes' creada exitosamente<br>";

        // Insertar datos de ejemplo con la nueva estructura
        $sql_data = "INSERT INTO estudiantes (cedula, nombres, apellidos, email, telefono, carrera, semestre, fecha_nacimiento, direccion) VALUES
        ('1234567890', 'Juan Carlos', 'Pérez López', 'juan.perez@uta.edu.ec', '0987654321', 'Ingeniería en Sistemas', 5, '2000-03-15', 'Ambato, Ecuador'),
        ('0987654321', 'María Elena', 'García Morales', 'maria.garcia@uta.edu.ec', '0998765432', 'Administración de Empresas', 3, '2001-07-22', 'Quito, Ecuador'),
        ('1122334455', 'Carlos Alberto', 'Ramírez Silva', 'carlos.ramirez@uta.edu.ec', '0976543210', 'Ingeniería Industrial', 7, '1999-11-08', 'Riobamba, Ecuador')";

        if (mysqli_query($conn, $sql_data)) {
            echo "✅ Datos de ejemplo insertados<br>";
        } else {
            echo "⚠️ No se pudieron insertar los datos de ejemplo: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "❌ Error al crear la tabla: " . mysqli_error($conn) . "<br>";
    }
}

// Verificar y crear tabla de usuarios
$result = mysqli_query($conn, "SHOW TABLES LIKE 'usuarios'");
if (mysqli_num_rows($result) > 0) {
    echo "✅ La tabla 'usuarios' ya existe<br>";
} else {
    echo "⚠️ La tabla 'usuarios' no existe. Creándola...<br>";
    $sql_usuarios = "CREATE TABLE usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        tipo_usuario ENUM('administrador', 'secretaria') NOT NULL,
        nombre_completo VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        estado ENUM('activo', 'inactivo') DEFAULT 'activo',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ultima_conexion TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (mysqli_query($conn, $sql_usuarios)) {
        echo "✅ Tabla 'usuarios' creada exitosamente<br>";        // Insertar usuarios por defecto
        $sql_usuarios_data = "INSERT INTO usuarios (usuario, password, tipo_usuario, nombre_completo, email) VALUES
        ('admin', MD5('admin123'), 'administrador', 'Administrador del Sistema', 'admin@uta.edu.ec'),
        ('secretaria1', MD5('secret123'), 'secretaria', 'María González', 'secretaria1@uta.edu.ec'),
        ('secretaria2', MD5('secret123'), 'secretaria', 'Ana Martínez', 'secretaria2@uta.edu.ec')";

        if (mysqli_query($conn, $sql_usuarios_data)) {
            echo "✅ Usuarios de ejemplo insertados<br>";
        } else {
            echo "⚠️ No se pudieron insertar los usuarios de ejemplo: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "❌ Error al crear la tabla usuarios: " . mysqli_error($conn) . "<br>";
    }
}

// Verificar cantidad de registros
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM estudiantes");
$row = mysqli_fetch_assoc($result);
echo "📊 Total de estudiantes en la base: " . $row['total'] . "<br>";

echo "</div>";

echo "<div style='margin-top: 20px; padding: 15px; background: #e3f2fd; border: 1px solid #2196f3;'>";
echo "<h3>🎉 ¡Configuración Completada!</h3>";
echo "<p>Tu base de datos local está lista. Ahora puedes:</p>";
echo "<h4>📋 Credenciales de acceso:</h4>";
echo "<ul>";
echo "<li><strong>Administrador:</strong> admin / admin123</li>";
echo "<li><strong>Secretaria:</strong> secretaria1 / secret123</li>";
echo "<li><strong>Secretaria:</strong> secretaria2 / secret123</li>";
echo "</ul>";
echo "<h4>🔗 Enlaces útiles:</h4>";
echo "<ul>";
echo "<li><a href='index.php'>🏠 Ir al proyecto principal</a></li>";
echo "<li><a href='debug.php'>🔍 Ver información de debug</a></li>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>🗄️ Abrir phpMyAdmin</a></li>";
echo "</ul>";
echo "</div>";

mysqli_close($conn);
