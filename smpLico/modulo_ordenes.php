<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

// Obtener órdenes de la base de datos
$stmt = $conexion->prepare("SELECT * FROM ordenes ORDER BY fecha DESC");
$stmt->execute();
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Órdenes</title>
    <style>
        /* Simple styling for the orders module page */
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
        <h2>Módulo de Órdenes</h2>
        <a href="nueva_orden.php">Crear Nueva Orden</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($orden['id']); ?></td>
                        <td><?php echo htmlspecialchars($orden['cliente']); ?></td>
                        <td><?php echo htmlspecialchars($orden['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($orden['total']); ?></td>
                        <td><a href="detalle_orden.php?id=<?php echo htmlspecialchars($orden['id']); ?>">Ver Detalles</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
