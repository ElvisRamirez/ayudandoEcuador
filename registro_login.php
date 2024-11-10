<?php
session_start();
include 'db.php'; // Asegúrate de tener una conexión a la base de datos

$mensaje = ''; // Variable para almacenar mensajes de error o éxito
$tipo_mensaje = ''; // Variable para almacenar el tipo de mensaje (success, danger)

// Verificar si se ha enviado el formulario de inicio de sesión
if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];

    try {
        $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasenia, $usuario['contrasenia'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['mensaje'] = 'Inicio de sesión exitoso.';
            $_SESSION['tipo_mensaje'] = 'success';
            header("Location: dashboard.php");
            exit();
        } else {
            $mensaje = 'Correo o contraseña incorrectos.';
            $tipo_mensaje = 'danger';
        }
    } catch (PDOException $e) {
        $mensaje = 'Error en el inicio de sesión: ' . $e->getMessage();
        $tipo_mensaje = 'danger';
    }
}

// Verificar si se ha enviado el formulario de registro
if (isset($_POST['register'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];
    $contrasenia_confirm = $_POST['contrasenia_confirm'];

    if ($contrasenia !== $contrasenia_confirm) {
        $mensaje = 'Las contraseñas no coinciden.';
        $tipo_mensaje = 'danger';
    } else {
        $hashed_password = password_hash($contrasenia, PASSWORD_DEFAULT);
        
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO Usuarios (Nombre, Apellido, correo, contrasenia) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $correo, $hashed_password]);
            
            $id_usuario = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO Datos (id_usuario) VALUES (?)");
            $stmt->execute([$id_usuario]);
            
            $conn->commit();

            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['mensaje'] = 'Registro exitoso. Bienvenido a tu dashboard.';
            $_SESSION['tipo_mensaje'] = 'success';
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $conn->rollBack();
            if ($e->getCode() == 23000) {
                $mensaje = 'El correo ya está registrado. Por favor, intenta con otro correo.';
            } else {
                $mensaje = 'Error al registrar: ' . $e->getMessage();
            }
            $tipo_mensaje = 'danger';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Aquí va tu formulario de inicio de sesión -->
        <form action="" method="post">
            <h2>Iniciar Sesión</h2>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contrasenia" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <!-- Aquí va tu formulario de registro -->
        <form action="" method="post" class="mt-5">
            <h2>Registrarse</h2>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="correo_reg" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_reg" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contrasenia_reg" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasenia_reg" name="contrasenia" required>
            </div>
            <div class="mb-3">
                <label for="contrasenia_confirm" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="contrasenia_confirm" name="contrasenia_confirm" required>
            </div>
            <button type="submit" name="register" class="btn btn-success">Registrarse</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>