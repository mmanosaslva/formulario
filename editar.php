<?php
include 'conexion.php';

function esEmailAscii($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    for ($i = 0; $i < strlen($email); $i++) {
        if (ord($email[$i]) > 127) return false;
    }
    return true;
}

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM perfiles WHERE id = ?");
$stmt->execute([$id]);
$perfil = $stmt->fetch();

if (!$perfil) {
    session_start();
    $_SESSION['mensaje'] = "Perfil no encontrado.";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: index.php");
    exit;
}

$errores = [];
$datos = [
    'nombre'   => $perfil['nombre'],
    'email'    => $perfil['email'],
    'telefono' => $perfil['telefono'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_map('trim', $_POST);

    // Validaciones
    if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'\-]{2,100}$/u', $datos['nombre'])) {
        $errores[] = "El nombre solo puede contener letras, espacios, guiones y apóstrofes.";
    }

    if (empty($datos['email'])) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!esEmailAscii($datos['email'])) {
        $errores[] = "El correo solo puede contener caracteres ASCII (sin acentos, ñ, emojis, etc.).";
    } elseif (strlen($datos['email']) > 100) {
        $errores[] = "El correo es demasiado largo (máx. 100 caracteres).";
    }

    if ($datos['telefono'] !== '' && !preg_match('/^[\+]?[0-9\s\-\(\)]{7,20}$/', $datos['telefono'])) {
        $errores[] = "El teléfono solo puede contener números, +, espacios, guiones y paréntesis.";
    }

    // Verificar duplicado (solo si el email cambió)
    if (empty($errores) && $datos['email'] !== $perfil['email']) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM perfiles WHERE email = ? AND id != ?");
        $stmt->execute([$datos['email'], $id]);
        if ($stmt->fetchColumn() > 0) {
            $errores[] = "Este correo electrónico ya está registrado por otro perfil.";
        }
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE perfiles 
                SET nombre = ?, email = ?, telefono = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $datos['telefono'] ?: null,
                $id
            ]);

            session_start();
            $_SESSION['mensaje'] = "Perfil actualizado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al actualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5">

            <h2 class="mb-4 text-center">Editar Perfil #<?= $id ?></h2>

            <?php if ($errores): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" required
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]{2,100}"
                           value="<?= htmlspecialchars($datos['nombre']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required
                           maxlength="100"
                           value="<?= htmlspecialchars($datos['email']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono (opcional)</label>
                    <input type="tel" name="telefono" class="form-control"
                           pattern="[\+]?[0-9\s\-\(\)]{7,20}"
                           value="<?= htmlspecialchars($datos['telefono']) ?>">
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>