<?php
session_start();
require "../config.php"; // Asegúrate de tener la conexión a la base de datos

// Verificar si 'total' está presente y no está vacío
if (!isset($_POST['total']) || empty($_POST['total'])) {
    echo "El total es obligatorio.";
    exit;  // Detener el proceso si el total no está presente
}

$total = $_POST['total']; // Asumiendo que el total se está calculando en el frontend y enviando

// Datos del formulario (sabor, relleno, etc.)
$sabor = $_POST['sabor'];
$relleno = $_POST['relleno'];
$cobertura = $_POST['cobertura'];
$tamano = $_POST['tamano'];
$mensaje = $_POST['mensaje'];
$tipo_envio = $_POST['tipo_envio'];
$metodo_pago = $_POST['metodo_pago'];
$direccion_predeterminada = $_POST['direccion_predeterminada'] ?? null;
$nueva_direccion = $_POST['nueva_direccion'] ?? null;
$tipo_direccion = $_POST['tipo_direccion'];
$numero_tarjeta = $_POST['numero_tarjeta'] ?? null;
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
$cvv = $_POST['cvv'] ?? null;

// Manejar la subida de la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $rutaTemporal = $_FILES['imagen']['tmp_name'];
    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
    $rutaDestino = "../img/uploads/" . $nombreArchivo;

    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
        $imagenReferencia = $nombreArchivo; // Guardar el nombre del archivo subido
    } else {
        echo "Error al subir la imagen.";
        exit;
    }
} else {
    $imagenReferencia = null; // Si no hay imagen, se dejará como null
}

// Obtener la dirección de entrega (si es nueva o predeterminada)
$direccion_entrega = ($tipo_direccion === 'nueva') ? $nueva_direccion : $direccion_predeterminada;

// Insertar el pedido en la base de datos
$sql = "INSERT INTO pedidos (id_usuario, total, metodo_pago, direccion_entrega) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("idss", $_SESSION['id_usuario'], $total, $metodo_pago, $direccion_entrega);
$stmt->execute();
$id_pedido = $stmt->insert_id; // Obtener el ID del nuevo pedido
$stmt->close();

// Inserta los detalles del pastel personalizado
// Assuming you want to bind the additional parameter `$imagenReferencia`
$sql = "INSERT INTO personalizacion_pastel (id_pedido, sabor, relleno, mensaje, tamano_porciones, imagen_referencia) 
        VALUES (?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters (note the additional 's' for the image reference)
$stmt->bind_param("isssss", $id_pedido, $sabor, $relleno, $mensaje, $tamano, $imagenReferencia);


// Execute the query
$stmt->execute();
$stmt->close();




// Redirigir al usuario a una página de confirmación o donde desees
header("Location: inicio.php");
exit();
?>
