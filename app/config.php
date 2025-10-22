<?php
// Archivo config.php
$servername = "db";
$username = "user";
$password = "password";
$database = "vainiya_bakery";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>