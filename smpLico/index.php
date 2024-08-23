<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el nombre de usuario y el tipo de usuario desde la sesión
$nombre_usuario = $_SESSION['nombre_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <style>
        /* Simple styling for the main panel */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .container {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .container a {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .container a:hover {
            background-color: #005f7f;
        }
        .logout {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 15px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h1>
    </div>
    <div class="container">
        <h2>Panel Principal</h2>
        <p>Selecciona una opción:</p>
        <a href="gestion_productos.php">Gestión de Productos</a>
        <a href="modulo_ordenes.php">Módulo de Órdenes</a>
        <a href="generador_recibo.php">Generador de Recibo</a>
        <a href="logout.php" class="logout">Cerrar Sesión</a>
    </div>
</body>
</html>
