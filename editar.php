<?php
// editar.php: Formulario para editar un perfil existente.
// Recibe ID por GET, prellena datos, actualiza con sentencias preparadas y redirige.

include 'conexion.php'; // Incluir la conexión a la BD

// Verificar que se reciba un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = (int)$_GET['id'];

// Inicializar variables para el formulario y errores
$nombre = $email = $telefono = '';
$errores = [];

// Obtener datos actuales del perfil
try {
    $stmt = $pdo->prepare("SELECT * FROM perfiles WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $perfil = $stmt->fetch();
    
    if (!$perfil) {
        session_start();
        $_SESSION['mensaje'] = "Perfil no encontrado.";
        header("Location: index.php");
        exit;
    }
    
    // Prellenar variables
    $nombre = $perfil['nombre'];
    $email = $perfil['email'];
    $telefono = $perfil['telefono'];
} catch (PDOException $e) {
    $errores[] = "Error al obtener perfil: " . $e->getMessage();
}

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
    $telefono = filter_var($telefono, FILTER_SANITIZE_STRING);
    
    // Si no hay errores, actualizar en la BD
    if (empty($errores)) {
        try {
            // Preparar la sentencia SQL
            $stmt = $pdo->prepare("UPDATE perfiles SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id");
            
            // Bind parameters
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':id', $id);
            
            // Ejecutar
            $stmt->execute();
            
            // Iniciar sesión para mensaje y redirigir
            session_start();
            $_SESSION['mensaje'] = "Perfil actualizado exitosamente.";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al actualizar perfil: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Editar Perfil ID: <?php echo $id; ?></h1>
    
    <!-- Mostrar errores -->
    <?php if (!empty($errores)): ?>
        <ul class="error">
            <?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <!-- Formulario prellenado -->
    <form method="POST">
        <label>Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required></label><br><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required></label><br><br>
        <label>Teléfono: <input type="text" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>"></label><br><br>
        <button type="submit">Actualizar</button>
        <a href="index.php"><button type="button">Cancelar</button></a>
    </form>
</body>
</html>