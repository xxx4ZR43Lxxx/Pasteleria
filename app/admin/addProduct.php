<?php
require "../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $imagenReferencia = null;

    // Verificar si se subió una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaTemporal = $_FILES['imagen']['tmp_name'];
        $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = "../img/postres/" . $nombreArchivo;

        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $imagenReferencia = "/img/postres/" . $nombreArchivo; // Guardar la ruta completa de la imagen
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    // Verificar si estamos actualizando o insertando un nuevo producto
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Actualizar producto
        $id = $_POST['id'];

        // Si no se subió una nueva imagen, mantener la imagen existente
        if ($imagenReferencia === null) {
            // Obtener la imagen existente
            $sql = "SELECT imagen FROM productos WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $imagenReferencia = $row['imagen']; // Mantener la imagen original
            }
            $stmt->close();
        }

        // Actualizar los datos del producto con la ruta completa de la imagen
        $sql = "UPDATE productos SET nombre = ?, precio = ?, descripcion = ?, imagen = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $precio, $descripcion, $imagenReferencia, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insertar nuevo producto con la ruta completa de la imagen
        $sql = "INSERT INTO productos (nombre, precio, descripcion, imagen) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $precio, $descripcion, $imagenReferencia);
        $stmt->execute();
        $stmt->close();
    }

    // Responder con éxito
    echo json_encode(['success' => true]);
    $conn->close();
}
?>
