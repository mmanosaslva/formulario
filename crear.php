<?php

include 'conexion.php'; // Incluir la conexión a la BD

// Función para validar que el email solo contenga caracteres ASCII
function esEmailAscii($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    for ($i = 0; $i < strlen($email); $i++) {
        if (ord($email[$i]) > 127) return false;
    }
    return true;
}

$nombre = $email = $telefono = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $email    = trim($_POST['email']    ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    // ──────────────────────────────────────────────
    // VALIDACIONES
    // ──────────────────────────────────────────────

    // Nombre
    if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'\-]{2,100}$/u', $nombre)) {
        $errores[] = "El nombre solo puede contener letras, espacios, guiones y apóstrofes.";
    }

    // Email - dos condiciones obligatorias
    if (empty($email)) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!esEmailAscii($email)) {
        $errores[] = "El correo electrónico solo puede contener caracteres ASCII (sin acentos, ñ, caracteres especiales ni emojis).";
    } elseif (strlen($email) > 100) {
        $errores[] = "El correo electrónico es demasiado largo (máximo 100 caracteres).";
    }

    // Teléfono (opcional)
    if ($telefono !== '' && !preg_match('/^[\+]?[0-9\s\-\(\)]{7,20}$/', $telefono)) {
        $errores[] = "El teléfono solo puede contener números, espacios, guiones, paréntesis y el signo +.";
    }

    // Verificar duplicado de email
    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM perfiles WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            $errores[] = "Este correo electrónico ya está registrado.";
        }
    }

    // Si no hay errores → insertar
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO perfiles (nombre, email, telefono) VALUES (:nombre, :email, :telefono)");
            $stmt->execute([
                ':nombre'   => $nombre,
                ':email'    => $email,
                ':telefono' => $telefono ?: null
            ]);

            session_start();
            $_SESSION['mensaje'] = "Perfil creado exitosamente.";
            $_SESSION['tipo_mensaje'] = "success";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al guardar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Perfil</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container { max-width: 500px; margin: 40px auto; }
    </style>
</head>
<body class="bg-light">

<div class="container form-container">

    <h2 class="mb-4 text-center">Crear Nuevo Perfil</h2>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre"
                   value="<?= htmlspecialchars($nombre) ?>" required
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]{2,100}"
                   title="Solo letras, espacios, guiones y apóstrofes">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= htmlspecialchars($email) ?>" required
                   maxlength="100">
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono (opcional)</label>
            <input type="tel" class="form-control" id="telefono" name="telefono"
                   value="<?= htmlspecialchars($telefono) ?>"
                   pattern="[\+]?[0-9\s\-\(\)]{7,20}"
                   title="Solo números, +, espacios, guiones y paréntesis">
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear Perfil</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS (para alertas y componentes interactivos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>