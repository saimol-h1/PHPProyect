<?php

/**
 * Script para verificar y corregir estructura de tablas
 */

require_once 'config/database.php';

echo "<h1>🔧 Verificar y Corregir Tablas</h1>";

// Verificar si la tabla estudiantes tiene las columnas necesarias
echo "<h2>Verificando tabla estudiantes...</h2>";

$required_columns = ['id', 'nombre', 'apellido', 'cedula', 'carrera', 'email'];
$sql = "DESCRIBE estudiantes";
$result = $conn->query($sql);

$existing_columns = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $existing_columns[] = $row['Field'];
    }
}

echo "<p><strong>Columnas existentes:</strong> " . implode(', ', $existing_columns) . "</p>";
echo "<p><strong>Columnas requeridas:</strong> " . implode(', ', $required_columns) . "</p>";

// Si no tiene la estructura correcta, crearla
if (!in_array('nombre', $existing_columns) || !in_array('apellido', $existing_columns)) {
    echo "<h3>⚠️ Estructura incorrecta. Recreando tabla...</h3>";

    // Respaldar datos existentes si los hay
    $backup_data = [];
    if (in_array('id', $existing_columns)) {
        $sql = "SELECT * FROM estudiantes";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $backup_data[] = $row;
            }
        }
    }

    // Recrear tabla con estructura correcta
    $sql = "DROP TABLE IF EXISTS estudiantes";
    if ($conn->query($sql)) {
        echo "<p>✅ Tabla antigua eliminada</p>";
    }

    $sql = "CREATE TABLE estudiantes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        apellido VARCHAR(100) NOT NULL,
        cedula VARCHAR(20) UNIQUE NOT NULL,
        carrera VARCHAR(150) NOT NULL,
        email VARCHAR(100) NOT NULL,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($conn->query($sql)) {
        echo "<p>✅ Nueva tabla creada correctamente</p>";

        // Insertar datos de muestra
        $sample_students = [
            ['Juan Carlos', 'Pérez García', '1234567890', 'Ingeniería en Sistemas', 'juan.perez@uta.edu.ec'],
            ['María Elena', 'González López', '0987654321', 'Administración de Empresas', 'maria.gonzalez@uta.edu.ec'],
            ['Carlos Alberto', 'Rodríguez Silva', '1122334455', 'Ingeniería Industrial', 'carlos.rodriguez@uta.edu.ec'],
            ['Ana Sofía', 'Martínez Torres', '5566778899', 'Psicología', 'ana.martinez@uta.edu.ec'],
            ['Luis Fernando', 'Sánchez Vega', '9988776655', 'Derecho', 'luis.sanchez@uta.edu.ec']
        ];

        foreach ($sample_students as $student) {
            $sql = "INSERT INTO estudiantes (nombre, apellido, cedula, carrera, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $student[0], $student[1], $student[2], $student[3], $student[4]);

            if ($stmt->execute()) {
                echo "<p>✅ Estudiante agregado: {$student[0]} {$student[1]}</p>";
            } else {
                echo "<p>❌ Error agregando: {$student[0]} {$student[1]} - " . $stmt->error . "</p>";
            }
        }
    } else {
        echo "<p>❌ Error creando tabla: " . $conn->error . "</p>";
    }
} else {
    echo "<p>✅ Estructura de tabla correcta</p>";

    // Verificar si hay datos
    $sql = "SELECT COUNT(*) as total FROM estudiantes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['total'] == 0) {
        echo "<p>⚠️ No hay datos. Agregando estudiantes de muestra...</p>";
        // Agregar datos de muestra aquí si es necesario
    } else {
        echo "<p>✅ Tabla tiene {$row['total']} registros</p>";
    }
}

echo "<h2>✨ Proceso completado</h2>";
echo "<p><a href='check_tables.php'>Ver estructura actual</a></p>";
echo "<p><a href='models/select.php'>Probar consulta de estudiantes</a></p>";
