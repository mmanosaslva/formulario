<?php
// Formulario para crear un nuevo perfil.
// Valida datos, inserta usando sentencias preparadas y redirige con mensaje.

include 'conexion.php'; // Incluir la conexión a la BD

// Inicializar variables para el formulario y errores
$nombre = $email = $telefono = '';
$errores = [];

// Procesar el formulario si se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar inputs
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    
    // Validaciones básicas
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email es obligatorio y debe ser válido.";
    }
    // Teléfono es opcional, pero sanitizar si se proporciona
    $telefono = filter_var($telefono, FILTER_SANITIZE_STRING);
    
    // Si no hay errores, insertar en la BD
    if (empty($errores)) {
        try {
            // Preparar la sentencia SQL
            $stmt = $pdo->prepare("INSERT INTO perfiles (nombre, email, telefono) VALUES (:nombre, :email, :telefono)");
            
            // Bind parameters (prevenir SQL injection)
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            
            // Ejecutar
            $stmt->execute();
            
            // Iniciar sesión para mensaje y redirigir
            session_start();
            $_SESSION['mensaje'] = "Perfil creado exitosamente.";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al crear perfil: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Perfil</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Crear Nuevo Perfil</h1>
    
    <!-- Mostrar errores -->
    <?php if (!empty($errores)): ?>
        <ul class="error">
            <?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <!-- Formulario -->
    <form method="POST">
        <label>Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required></label><br><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required></label><br><br>
        <label>Teléfono: <input type="text" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>"></label><br><br>
        <button type="submit">Crear</button>
        <a href="index.php"><button type="button">Cancelar</button></a>
    </form>
</body>
</html>