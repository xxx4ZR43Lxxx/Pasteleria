<?php
$carrito_items = 0;

// Verifica si el usuario está registrado
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Consulta SQL para contar la cantidad total de productos en el carrito
    $sql = "SELECT SUM(cantidad) AS total_productos FROM carrito WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Almacena el total de productos en el carrito
    $carrito_items = $row['total_productos'] ?? 0;
} else {
    // Si no hay sesión de usuario, puedes manejar el carrito por cookies o mostrar 0
    $carrito_items = 0; 
}
?>