<?php
// Archivo para la conexión a la BD usando PDO

// Configuración BD
$host = 'localhost'; // Servidor de la BD
$dbname = 'crud_php'; // Nombre de la BD
$username = 'root'; // Usuario de la BD (por defecto en XAMPP es root)
$password = ''; // Contraseña de la BD (por defecto en XAMPP es vacía)

// Intentar conectar a la base de datos usando PDO
try {
    // Crear la conexión PDO con manejo de errores y configuración UTF-8
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar el modo de fetch por defecto a asociativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Configuración para UTF-8 (evitar problemas con caracteres especiales)
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    // Manejo de errores: Mostrar mensaje amigable y detener ejecución
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>