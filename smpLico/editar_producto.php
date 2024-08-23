<?php
session_start();

// Verificar si el usuario está autenticado y es admin
//if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {

if (!isset($_SESSION['usuario_id']) ) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener el producto actual
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];

        if (!empty($nombre) && !empty($precio) && !empty($cantidad)) {
            $stmt = $conexion->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, cantidad = :cantidad WHERE id = :id");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':cantidad' => $cantidad,
                ':id' => $id
            ]);

            header("Location: gestion_productos.php");
            exit();
        } else {
            $error = "Por favor, completa todos los campos.";
        }
    }
} else {
    echo "ID de producto no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>
<body>
    <div class="container">
        <h2>Editar Producto</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="editar_producto.php?id=<?php echo htmlspecialchars($producto['id']); ?>" method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre del producto" required>
            <input type="text" name="descripcion" value="<?php echo htmlspecialchars($producto['descripcion']); ?>" placeholder="Descripción del producto">
            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" placeholder="Precio" required>
            <input type="number" name="cantidad" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" placeholder="Cantidad" required>
            <button type="submit">Actualizar Producto</button>
        </form>
        <a href="gestion_productos.php">Volver a la gestión de productos</a>
    </div>
</body>
</html>
