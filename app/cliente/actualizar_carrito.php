<?php
session_start();
include('../config.php'); // Incluye la conexión a la base de datos

// Verifica si los datos necesarios están presentes
if (isset($_POST['id_producto'], $_POST['accion'])) {
    $id_producto = intval($_POST['id_producto']);
    $accion = $_POST['accion'];
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que el usuario esté autenticado

    // Obtiene la cantidad actual del producto en el carrito
    $sql = "SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cantidad_actual = $row['cantidad'];

        // Calcula la nueva cantidad
        if ($accion === 'sumar') {
            $nueva_cantidad = $cantidad_actual + 1;
        } elseif ($accion === 'restar' && $cantidad_actual > 1) {
            $nueva_cantidad = $cantidad_actual - 1;
        } else {
            $nueva_cantidad = $cantidad_actual;
        }

        // Actualiza la cantidad en la base de datos
        $update_sql = "UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $nueva_cantidad, $id_usuario, $id_producto);
        $update_stmt->execute();

        $update_stmt->close();
    }

    $stmt->close();
}

// Redirige de vuelta al carrito
header("Location: carrito.php");
exit;
?>
