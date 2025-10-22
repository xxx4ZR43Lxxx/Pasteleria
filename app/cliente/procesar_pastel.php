<?php
session_start();
require "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sabor = $_POST['sabor'];
    $relleno = $_POST['relleno'];
    $cobertura = $_POST['cobertura'];
    $mensaje = $_POST['mensaje'];
    $direccion = $_POST['direccion-opcion'] === 'nueva' ? $_POST['nueva-direccion'] : 'predeterminada';
    $imagenReferencia = null;

    // Manejar la subida de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaTemporal = $_FILES['imagen']['tmp_name'];
        $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = "../img/uploads/" . $nombreArchivo;

        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $imagenReferencia = $nombreArchivo;
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    // Inserción en la tabla personalizacion_pastel
    $idPedido = 1; // Aquí deberías obtener el ID del pedido real, por ejemplo, después de crear el pedido.

    $sql = "INSERT INTO personalizacion_pastel (id_pedido, sabor, tipo, relleno, mensaje, imagen_referencia)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $idPedido, $sabor, $cobertura, $relleno, $mensaje, $imagenReferencia);

    if ($stmt->execute()) {
        echo "Personalización registrada correctamente.";
    } else {
        echo "Error al registrar la personalización.";
    }
}
?>
