-- database.sql: Script SQL para crear la base de datos y la tabla.

CREATE DATABASE IF NOT EXISTS crud_php CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE crud_php;

CREATE TABLE IF NOT EXISTS perfiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);