<?php
session_start();
require "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    $id_usuario = $_SESSION['id_usuario'] ?? null; // ID del usuario autenticado

    if ($cantidad < 1) {
        header("Location: detalle_producto.php?id=$id_producto&error=cantidad_invalida");
        exit();
    }

    if ($accion === 'carrito') {
        // Añadir al carrito
        $sql = "SELECT id FROM carrito WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si el producto ya está en el carrito, actualiza la cantidad
            $sql = "UPDATE carrito SET cantidad = cantidad + ?, fecha_agregado = NOW() WHERE id_usuario = ? AND id_producto = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $cantidad, $id_usuario, $id_producto);
        } else {
            // Si no está, inserta un nuevo registro
            $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $id_usuario, $id_producto, $cantidad);
        }
        $stmt->execute();
        header("Location: carrito.php");
        exit();
    } elseif ($accion === 'comprar_ahora') {
        // Obtener el precio y la cantidad
        $precio_producto = floatval($_POST['precio_producto']); // Precio del producto
        $cantidad = intval($_POST['cantidad']); // Cantidad del producto
        $total = $precio_producto * $cantidad; // Calcular el total
    
        // Crear un pedido directamente
        $sql = "INSERT INTO pedidos (id_usuario, total, estado) VALUES (?, ?, 'pendiente')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $id_usuario, $total); // Enviar el total al segundo parámetro
        $stmt->execute();
        $id_pedido = $stmt->insert_id;
    
        // Enviar los datos por POST a la página comprar_ahora.php
        // Crear un formulario oculto para enviar los datos por POST
        echo '
        <form id="form_comprar" action="comprar_ahora.php" method="POST">
            <input type="hidden" name="id_producto" value="' . $id_producto . '">
            <input type="hidden" name="precio_producto" value="' . $_POST['precio_producto'] . '">
            <input type="hidden" name="cantidad" value="' . $cantidad . '">
            <input type="hidden" name="accion" value="comprar_ahora">
            <input type="hidden" name="id_pedido" value="' . $id_pedido . '"> <!-- Pasar el id_pedido -->
        </form>
        <script>
            // Enviar el formulario automáticamente
            document.getElementById("form_comprar").submit();
        </script>
        ';
        exit();
    }
}    
?>
