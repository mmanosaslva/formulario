<?php
// eliminar.php: Elimina un perfil por ID recibido via GET.
// Usa sentencia preparada, redirige con mensaje de confirmación.

include 'conexion.php'; // Incluir la conexión a la BD

// Verificar que se reciba un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = (int)$_GET['id'];

// Eliminar el perfil
try {
    // Preparar la sentencia SQL
    $stmt = $pdo->prepare("DELETE FROM perfiles WHERE id = :id");
    
    // Bind parameter
    $stmt->bindParam(':id', $id);
    
    // Ejecutar
    $stmt->execute();
    
    // Iniciar sesión para mensaje y redirigir
    session_start();
    $_SESSION['mensaje'] = "Perfil eliminado exitosamente.";
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    session_start();
    $_SESSION['mensaje'] = "Error al eliminar perfil: " . $e->getMessage();
    header("Location: index.php");
    exit;
}
?>  