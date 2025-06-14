<?php

/**
 * Setup automático para Railway
 * Se ejecuta una vez para inicializar la base de datos
 */

// Solo ejecutar en Railway o si se pasa parámetro específico
if (!isset($_ENV['RAILWAY_ENVIRONMENT']) && !isset($_GET['force'])) {
    die("Este script solo se ejecuta en Railway o con parámetro force=1");
}

require_once 'config/database_railway.php';

echo "<h1>🚂 Setup de Railway - UTA MVC System</h1>";

try {
    // Crear tabla usuarios
    $sql_usuarios = "
    CREATE TABLE IF NOT EXISTS usuarios (
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

    if ($conn->query($sql_usuarios) === TRUE) {
        echo "<p style='color: green;'>✅ Tabla 'usuarios' creada correctamente</p>";
    } else {
        throw new Exception("Error creando tabla usuarios: " . $conn->error);
    }

    // Crear tabla estudiantes
    $sql_estudiantes = "
    CREATE TABLE IF NOT EXISTS estudiantes (
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

    if ($conn->query($sql_estudiantes) === TRUE) {
        echo "<p style='color: green;'>✅ Tabla 'estudiantes' creada correctamente</p>";
    } else {
        throw new Exception("Error creando tabla estudiantes: " . $conn->error);
    }

    // Insertar usuarios por defecto (solo si no existen)
    $check_users = "SELECT COUNT(*) as count FROM usuarios";
    $result = $conn->query($check_users);
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        $insert_usuarios = "
        INSERT INTO usuarios (usuario, password, tipo_usuario, nombre_completo, email) VALUES
        ('admin', MD5('admin123'), 'administrador', 'Administrador del Sistema', 'admin@uta.edu.ec'),
        ('secretaria1', MD5('secret123'), 'secretaria', 'María González', 'secretaria1@uta.edu.ec'),
        ('secretaria2', MD5('secret123'), 'secretaria', 'Ana Martínez', 'secretaria2@uta.edu.ec')";

        if ($conn->query($insert_usuarios) === TRUE) {
            echo "<p style='color: green;'>✅ Usuarios por defecto insertados</p>";
        } else {
            throw new Exception("Error insertando usuarios: " . $conn->error);
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Los usuarios ya existen, no se insertaron duplicados</p>";
    }

    // Insertar algunos estudiantes de ejemplo (solo si no existen)
    $check_students = "SELECT COUNT(*) as count FROM estudiantes";
    $result = $conn->query($check_students);
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        $insert_estudiantes = "
        INSERT INTO estudiantes (cedula, nombres, apellidos, email, telefono, carrera, semestre, fecha_nacimiento, direccion) VALUES
        ('1234567890', 'Juan Carlos', 'Pérez López', 'juan.perez@uta.edu.ec', '0987654321', 'Ingeniería en Sistemas', 5, '2000-03-15', 'Ambato, Ecuador'),
        ('0987654321', 'María Elena', 'García Morales', 'maria.garcia@uta.edu.ec', '0998765432', 'Administración de Empresas', 3, '2001-07-22', 'Quito, Ecuador'),
        ('1122334455', 'Carlos Alberto', 'Ramírez Silva', 'carlos.ramirez@uta.edu.ec', '0976543210', 'Ingeniería Industrial', 7, '1999-11-08', 'Riobamba, Ecuador')";

        if ($conn->query($insert_estudiantes) === TRUE) {
            echo "<p style='color: green;'>✅ Estudiantes de ejemplo insertados</p>";
        } else {
            throw new Exception("Error insertando estudiantes: " . $conn->error);
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Los estudiantes ya existen, no se insertaron duplicados</p>";
    }

    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 ¡Setup completado exitosamente!</h2>";
    echo "<h3>📋 Credenciales de acceso:</h3>";
    echo "<ul>";
    echo "<li><strong>Administrador:</strong> admin / admin123</li>";
    echo "<li><strong>Secretaria:</strong> secretaria1 / secret123</li>";
    echo "</ul>";
    echo "<p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Acceder al Sistema</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error durante el setup: " . $e->getMessage() . "</p>";
    exit(1);
}

$conn->close();
