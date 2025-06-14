-- Script SQL para crear tabla de usuarios
-- Ejecutar en phpMyAdmin o desde el setup

USE cuarto;

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('administrador', 'secretaria') NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertar usuarios por defecto (contraseñas simples para el ejemplo)
INSERT INTO usuarios (usuario, password, tipo_usuario, nombre_completo, email) VALUES
('admin', MD5('admin123'), 'administrador', 'Administrator UTA', 'admin@uta.edu.ec'),
('secretaria1', MD5('secret123'), 'secretaria', 'María González', 'maria.gonzalez@uta.edu.ec'),
('secretaria2', MD5('secret456'), 'secretaria', 'Ana López', 'ana.lopez@uta.edu.ec')
ON DUPLICATE KEY UPDATE usuario=usuario;
