<?php
session_start();

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['usuario_id']) ) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el producto
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: gestion_productos.php");
    exit();
} else {
    echo "ID de producto no proporcionado.";
    exit();
}
