<?php
// Inicia sesión
session_start();

// Incluye el archivo de configuración de la base de datos
require 'config.php';

// Verifica si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica que ambos campos estén completos
    if (!empty($_POST['correo']) && !empty($_POST['contrasena'])) {
        $correo = mysqli_real_escape_string($conn, $_POST['correo']);
        $contrasena = $_POST['contrasena']; // No escapar aquí, ya que será comparada con `password_verify`

        // Consulta para obtener el usuario con el correo ingresado
        $sql = "SELECT id, contrasena, tipo_usuario FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hash = $user['contrasena'];

            // Verifica la contraseña ingresada con el hash almacenado
            if (password_verify($contrasena, $hash)) {
                // Contraseña válida: guarda los datos del usuario en la sesión
                $_SESSION['id_usuario'] = $user['id']; // Usa 'user_id' para la sesión
                $_SESSION['user_type'] = $user['tipo_usuario'];

                // Redirige según el tipo de usuario
                if ($user['tipo_usuario'] === 'administrador') {
                    header("Location: /admin/dashboard.php");
                } else {
                    header("Location: /cliente/inicio.php");
                }
                $conn->close();
                exit();
            } else {
                // Contraseña incorrecta
                $error = "Correo o contraseña incorrectos.";
                header("Location: index.php?error=" . urlencode($error));
                $conn->close();
                exit();
            }
        } else {
            // Usuario no encontrado
            $error = "Correo o contraseña incorrectos.";
            header("Location: index.php?error=" . urlencode($error));
            $conn->close();
            exit();
        }
    } else {
        // Campos vacíos
        $error = "Por favor, completa todos los campos.";
        header("Location: index.php?error=" . urlencode($error));
        $conn->close();
        exit();
    }
} else {
    // Método no permitido
    $error = "Método no permitido.";
    header("Location: index.php?error=" . urlencode($error));
    $conn->close();
    exit();
}
?>
