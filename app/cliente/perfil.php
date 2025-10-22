<?php
session_start();
require "../config.php";
require "carrito_cont.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");  // Redirige al login si no está autenticado
    exit();
}

// Obtiene el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Conexión a la base de datos y obtención de datos del usuario
$sql = "SELECT nombre, correo, numero_contacto, direccion FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Error: Usuario no encontrado.";
    exit();
}

// Actualización de datos si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $numero_contacto = $_POST['numero_contacto'];
    $direccion = $_POST['direccion'];

    $update_sql = "UPDATE usuarios SET nombre = ?, correo = ?, numero_contacto = ?, direccion = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $nombre, $correo, $numero_contacto, $direccion, $id_usuario);

    if ($update_stmt->execute()) {
        echo "<script>alert('Datos actualizados correctamente.'); window.location.href='perfil.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar los datos.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Vainiya! Bakery</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-bottom: 50px;
        }
        .auth-container {
            width: 100%;
            max-width: 600px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            font-size: 1.2rem; /* Tamaño del texto del label */
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 1rem; /* Más espacio dentro del input */
            font-size: 1.1rem; /* Tamaño del texto del input */
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn-primary, .btn-secondary {
            display: block;
            width: 100%;
            padding: 0.75rem;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-primary {
            background-color: #ff69b4;
        }
        .btn-primary:hover {
            background-color: #ff1493;
        }
        .btn-secondary {
            background-color: #ff69b4;
        }
        .btn-secondary:hover {
            background-color: #ff1493;
        }
        h2 {
            text-align: center;
            margin-top: 50px;
            color: #333;
        }
    </style>
        <style>
        /* Estilos para el menú desplegable */
.dropdown {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0.5rem 0;
    min-width: 150px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.dropdown-menu .dropdown-link {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background 0.3s ease;
}

.dropdown-menu .dropdown-link:hover {
    background: #ff69b4;
    color: #fff;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

    </style>
</head>
<body>
    
<header>
<nav class="navbar">
    <div class="logo">
        
            <img class="logo_img" src="/img/logo.png" alt="Vainiya! Bakery">
            <a href="inicio.php" style="text-decoration: none; color: inherit;">
            <h1>Vainiya! Bakery</h1>
        </a>
    </div>
    <ul id="nav-menu">
        <li><a href="sobre-nosotros.php" class="nav-link">Sobre Nosotros</a></li>
        <li><a href="disenar_pastel.php" class="nav-link btn-requiere-sesion">Personalizar Pastel</a></li>
        <li class="logged-in"><a href="#pedidos" class="nav-link">Pedidos</a></li>

        <!-- Menú desplegable para Perfil -->
        <li class="dropdown logged-in">
            <a class="nav-link">Perfil <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="perfil.php" class="dropdown-link">Información</a></li>
                <li><a href="logout.php" class="dropdown-link">Cerrar Sesión</a></li>
            </ul>
        </li>

        <!-- Ícono del carrito con cantidad -->
        <li>
            <a href="carrito.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <!-- Muestra la cantidad de productos en el carrito -->
                <?php echo $carrito_items; ?>
            </a>
        </li>
    </ul>
</nav>

</header>
<h2>Perfil</h2>
<main>
    <section class="auth-container">
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="numero_contacto">Número de Contacto</label>
                <input type="text" id="numero_contacto" name="numero_contacto" value="<?php echo htmlspecialchars($user['numero_contacto']); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($user['direccion']); ?>" disabled>
            </div>

            <button type="button" id="editar" class="btn-primary">Editar</button>
            <button type="submit" id="actualizar" name="actualizar" class="btn-secondary" style="display: none;">Actualizar</button>
        </form>
    </section>
</main>


<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>Vainiya! Bakery</h3>
            <p>Endulzando momentos especiales con nuestras deliciosas creaciones.</p>
        </div>
        <div class="footer-section">
            <h3>Síguenos</h3>
            <div class="social-links">
                <a href="https://www.instagram.com/vainiyabakery/profilecard/?igsh=MW1keWZwcHM2dTlodw%3D%3D"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@vainiyabakery?_t=8k4gBw9kKnt&_r=1"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
        <div class="footer-section">
            <h3>Contacto</h3>
            <p><i class="fas fa-map-marker-alt"></i> Tulancingo de Bravo; Hgo.</p>
            <p><i class="fas fa-phone"></i> Teléfono: (52) 775-105-96-89</p>
            <p><i class="fas fa-envelope"></i> Email: info@vainiya.com</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Vainiya! Bakery. Todos los derechos reservados.</p>
    </div>
</footer>



<script>
    const editarBtn = document.getElementById('editar');
    const actualizarBtn = document.getElementById('actualizar');
    const inputs = document.querySelectorAll('input');

    editarBtn.addEventListener('click', () => {
        inputs.forEach(input => input.disabled = false);
        editarBtn.style.display = 'none';
        actualizarBtn.style.display = 'block';
    });
</script>

</body>
</html>
