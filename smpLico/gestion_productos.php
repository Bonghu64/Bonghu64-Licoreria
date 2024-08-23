<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id']) ) {
    header("Location: login.php");
    exit();
}

require 'db.php';

// Obtener productos de la base de datos
$stmt = $conexion->prepare("SELECT * FROM productos");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <style>
        /* Simple styling for the product management page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #005f7f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestión de Productos</h2>
        <a href="agregar_producto.php">Agregar Nuevo Producto</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['id']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo htmlspecialchars($producto['id']); ?>">Editar</a>
                            <a href="eliminar_producto.php?id=<?php echo htmlspecialchars($producto['id']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
