<?php
session_start();
include('../config.php'); // Incluye la conexión a la base de datos

// Verifica si el producto está especificado
if (isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que el usuario esté autenticado

    // Elimina el producto del carrito
    $sql = "DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $stmt->close();
}

// Redirige de vuelta al carrito
header("Location: carrito.php");
exit;
?>
