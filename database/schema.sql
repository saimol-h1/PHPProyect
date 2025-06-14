-- Script SQL para crear la base de datos en producción
-- Ejecutar este script en el panel de control de tu hosting

CREATE DATABASE IF NOT EXISTS cuarto CHARACTER SET utf8 COLLATE utf8_general_ci;

USE cuarto;

-- Tabla de estudiantes
CREATE TABLE IF NOT EXISTS estudiantes (
    cedula VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertar datos de ejemplo (opcional)
INSERT INTO estudiantes (cedula, nombre, apellido, direccion, telefono) VALUES
('1234567890', 'Juan', 'Pérez', 'Av. Principal 123', '0987654321'),
('0987654321', 'María', 'González', 'Calle Secundaria 456', '0912345678'),
('1122334455', 'Carlos', 'Rodríguez', 'Barrio Centro 789', '0998877665')
ON DUPLICATE KEY UPDATE cedula=cedula;
