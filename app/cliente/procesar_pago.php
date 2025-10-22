<?php
session_start();
require "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $metodo_pago = $_POST['metodo_pago'];
    
    // Verificar si se ha seleccionado dirección nueva o predeterminada
    if ($_POST['tipo_direccion'] === 'nueva') {
        $direccion = $_POST['nueva_direccion'];
    } else {
        $direccion = $_POST['direccion_predeterminada'];
    }

    // Calcular el total del carrito
    $sql = "SELECT SUM(p.precio * c.cantidad) AS total 
            FROM carrito c 
            JOIN productos p ON c.id_producto = p.id
            WHERE c.id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'];

    // Crear un nuevo pedido
    $sql = "INSERT INTO pedidos (id_usuario, total, estado, metodo_pago, direccion_entrega) 
            VALUES (?, ?, 'pendiente', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $id_usuario, $total, $metodo_pago, $direccion);
    $stmt->execute();
    $id_pedido = $stmt->insert_id;

    // Insertar detalles del pedido
    $sql = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, subtotal) 
            SELECT ?, c.id_producto, c.cantidad, (p.precio * c.cantidad) 
            FROM carrito c 
            JOIN productos p ON c.id_producto = p.id
            WHERE c.id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_pedido, $id_usuario);
    $stmt->execute();

    // Limpiar el carrito
    $sql = "DELETE FROM carrito WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    header("Location: inicio.php");
    exit();
}
?>
