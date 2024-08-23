<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener detalles de la orden
    $stmt = $conexion->prepare("SELECT * FROM ordenes WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $orden = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($orden) {
        // Obtener detalles de los productos en la orden
        $stmt = $conexion->prepare("SELECT * FROM detalle_orden WHERE orden_id = :orden_id");
        $stmt->execute([':orden_id' => $id]);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Orden no encontrada.";
        exit();
    }
} else {
    echo "ID de orden no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Orden</title>
</head>
<body>
    <div class="container">
        <h2>Detalles de la Orden #<?php echo htmlspecialchars($orden['id']); ?></h2>
        <p>Cliente: <?php echo htmlspecialchars($orden['cliente']); ?></p>
        <p>Fecha: <?php echo htmlspecialchars($orden['fecha']); ?></p>
        <p>Total: $<?php echo htmlspecialchars($orden['total']); ?></p>
        <h3>Productos en la Orden</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <?php
                        // Obtener nombre del producto
                        $stmt = $conexion->prepare("SELECT nombre FROM productos WHERE id = :id");
                        $stmt->execute([':id' => $detalle['producto_id']]);
                        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                        <td>$<?php echo htmlspecialchars($detalle['subtotal']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="modulo_ordenes.php">Volver al módulo de órdenes</a>
    </div>
</body>
</html>
