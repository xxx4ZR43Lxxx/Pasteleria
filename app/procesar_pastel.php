<?php
session_start();
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sabor = $_POST['sabor'];
    $relleno = $_POST['relleno'];
    $cobertura = $_POST['cobertura'];
    $mensaje = $_POST['mensaje'];
    $imagenNombre = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagenNombre = "uploads/" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenNombre);
    }

    $sql = "INSERT INTO pedidos (sabor, relleno, cobertura, mensaje, imagen) 
            VALUES ('$sabor', '$relleno', '$cobertura', '$mensaje', '$imagenNombre')";

    if ($conn->query($sql) === TRUE) {
        echo "Pedido realizado con éxito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
