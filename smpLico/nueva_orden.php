<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = $_SESSION['nombre_usuario']; // Usar el nombre de usuario como cliente
    $productos = $_POST['productos']; // Array con IDs de productos y cantidades
    $total = 0;

    if (!empty($productos)) {
        // Iniciar una transacción
        $conexion->beginTransaction();

        try {
            // Insertar la orden
            $stmt = $conexion->prepare("INSERT INTO ordenes (cliente, fecha, total) VALUES (:cliente, NOW(), :total)");
            $stmt->execute([
                ':cliente' => $cliente,
                ':total' => $total
            ]);
            $orden_id = $conexion->lastInsertId();

            // Insertar detalles de la orden
            foreach ($productos as $producto_id => $cantidad) {
                // Obtener el precio del producto
                $stmt = $conexion->prepare("SELECT precio FROM productos WHERE id = :id");
                $stmt->execute([':id' => $producto_id]);
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($producto) {
                    $precio = $producto['precio'];
                    $subtotal = $precio * $cantidad;
                    $total += $subtotal;

                    // Insertar detalle de la orden
                    $stmt = $conexion->prepare("INSERT INTO detalle_orden (orden_id, producto_id, cantidad, subtotal) VALUES (:orden_id, :producto_id, :cantidad, :subtotal)");
                    $stmt->execute([
                        ':orden_id' => $orden_id,
                        ':producto_id' => $producto_id,
                        ':cantidad' => $cantidad,
                        ':subtotal' => $subtotal
                    ]);
                }
            }

            // Actualizar el total de la orden
            $stmt = $conexion->prepare("UPDATE ordenes SET total = :total WHERE id = :id");
            $stmt->execute([
                ':total' => $total,
                ':id' => $orden_id
            ]);

            // Confirmar la transacción
            $conexion->commit();

            header("Location: modulo_ordenes.php");
            exit();
        } catch (Exception $e) {
            // Rollback en caso de error
            $conexion->rollBack();
            $error = "Hubo un problema al crear la orden: " . $e->getMessage();
        }
    } else {
        $error = "No se seleccionaron productos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Orden</title>
</head>
<body>
    <div class="container">
        <h2>Crear Nueva Orden</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="nueva_orden.php" method="POST">
            <h3>Selecciona los productos</h3>
            <?php
            $stmt = $conexion->prepare("SELECT * FROM productos");
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($productos as $producto): ?>
                <div>
                    <label>
                        <input type="checkbox" name="productos[<?php echo htmlspecialchars($producto['id']); ?>]" value="1">
                        <?php echo htmlspecialchars($producto['nombre']); ?> - $<?php echo htmlspecialchars($producto['precio']); ?>
                    </label>
                    <input type="number" name="cantidad[<?php echo htmlspecialchars($producto['id']); ?>]" placeholder="Cantidad" min="1" value="1">
                </div>
            <?php endforeach; ?>
            <button type="submit">Crear Orden</button>
        </form>
        <a href="modulo_ordenes.php">Volver al módulo de órdenes</a>
    </div>
</body>
</html>
