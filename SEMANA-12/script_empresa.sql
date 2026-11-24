-- ============================================================================
-- BASE DE DATOS: empresa
-- Compatible con phpMyAdmin (Importar / Pestaña SQL) y Visual Studio Code
-- Guía Práctica de Laboratorio: Módulo de Registro de Empleados
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Crear la base de datos 'empresa'
CREATE DATABASE IF NOT EXISTS `empresa`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_spanish_ci;

USE `empresa`;

-- 2. Crear la tabla 'empleados'
CREATE TABLE IF NOT EXISTS `empleados` (
  `ID` INT AUTO_INCREMENT PRIMARY KEY,
  `Nombre` VARCHAR(250) NOT NULL,
  `ApellidoP` VARCHAR(250) NOT NULL,
  `ApellidoM` VARCHAR(250) DEFAULT NULL,
  `Correo` VARCHAR(100) UNIQUE NOT NULL,
  `Foto` VARCHAR(100) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- 3. Insertar los 10 empleados de prueba (DML)
INSERT INTO `empleados` (`Nombre`, `ApellidoP`, `ApellidoM`, `Correo`, `Foto`) VALUES
('Juan', 'Pérez', 'Gómez', 'juan.perez@empresa.com', 'default.jpg'),
('María', 'García', 'López', 'maria.garcia@empresa.com', 'default.jpg'),
('Carlos', 'Rodríguez', 'Sánchez', 'carlos.rodriguez@empresa.com', 'default.jpg'),
('Ana', 'Martínez', 'Torres', 'ana.martinez@empresa.com', 'default.jpg'),
('Luis', 'Hernández', 'Díaz', 'luis.hernandez@empresa.com', 'default.jpg'),
('Sofia', 'Ramírez', 'Castillo', 'sofia.ramirez@empresa.com', 'default.jpg'),
('Jorge', 'Flores', 'Quispe', 'jorge.flores@empresa.com', 'default.jpg'),
('Elena', 'Morales', 'Vargas', 'elena.morales@empresa.com', 'default.jpg'),
('Pedro', 'Chávez', 'Mendoza', 'pedro.chavez@empresa.com', 'default.jpg'),
('Lucía', 'Rojas', 'Espinoza', 'lucia.rojas@empresa.com', 'default.jpg')
ON DUPLICATE KEY UPDATE
  `Nombre` = VALUES(`Nombre`),
  `ApellidoP` = VALUES(`ApellidoP`),
  `ApellidoM` = VALUES(`ApellidoM`),
  `Foto` = VALUES(`Foto`);

COMMIT;
