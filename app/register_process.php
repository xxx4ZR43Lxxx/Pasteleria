<?php
// Inicia la sesión
session_start();

// Incluye el archivo de configuración de la base de datos
require 'config.php';

// Inicializa variables
$error = '';
$success = '';

// Verifica si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica que todos los campos obligatorios están presentes
    if (
        isset($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['telefono'], $_POST['password'], $_POST['confirm-password']) &&
        !empty($_POST['nombre']) &&
        !empty($_POST['apellido']) &&
        !empty($_POST['email']) &&
        !empty($_POST['telefono']) &&
        !empty($_POST['password']) &&
        !empty($_POST['confirm-password'])
    ) {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
        $correo = mysqli_real_escape_string($conn, $_POST['email']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $direccion = isset($_POST['direccion']) ? mysqli_real_escape_string($conn, $_POST['direccion']) : null;
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);

        // Valida que las contraseñas coincidan
        if ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
            header("Location: index.php?error=" . urlencode($error));
            exit();
        }

        // Verifica si el correo ya está registrado
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "El correo ya está registrado.";
            header("Location: index.php?error=" . urlencode($error));
            exit();
        }

        // Cifrado de contraseña (puedes usar password_hash)
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Inserta el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, numero_contacto, direccion, tipo_usuario) 
                VALUES (?, ?, ?, ?, ?, 'cliente')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $correo, $password_hashed, $telefono, $direccion);

        if ($stmt->execute()) {
            $success = "Usuario registrado exitosamente.";
            header("Location: index.php?success=" . urlencode($success));
        } else {
            $error = "Error al registrar al usuario.";
            header("Location: index.php?error=" . urlencode($error));
        }

        $stmt->close();
    } else {
        $error = "Por favor, completa todos los campos obligatorios.";
        header("Location: index.php?error=" . urlencode($error));
    }
} else {
    $error = "Método no permitido.";
    header("Location: index.php?error=" . urlencode($error));
}

// Cierra la conexión
$conn->close();
?>
