<?php
session_start();

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    if (!empty($nombre) && !empty($precio) && !empty($cantidad)) {
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, cantidad) VALUES (:nombre, :descripcion, :precio, :cantidad)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':cantidad' => $cantidad
        ]);

        header("Location: gestion_productos.php");
        exit();
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
    <title>Agregar Producto</title>
</head>
<body>
    <div class="container">
        <h2>Agregar Nuevo Producto</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="agregar_producto.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="text" name="descripcion" placeholder="Descripción del producto">
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>
            <input type="number" name="cantidad" placeholder="Cantidad" required>
            <button type="submit">Agregar Producto</button>
        </form>
        <a href="gestion_productos.php">Volver a la gestión de productos</a>
    </div>
</body>
</html>
