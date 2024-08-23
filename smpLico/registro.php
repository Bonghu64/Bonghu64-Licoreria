<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $tipo_usuario = $_POST['tipo_usuario'];

    if (!empty($nombre_usuario) && !empty($email) && !empty($contraseña) && !empty($tipo_usuario)) {
        // Verifica si el nombre de usuario o el correo ya están registrados
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? OR email = ? LIMIT 1");
        $stmt->execute([$nombre_usuario, $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            // Hashear la contraseña
            $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);

            // Insertar nuevo usuario
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contraseña, tipo_usuario, email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre_usuario, $contraseña_hash, $tipo_usuario, $email]);

            header("Location: login.php?registro=exitoso");
            exit();
        } else {
            $error = "El nombre de usuario o el correo electrónico ya están registrados.";
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .registro-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .registro-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registro-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .registro-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .registro-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .registro-container button:hover {
            background-color: #0069d9;
        }
        .registro-container p {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <h2>Registrarse</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <select name="tipo_usuario" required>
                <option value="cliente">Cliente</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
