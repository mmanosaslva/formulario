<?php
// index.php: Página principal que lista todos los perfiles en una tabla HTML.
// Incluye botones para Crear, Editar y Eliminar. Muestra mensajes de éxito/error.

include 'conexion.php'; // Incluir la conexión a la BD

// Inicializar variable para mensajes
$mensaje = '';

// Verificar si hay un mensaje en la sesión (de redirecciones)
session_start();
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}

// Consulta para obtener todos los perfiles
try {
    $stmt = $pdo->prepare("SELECT * FROM perfiles ORDER BY creado_en DESC");
    $stmt->execute();
    $perfiles = $stmt->fetchAll();
} catch (PDOException $e) {
    $mensaje = "Error al listar perfiles: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perfiles</title>
    <!-- Estilos simples para una interfaz funcional -->
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .mensaje { color: green; font-weight: bold; }
        .error { color: red; }
        button { margin: 5px; }
    </style>
</head>
<body>
    <h1>Lista de Perfiles</h1>
    
    <!-- Mostrar mensaje de éxito/error -->
    <?php if ($mensaje): ?>
        <p class="<?php echo strpos($mensaje, 'Error') !== false ? 'error' : 'mensaje'; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    
    <!-- Botón para crear nuevo perfil -->
    <a href="crear.php"><button>Crear Nuevo Perfil</button></a>
    
    <!-- Tabla de perfiles -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Creado En</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($perfiles)): ?>
                <tr><td colspan="6">No hay perfiles registrados.</td></tr>
            <?php else: ?>
                <?php foreach ($perfiles as $perfil): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($perfil['id']); ?></td>
                        <td><?php echo htmlspecialchars($perfil['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($perfil['email']); ?></td>
                        <td><?php echo htmlspecialchars($perfil['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($perfil['creado_en']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $perfil['id']; ?>"><button>Editar</button></a>
                            <button onclick="confirmarEliminar(<?php echo $perfil['id']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Script para confirmación antes de eliminar -->
    <script>
        function confirmarEliminar(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este perfil?")) {
                window.location.href = "eliminar.php?id=" + id;
            }
        }
    </script>
</body>
</html>