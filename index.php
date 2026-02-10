<?php
// index.php - Listado de perfiles con Bootstrap

include 'conexion.php';

session_start();

// Manejo de mensajes de sesión
$mensaje = '';
$tipo_mensaje = 'info';

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? 'info';
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
}

// Obtener todos los perfiles
try {
    $stmt = $pdo->query("SELECT * FROM perfiles ORDER BY creado_en DESC");
    $perfiles = $stmt->fetchAll();
} catch (PDOException $e) {
    $mensaje = "Error al cargar los perfiles: " . $e->getMessage();
    $tipo_mensaje = 'danger';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Perfiles</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-main {
            max-width: 1200px;
        }
    </style>
</head>
<body>

<div class="container container-main mt-5">

    <!-- Título y botón nuevo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestión de Perfiles</h1>
        <a href="crear.php" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Nuevo Perfil
        </a>
    </div>

    <!-- Mensaje de éxito / error -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla responsive -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Creado el</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($perfiles)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 fw-bold">
                            No hay perfiles registrados todavía.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($perfiles as $perfil): ?>
                        <tr>
                            <td><?= htmlspecialchars($perfil['id']) ?></td>
                            <td><?= htmlspecialchars($perfil['nombre']) ?></td>
                            <td><?= htmlspecialchars($perfil['email']) ?></td>
                            <td><?= htmlspecialchars($perfil['telefono'] ?: '—') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($perfil['creado_en'])) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $perfil['id'] ?>" 
                                   class="btn btn-sm btn-primary me-1">
                                    Editar
                                </a>
                                <button class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminar(<?= $perfil['id'] ?>)">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Bootstrap JS (necesario para alertas dismissibles y otros componentes) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Iconos Bootstrap (opcional, pero queda muy bien) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script>
function confirmarEliminar(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este perfil?\nEsta acción no se puede deshacer.")) {
        window.location.href = "eliminar.php?id=" + id;
    }
}
</script>

</body>
</html>